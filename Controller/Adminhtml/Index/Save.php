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
class Save extends \Magento\Backend\App\Action
{
	protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magerubik_Shopbybrand::save');
    }	
	public function execute()
    {
        $request = $this->getRequest();
        $data = $request->getPostValue();
		$storeId = (int)$request->getParam('store');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
			$id = $this->getRequest()->getParam('entity_id');
            $model = $this->_objectManager->create('Magerubik\Shopbybrand\Model\Brand');
			$this->_eventManager->dispatch(
				'product_brand_prepare_save',
				['product_brand' => $model, 'request' => $this->getRequest()]
			);
            $connection = $model->getResource()->getConnection();
            $select = $connection->select()->from(
                $model->getResource()->getTable('eav_attribute_option'), 'attribute_id'
            )->where('option_id = '.$data['option_id'])->limit(1);
            $attributeId = $connection->fetchOne($select);
            $data['attribute_id'] = $attributeId;
			$model->setStoreId($storeId);
			$model->setOptionId($data['option_id']);
			if ($id) {
                $model->load($id);
				if ($id != $model->getId()) {
					throw new LocalizedException(__('Wrong brand specified.'));
				}
            }
			$model->addData($data);
            if ($useDefaults = $request->getPost('use_default')) {
                foreach ($useDefaults as $attributeCode) {
                    $model->setData($attributeCode, false);
                }
            }
            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved this brand.'));
				$this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($request->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', [
                        'entity_id'         => $model->getId(),
                        '_current'          => true,
                        'store'             => $storeId,
                        'option_id'         => $data['option_id'],
                        'attribute_id'      => $attributeId,
                    ]);
                }
                return $resultRedirect->setPath('*/*/', ['attribute_id' => $attributeId]);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
				$this->messageManager->addError($e->getMessage());
                $this->messageManager->addException($e, __('Something went wrong while saving the label.'));
            }
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', [
                'option_id'         => $request->getParam('option_id'),
                'entity_id'         => $model->getId(),
                'attribute_id'      => $attributeId,
            ]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}