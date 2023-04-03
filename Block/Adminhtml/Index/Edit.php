<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */
 
namespace Magerubik\Shopbybrand\Block\Adminhtml\Index;

use Magento\Framework\App\ObjectManager;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
	protected $_coreRegistry;
    
    protected $_objectManager;
    
	public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_objectManager = ObjectManager::getInstance();
        parent::__construct($context, $data);
    }
	
	protected function _construct()
    {
        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'Magerubik_Shopbybrand';
        $this->_controller = 'adminhtml_index';
		
        parent::_construct();

        if ($this->_isAllowedAction('Magerubik_Shopbybrand::save')) {
            $this->buttonList->update('save', 'label', __('Save'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }
		
		$this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('block_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'block_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'block_content');
                }
            }
        ";
        $this->buttonList->remove('delete');
    }
    
	public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('brand')->getId()) {
            return __("Edit '%1'", $this->escapeHtml($this->_coreRegistry->registry('brand')->getTitle()));
        } else {
            return __('Edit Brand');
        }
    }
	
    
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
    
	protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('shopbybrand/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}