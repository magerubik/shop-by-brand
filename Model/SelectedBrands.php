<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */
 
namespace Magerubik\Shopbybrand\Model;
use Magerubik\ProductLabel\Api\Data\ProductLabelInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject\IdentityInterface;

class SelectedBrands extends \Magento\Framework\Model\AbstractModel
{
	protected function _construct()
	{
		$this->_init('Magerubik\Shopbybrand\Model\ResourceModel\SelectedBrands');
	}
	
}