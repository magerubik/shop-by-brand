<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */
 
namespace Magerubik\Shopbybrand\Model\Shopbybrand\Source;
class IsActive implements \Magento\Framework\Data\OptionSourceInterface
{
	protected $brand;
	public function __construct(\Magerubik\Shopbybrand\Model\Brand $brand)
    {
        $this->brand = $brand;
    }
	public function toOptionArray()
	{
		$options[] = ['label' => '', 'value' => ''];
		$availableOptions = $this->brand->getAvailableStatuses();
		foreach ($availableOptions as $key => $value) {
			$options[] = [
				'label' => $value,
				'value' => $key,
			];
		}
		return $options;
	}
}