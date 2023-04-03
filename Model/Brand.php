<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */
 
namespace Magerubik\Shopbybrand\Model;
use Magento\Catalog\Model\Product;

class Brand extends \Magento\Framework\Model\AbstractModel
{
	const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
	const CACHE_TAG = 'magerubik_brand';

	protected $_cacheTag = 'magerubik_brand';
	protected $_eventPrefix = 'magerubik_brand';
	protected $_eventObject = 'magerubik_brand';
	
	protected $_storeValuesFlags = [];
    
    protected $_objectManager;
	public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
		parent::__construct($context, $registry, $resource, $resourceCollection);
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	}
	
    protected function _construct()
	{
		parent::_construct();
		$this->_init('Magerubik\Shopbybrand\Model\ResourceModel\BrandEntity');
	}
	public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
	
    public function setExistsStoreValueFlag($attributeCode)
    {
        $this->_storeValuesFlags[$attributeCode] = true;
        return $this;
    }
    
	public function getExistsStoreValueFlag($attributeCode)
    {
        return array_key_exists($attributeCode, $this->_storeValuesFlags);
    }
    
	private function getExtensionFactory()
    {
        return $this->_objectManager->get(\Magento\Framework\Api\ExtensionAttributesFactory::class);
    }
    
	private function getCustomAttributeFactory()
    {
        return $this->_objectManager->get(\Magento\Framework\Api\AttributeValueFactory::class);
    }
}