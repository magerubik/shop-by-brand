<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */

namespace Magerubik\Shopbybrand\Block\Widget;
use Magento\Framework\View\Element\Template;
use Magerubik\Shopbybrand\Model\BrandFactory as BrandFactory;

class BrandAbstract extends Template implements \Magento\Widget\Block\BlockInterface
{
	protected $_brandFactory;
	protected $_brandObject;
	protected $_mediaUrl;
	protected $_objectManager;
	protected $_context;
	protected $_attributeCode;
	protected $_assetRepository;
	protected $_imageHelper;
	protected $_cacheTag = 'HKK_BRAND';
	protected $_template = '';
	protected $_categoryHeper = null;
	protected $_categoryRepository = null;
    protected $_coreRegistry = null;
    protected $_brandIsFeturedByDefault;
    protected $_copeConfig;
    protected $_helper;
	
	public function __construct(
		Template\Context $context,
		BrandFactory $brandFactory,
		\Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\Registry $coreRegistry,
        \Magerubik\Shopbybrand\Helper\Data $helper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\Layer\Category\FilterableAttributeList $FilterableAttributeList,
        array $data = []
	){
        
		parent::__construct($context, $data);
		$this->_brandFactory = $brandFactory;
		$this->httpContext = $httpContext;
		$this->_context = $context;
        $this->_storeManager = $context->getStoreManager();
		$this->_mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		$this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$this->_assetRepository = $context->getAssetRepository();
		$this->_helper = $helper;
        $this->_coreRegistry = $coreRegistry;
        $this->_copeConfig = $context->getScopeConfig();
		$this->_attributeCode = $this->_helper->getStoreBrandCode();
        $this->_brandIsFeturedByDefault = $this->_copeConfig->getValue('magerubik_shopbybrand/featured_brands/brand_is_featured_by_default', 'store');
        $this->addData([
            'cache_lifetime' => 86400,
            'cache_tags' => [$this->_cacheTag]
		]);
	}
	
    public function getConfigValue($path)
    {
        return $this->_copeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    public function getAttributeCode()
    {
		return $this->_attributeCode;
	}
    
	public function getBrandObject($orderBy = 'brand_label', $order = 'asc', $onlyFeaturedBrands = false, $getCount = false, $limit = false)
    {
        $finalFilters = [];
        $registryName = 'hkk_brand_' . $orderBy . '_' . $order . '_' . (string)$onlyFeaturedBrands . '_' . (string)$limit;
        if ($this->_coreRegistry->registry($registryName)) {
            $this->_coreRegistry->unregister($registryName);
        }
        $brandObject = $this->_coreRegistry->registry($registryName);
		if (!$brandObject) {
            $brandObject = [];
			$brand = $this->_brandFactory->create();		
			$col = $brand->getCollection();
			$connection = $col->getConnection();
            
            $defaultStoreId = \Magento\Store\Model\Store::DEFAULT_STORE_ID;
            $storeId = $this->_storeManager->getStore()->getId();
            
			$select = $connection->select();
			$select->from(['main_table' => $col->getTable('eav_attribute_option') ], ['option_id', 'sort_order'])
				->joinLeft([ 'cea' => $col->getTable('catalog_eav_attribute') ],'main_table.attribute_id = cea.attribute_id', ['attribute_id'])
				->joinLeft([ 'ea' => $col->getTable('eav_attribute') ],'cea.attribute_id = ea.attribute_id', ['attribute_code'])
				->joinLeft([ 'eaov' => $col->getTable('eav_attribute_option_value') ], 'eaov.option_id = main_table.option_id', ['brand_label' => 'value', 'store_id'])
				->where("ea.attribute_code = '{$this->_attributeCode}'")
                ->where("eaov.store_id IN ({$defaultStoreId}, {$storeId})")
				->group("main_table.option_id")
				->order($orderBy.' '.$order);
            if ($onlyFeaturedBrands) {
                if ($this->_brandIsFeturedByDefault) {
                    $featuredIds = $this->_brandFactory
                        ->create()
                        ->getCollection()->setStore($storeId)
                        ->addAttributeToFilter('brand_is_featured', 0)
                        ->addFieldToSelect(['option_id'])->getColumnValues('option_id');
                    if ($featuredIds) {
                        $featuredIds = implode(',', $featuredIds);
                        $select->where("main_table.option_id not in ({$featuredIds})");
                    }
                    
                } else {
                    $featuredIds = $this->_brandFactory
                        ->create()
                        ->getCollection()->setStore($storeId)
                        ->addAttributeToFilter('brand_is_featured', 1)
                        ->addFieldToSelect(['option_id'])->getColumnValues('option_id');
                    if (!$featuredIds) {
                        $featuredIds = '-1';
                    } else {
                        $featuredIds = implode(',', $featuredIds);
                    }
                    $select->where("main_table.option_id in ({$featuredIds})");
                }
            }
            
            if ($limit) {
                $select->limit($limit);
            }

			$rows = $connection->fetchAll($select);
            
            if (count($rows) > 0) {
                $optionIds = [];
                foreach ($rows as $row) {
                    $optionIds[] = $row['option_id'];
                }
                $brandItems = $this->_brandFactory
                    ->create()
                    ->getCollection()->setStore($storeId)
                    ->addFieldToFilter('option_id', ['in' => $optionIds])
                    ->addAttributeToSelect(['brand_thumbnail', 'brand_url_key', 'brand_is_featured'])->getItems();
                $brands = [];
                foreach ($brandItems as $brandItem) {
                    $brands[$brandItem->getData('option_id')] = $brandItem;
                }
				foreach ($rows as $row) {
                    $optionId = $row['option_id'];
                    $row['product_count'] = $this->_helper->getProductCount($this->_attributeCode, $optionId);
                    if (isset($brands[$optionId])) {
                        if ($onlyFeaturedBrands && (!$brands[$optionId]->getData('brand_is_featured'))) {
                            continue;
                        }
                        if (!$brands[$optionId]->getData('is_active')) {
                            continue;
                        }
                        $brandModel = $brands[$optionId]->addData($row);
                    } else {
                        if ($onlyFeaturedBrands && !$this->_brandIsFeturedByDefault) {
                            continue;
                        }
                        $brandModel = new \Magento\Framework\DataObject($row);
					}
                    $brandModel->setUrl($this->_helper->getBrandPageUrl($brandModel));
                    $brandObject[] = $brandModel;
				}
			}
            $this->_coreRegistry->register($registryName, $brandObject);
		}
		return $brandObject;
	}
    
	public function getTemplate()
    {
		if ($this->getData('custom_template') != '') {
			return $this->getData('custom_template');
		} else {
            if (parent::getTemplate()) {
                return parent::getTemplate();
            } else {
                return $this->_template;
            }
		}
    }
    
	public function getThumbnailImage($brand, array $options = [])
    {
		return $this->_helper->getBrandImage($brand, 'brand_thumbnail', $options);
	}

	public function getBrandPageUrl($brand)
    {
		return $this->_helper->getBrandPageUrl($brandModel);
	}

	public function getCacheKeyInfo()
    {
        return [
            $this->_cacheTag,
            $this->_storeManager->getStore()->getId(),
            $this->_design->getDesignTheme()->getId(),
            $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP),
            $this->getCustomTemplate()
        ];
    }

	public function getIdentities()
    {
        return [$this->_cacheTag . '_' . $this->getCustomTemplate()];
    }

}