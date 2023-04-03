<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */

namespace Magerubik\Shopbybrand\Block\Widget;

class BrandList extends \Magerubik\Shopbybrand\Block\Widget\BrandAbstract
{
	protected $_template = 'brand/brand_list.phtml';
	protected $_cacheTag = 'BRAND_SEARCH';
}