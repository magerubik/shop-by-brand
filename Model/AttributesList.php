<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */

namespace Magerubik\Shopbybrand\Model;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface;

class AttributesList extends \Magento\Eav\Model\Entity\Attribute implements \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface
{
    const MODULE_NAME = 'Magerubik_Shopbybrand';
    
    const KEY_IS_GLOBAL = 'is_global';
    
    const ENTITY = 'catalog_product';
    
    protected $_eventObject = 'attribute';
    
    protected $_eventPrefix = 'shopbybrand_entity_attribute';
    
    protected function _construct()
    {
        $this->_init('Magerubik\Shopbybrand\Model\ResourceModel\AttributesList');
    }
    
    public function isScopeStore()
    {
        return !$this->isScopeGlobal() && !$this->isScopeWebsite();
    }
	
    public function isScopeGlobal()
    {
        return $this->getIsGlobal() == self::SCOPE_GLOBAL;
    }
	
    public function isScopeWebsite()
    {
        return $this->getIsGlobal() == self::SCOPE_WEBSITE;
    }
    
    public function __sleep()
    {
        $this->unsetData('entity_type');
        return parent::__sleep();
    }
    
}
