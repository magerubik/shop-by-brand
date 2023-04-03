<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */
 
namespace Magerubik\Shopbybrand\Setup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
class InstallData implements InstallDataInterface
{
    private $labelSetupFactory;
	public function __construct(BrandSetupFactory $brandSetupFactory)
    {
        $this->brandSetupFactory = $brandSetupFactory;
    }
	public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
		$labelSetup = $this->brandSetupFactory->create(['setup' => $setup]);
        $labelSetup->installEntities();		
	}
}