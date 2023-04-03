<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */

namespace Magerubik\Shopbybrand\Model\ResourceModel\SelectedBrands\Grid;

class Collection extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
	protected function _construct()
    {
		parent::_construct();

    }
	protected function _beforeLoad()
    {
        parent::_beforeLoad();
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $request = $objectManager->get('Magento\Framework\App\Request\Http');
        $search = $request->getParam('search', false);
		$storeId = \Magento\Store\Model\Store::DEFAULT_STORE_ID;
        
        $configAttributeCode = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Framework\App\Config\ScopeConfigInterface')
                ->getValue('magerubik_shopbybrand/general/attribute_code', 'store', $storeId);
        
        $optionValueTable = $this->getConnection()->select()
            ->from($this->getTable('eav_attribute_option_value'), ['oid' => 'option_id', 'store_id', 'brand_label' => 'value'])
            ->where("store_id = {$storeId}");
        
		$this->getSelect()
			->joinLeft(array('cea' => $this->getTable('catalog_eav_attribute') ),'main_table.attribute_id = cea.attribute_id','is_visible')
			->joinLeft(array('ea' => $this->getTable('eav_attribute') ),'cea.attribute_id = ea.attribute_id', ['attribute_code'])
			->joinLeft(array('eaov' => $optionValueTable ), 'eaov.oid = main_table.option_id', ['brand_label']);
        $this->getSelect()->where("ea.attribute_code = '{$configAttributeCode}'")->group("main_table.option_id");
        if ($search) {
            $this->getSelect()->where("eaov.brand_label LIKE '%{$search}%'");
        }
    }
}
