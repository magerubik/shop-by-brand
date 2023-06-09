<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */

/**
 * CatalogRule data helper
 */
namespace Magerubik\Shopbybrand\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_objectManager;
    protected $_scopeConfig;
    protected $_urlBuilder;
    protected $_imageHelper;
    protected $_brandFactory;
    protected $_storeManager;
    protected $_storeId;
    protected $_attributeCode;
    
    protected $_brandProducts = [];
    protected $_brandProductCount = [];
    protected $_blockFilter;
    protected $_viewRoute;
    
    const ATTR_CODE_CONFIG_PATH = 'magerubik_shopbybrand/general/attribute_code';
    const ROUTE_NAME_CONFIG_PATH = 'magerubik_shopbybrand/general/view_route_name';
    
	public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magerubik\Shopbybrand\Helper\Image $imageHelper,
        \Magerubik\Shopbybrand\Model\BrandFactory $brandFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_urlBuilder = $context->getUrlBuilder();
        $this->_imageHelper = $imageHelper;
        $this->_brandFactory = $brandFactory;
        $this->_storeManager = $storeManager;
        $this->_storeId = $this->_storeManager->getStore()->getId();
        $this->_attributeCode = $this->_scopeConfig->getValue(static::ATTR_CODE_CONFIG_PATH, 'store', $this->_storeId);
        $this->_viewRoute = $this->_scopeConfig->getValue(static::ROUTE_NAME_CONFIG_PATH, 'store', $this->_storeId);
    }
    
    public function getViewRoute()
    {
        return $this->_viewRoute;
    }
    
    public function getScopeConfig()
    {
        return $this->_scopeConfig;
    }
    
    public function getStoreManager()
    {
        return $this->_storeManager;
    }
    
    public function getStoreBrandCode() 
    {
        return $this->_attributeCode;
    }
    
    public function getImageHelper()
    {
        return $this->_imageHelper;
    }
    
    public function getUrl($path, $params = [])
    {
        return $this->_urlBuilder->getUrl($path, $params);
    }
    
    public function getBrandImage($brand, $type = 'brand_thumbnail', $options)
    {
        $brandThumb = $brand->getData($type);
        if ($type == 'brand_thumbnail') {
            if (!$brandThumb) {
				$repository = $this->_objectManager->get('Magento\Framework\View\Asset\Repository');
				return $repository->getUrl('Magerubik_Shopbybrand/images/placeholder_thumbnail.jpg');
            }
        }
		if ($brandThumb) {
			if (isset($options['width']) || isset($options['height'])) {
				if(!isset($options['width'])) {
					$options['width'] = null;
                }
				if(!isset($options['height'])) {
					$options['height'] = null;
                }
				return $this->_imageHelper->init($brandThumb)
                    ->resize($options['width'], $options['height'])->__toString();
			} else {
				return $this->_mediaUrl.$brand->getData($type);
			}
		}else{
			return '';
		}
	}
    
    public function getBrandPageUrl($brandModel)
    {
		if ($brandModel->getData('brand_url_key')) {
            return $this->getUrl($this->_viewRoute, ['_nosid' => true]) . $brandModel->getData('brand_url_key');
        } else {
            return $this->getUrl($this->_viewRoute, ['_nosid' => true]) . urlencode(str_replace([' ',"'"],['-','-'],strtolower(trim($brandModel->getData('brand_label')))));
        }
	}
    
    public function getProductCount($attributeCode, $optionId)
    {
        $key = $attributeCode.'_'.$optionId;
        if (!isset($this->_brandProductCount[$key])) {
            $collection = $this->_objectManager->get('\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory')->create();
            $collection->addAttributeToSelect([$attributeCode]);
            $collection->addAttributeToFilter($attributeCode, $optionId)->getSelect()->group('e.entity_id');
            $this->_brandProductCount[$key] = $collection->count();
        }
        return $this->_brandProductCount[$key];
    }
    
    public function getBrandByProduct($product, $attributeCode)
    {
        $attrValue = (int)$product->getData($attributeCode);
        if (!isset($this->_brandProducts[$attrValue])) {
            $brandModel = $this->_brandFactory->create()->setStoreId($this->_storeManager->getStore()->getId())
                ->setOptionId($attrValue)
                ->load(null);
            $brandModel->setUrl($this->getBrandPageUrl($brandModel));
            $brandModel->setThumbnail($this->getBrandImage($brandModel, 'brand_thumbnail', ['width' => 150]));
            $this->_brandProducts[$attrValue] = $brandModel;
        }
		return $this->_brandProducts[$attrValue];
	}
    
    public function getBlockFilter()
    {
        if ($this->_blockFilter === null) {
            $this->_blockFilter = $this->_objectManager->get('Magento\Cms\Model\Template\FilterProvider')->getBlockFilter();
        }
        return $this->_blockFilter;
    }
    
    public function htmlFilter($content)
    {
        return $this->getBlockFilter()->filter($content);
    }
}
