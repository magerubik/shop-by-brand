<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */
namespace Magerubik\Shopbybrand\Controller\Adminhtml\Index;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
class Index extends \Magento\Backend\App\Action
{
	protected $resultPageFactory;
	public function __construct(Context $context, PageFactory $resultPageFactory)
	{
		parent::__construct($context);
		$this->resultPageFactory = $resultPageFactory;
	}
	public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $attributeCode = $this->_objectManager->get(\Magento\Framework\App\Config\ScopeConfigInterface::class)
                ->getValue('magerubik_shopbybrand/general/attribute_code', 'store', \Magento\Store\Model\Store::DEFAULT_STORE_ID);
        if ($attributeId = $this->getRequest()->getParam('attribute_id')) {
             $attr = $this->_objectManager->create(\Magento\Catalog\Model\ResourceModel\Eav\Attribute::class)->load($attributeId); 
        } else {
             $attr = $this->_objectManager->create(\Magento\Catalog\Model\ResourceModel\Eav\Attribute::class)->load($attributeCode, 'attribute_code'); 
        }
        $label = $attr->getFrontendLabel();
        if ($attributeCode == $attr->getAttributeCode()) {
            $title = __("Brand options ({$label})");
        } else {
            $title = __("Attribute options ({$label})");
        }
        $resultPage->setActiveMenu('Magerubik_Shopbybrand::shopbybrand');
        $resultPage->addBreadcrumb($title, $title);
        $resultPage->addBreadcrumb($title, $title);
        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }
	/**
     * Is the user allowed to view the menu grid.
     *
     * @return bool
     */
	protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magerubik_Shopbybrand::index');
    }
}