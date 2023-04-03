<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */

namespace Magerubik\Shopbybrand\Model\Config\Source;

class AttributeCode implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
    {
        $collection = \Magento\Framework\App\ObjectManager::getInstance()->get('Magerubik\Shopbybrand\Model\ResourceModel\SelectedBrands');
		return $collection->getAttributeCodeList();
    }	
	
}
