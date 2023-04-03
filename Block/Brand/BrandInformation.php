<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */

namespace Magerubik\Shopbybrand\Block\Brand;

class BrandInformation extends \Magento\Framework\View\Element\Template  implements \Magento\Framework\DataObject\IdentityInterface
{
	protected $_coreRegistry = null;

    protected $_helper;
    
    protected $_copeConfig;
    
    protected $_attributeCode;
    
    protected $_mediaUrl;
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magerubik\Shopbybrand\Helper\Data $helper,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_storeManager = $context->getStoreManager();
		$this->_mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		$this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$this->_assetRepository = $this->_objectManager->get('Magento\Framework\View\Asset\Repository');
		$this->_helper = $helper;
		$this->_copeConfig = $context->getScopeConfig();
		$this->_attributeCode = $this->_helper->getStoreBrandCode();
        parent::__construct($context, $data);
    }
	
	public function getBrandAttributeCode(){
		return $this->_attributeCode;
	}
	
    public function getConfig($path)
	{
		return $this->_copeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);	
	}
	
	public function getIdentities()
    {
		return $this->getBrand() ? ['brand_information_'.$this->getBrand()->getOptionId()] : 
            ($this->_coreRegistry->registry('current_product') ? [$this->_coreRegistry->registry('current_product')->getData($this->_attributeCode)] : []);
	}
	
    public function getBrand()
    {
        if (!$this->hasData('brand')) {
            $this->setData('brand', $this->_coreRegistry->registry('current_brand'));
        }
        return $this->getData('brand');
    }
	
    public function getBrandOfCurrentProduct()
    {
		if ($product = $this->_coreRegistry->registry('current_product')) {
			return $this->getBrandByProduct($product);
		}else{
			return null;	
		}
	}
	
    public function getBrandLabel()
    {
		if ($brandProducts = $this->getBrand()) {
			return $brandProducts->getBrandLabel();
		}
        return '';
	}
	
    public function getBrandByProduct($product)
    {
		return $this->_helper->getBrandByProduct($product, $this->_attributeCode);
	}
	
    public function getThumbnailImage($brand, array $options = [])
    {
		return $this->_helper->getBrandImage($brand, 'brand_thumbnail', $options);
	}
	
    public function getCoverImage($brand, array $options = [])
    {
		return $this->_helper->getBrandImage($brand, 'brand_cover', $options);
	}
	
    public function getBrandImage($brand, $type = 'brand_thumbnail', $options)
    {
		return $this->_helper->getBrandImage($brand, $type, $options);
	}
    
	public function limitStringLength($string, $length)
    {
		$string = strip_tags($string);
		if(strlen($string) > $length) {
			$shortString = substr($string, 0, $length);
			$string = substr($shortString, 0, strrpos($shortString, ' '))."&hellip;";
		}
		return $string;
	}
}