<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */

namespace Magerubik\Shopbybrand\Model\Config\Source;

class Sortby implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
    {
        return [
            ['value' => 'brand_label',  'label' => __('Brand Label')],
            ['value' => 'sort_order',   'label' => __('Attribute Option Sort Order')]
        ];
    }	
	
}