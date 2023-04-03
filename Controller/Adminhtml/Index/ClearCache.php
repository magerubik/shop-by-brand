<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */

namespace Magerubik\Shopbybrand\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\TestFramework\ErrorLog\Logger;
use Magento\Framework\Exception\LocalizedException;

class ClearCache extends \Magento\Backend\App\Action
{
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magerubik_Shopbybrand::save');
    }
    
    public function execute()
    {
        $imageHelper = $this->_objectManager->create('Magerubik\Shopbybrand\Helper\Image');
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            $imageHelper->clearCache();
            $this->messageManager->addSuccess(__('Brand image cache was cleared successfully.'));
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\RuntimeException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            $this->messageManager->addException($e, __('Something went wrong while clearing image cache.'));
        }
        return $resultRedirect->setPath('*/*/', ['_current' => true]);
        
    }
}