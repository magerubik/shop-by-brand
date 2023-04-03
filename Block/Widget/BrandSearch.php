<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */

namespace Magerubik\Shopbybrand\Block\Widget;

class BrandSearch extends \Magerubik\Shopbybrand\Block\Widget\BrandAbstract
{
	protected $_template = 'widget/brand_search.phtml';
	protected $_cacheTag = 'BRAND_LIST';
}