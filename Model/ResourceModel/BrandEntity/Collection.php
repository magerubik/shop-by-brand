<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */
 
namespace Magerubik\Shopbybrand\Model\ResourceModel\BrandEntity;

class Collection extends AbstractCollection
{
	protected function _construct()
    {
		$this->_init('Magerubik\Shopbybrand\Model\Brand', 'Magerubik\Shopbybrand\Model\ResourceModel\BrandEntity');
    }
}
