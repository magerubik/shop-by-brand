<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */

namespace Magerubik\Shopbybrand\Block\Brand;

class AllBrands extends \Magento\Framework\View\Element\Template implements \Magento\Framework\DataObject\IdentityInterface
{
    protected $_coreRegistry = null;
    
    protected $_scopeConfig = null;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_scopeConfig = $context->getScopeConfig();
        parent::__construct($context, $data);
    }
    
    public function getIdentities()
    {
        return ['magerubik_all_brands_page'];
    }
    
    public function getPageInfo()
    {
         if (!$this->_coreRegistry->registry('all_brands_info')) {
             $brands = new \Magento\Framework\DataObject([
                'title'                     => $this->_scopeConfig->getValue('magerubik_shopbybrand/all_brand_page/title', 'store')?:__('Our Brands'),
                'description'               => $this->_scopeConfig->getValue('magerubik_shopbybrand/all_brand_page/description', 'store')?:'',
                'display_featured_brands'   => $this->_scopeConfig->getValue('magerubik_shopbybrand/all_brand_page/display_featured_brands', 'store'),
                'display_brand_search'      => $this->_scopeConfig->getValue('magerubik_shopbybrand/all_brand_page/display_brand_search', 'store'),
                'meta_title'                => $this->_scopeConfig->getValue('magerubik_shopbybrand/all_brand_page/meta_title', 'store'),
                'meta_keywords'             => $this->_scopeConfig->getValue('magerubik_shopbybrand/all_brand_page/meta_keywords', 'store'),
                'meta_description'          => $this->_scopeConfig->getValue('magerubik_shopbybrand/all_brand_page/meta_description', 'store'),
                'featured_brand_title'      => $this->_scopeConfig->getValue('magerubik_shopbybrand/featured_brands/title', 'store')
            ]);
            $this->_coreRegistry->register('all_brands_info', $brands);
         }
         return $this->_coreRegistry->registry('all_brands_info');
    }
    
}