<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */
namespace Magerubik\Shopbybrand\Block\Adminhtml\Index\Edit;

use Magento\Backend\Block\Widget\Form as WidgetForm;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
	/**
     * @var \Magento\Store\Model\System\Store
     */
	protected $_systemStore;
	/**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
	 /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('brand_form');
        $this->setTitle(__('Brand Information'));
    }
	/**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('brand');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
				'data' => [
					'id' => 'edit_form',
					'action' => $this->getData('action'),
					'method' => 'post',
					'class' => 'mrlayout–label-top',
					'enctype' => 'multipart/form-data'
				]
			]
        );
		$form->setHtmlIdPrefix('brand_');
		$scopeConfig = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\Config\ScopeConfigInterface');
		$fieldset = $form->addFieldset(
			'base_fieldset',
			['class' => 'mradmin-left-wrapper']
		);
		if ($model->getEntityId()) {
			$fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
		}else{
			$model->addData([
				'is_active' => 1,
				'brand_is_featured' => $scopeConfig->getValue('magerubik_shopbybrand/featured_brands/brand_is_featured_by_default')
			]);
		}
		$fieldset->addField('store', 'hidden', ['name' => 'store']);
		
		if ($model->getOptionId()) {
			$fieldset->addField('option_id', 'hidden', ['name' => 'option_id']);
		}
		$fieldset->addField(
			'brand_label',
			'label',
			['name' => 'brand_label', 'label' => __('Brand Label'), 'title' => __('Brand Label')]
		);
		$fieldset->addField(
			'is_active',
			'select',
			['name' => 'is_active', 'label' => __('Active'), 'title' => __('Active'),
				'required' => true,
				'options' => ['1' => __('Yes'), '0' => __('No')]
			]
		);
		$fieldset->addField(
			'brand_is_featured',
			'select',
			['name' => 'brand_is_featured', 'label' => __('Is Featured'), 'title' => __('Is Featured'),
				'required' => true,
				'options' => ['1' => __('Yes'), '0' => __('No')]
			]
		);
		$renderer = $this->getLayout()->createBlock(
			'Magerubik\Shopbybrand\Block\Adminhtml\Shopbybrand\AbstractHtmlField\Image'
		);
		
		$field = $fieldset->addField(
			'brand_thumbnail',
			'hidden',
			['name' => 'brand_thumbnail', 'label' => __('Brand Logo'), 'title' => __('Brand Logo'), 'required' => false, 'class' => 'input-image', 'onchange' => 'changePreviewImage(this)']
		);
		$field->setRenderer($renderer);
		$field = $fieldset->addField(
			'brand_cover',
			'hidden',
			['name' => 'brand_cover', 'label' => __('Brand Banner'), 'title' => __('Brand Banner'), 'required' => false, 'class' => 'input-image', 'onchange' => 'changePreviewImage(this)']
		);
		$field->setRenderer($renderer);		
		
		$fieldset2 = $form->addFieldset(
			'seo_fieldset',
			['class' => 'mradmin-content-wrapper']
		);
		$fieldset2->addField(
			'brand_description',
			'editor',
			['name' => 'brand_description', 'config' => $this->getWysiwygConfig(), 'label' => __('Description'), 'title' => __('Description'), 'required' => false]
		);
		$fieldset2->addField(
			'brand_url_key',
			'text',
			['name' => 'brand_url_key', 'label' => __('URL Key'), 'title' => __('URL Key'), 'required' => false]
		);
        
        $fieldset2->addField(
			'brand_meta_title',
			'text',
			['name' => 'brand_meta_title', 'label' => __('Meta Title'), 'title' => __('Meta Title'), 'required' => false]
		);
        
        $fieldset2->addField(
			'brand_meta_description',
			'textarea',
			['name' => 'brand_meta_description', 'label' => __('Meta Description'), 'title' => __('Meta Description'), 'required' => false]
		);
        
        $fieldset2->addField(
			'brand_meta_keyword',
			'text',
			['name' => 'brand_meta_keyword', 'label' => __('Meta Keyword'), 'title' => __('Meta Keyword'), 'required' => false]
		);
        $form->setDataObject($model);
		$form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

	public function getWysiwygConfig()
	{
        $config = [];
		$config['container_class'] = 'hor-scroll';
		$wysiwygConfig = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Cms\Model\Wysiwyg\Config');
		return $wysiwygConfig->getConfig($config);	
	}
}