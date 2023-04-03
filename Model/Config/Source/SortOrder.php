<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */

namespace Magerubik\Shopbybrand\Model\Config\Source;

class SortOrder implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
    {
        return [
            ['value' => 'asc',      'label' => __('ASC')],
            ['value' => 'desc',     'label' => __('DESC')]
        ];
    }	
	
}