<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */
namespace Magerubik\Shopbybrand\Block\Adminhtml\Shopbybrand\AbstractHtmlField;
use Magento\Framework\Escaper;
class Image extends \Magerubik\Shopbybrand\Block\Adminhtml\Shopbybrand\AbstractHtmlField
{
    /**
     * Form element which re-rendering
     *
     * @var \Magento\Framework\Data\Form\Element\Fieldset
     */
    protected $_element;
    /**
     * @var string
     */
    protected $_template = 'shopbybrand/fieldset/image.phtml';
    /**
     * Retrieve an element
     *
     * @return \Magento\Framework\Data\Form\Element\Fieldset
     */
    public function getElement()
    {
        return $this->_element;
    }
    /**
     * Render element
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->_element = $element;
        return $this->toHtml();
    }
    /**
     * Return html for store switcher hint
     *
     * @return string
     */
	public function getBaseUrl()
    {		
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
    public function getHintHtml()
    {
		$element = $this->_element;
		$elementId = $element->getHtmlId();
		$imageId = $elementId.'_image';
		if( !empty( $element->getValue() ) ){
			$imageUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$element->getValue();
		}else{
			$imageUrl = $this->assetRepo->getUrl('Magerubik_Shopbybrand/images/placeholder_thumbnail.jpg');	
		}
		$html = $this->_getButtonHtml(
			[
				'title' => __('Upload'),
				'style' => ' display: block; margin-bottom: 10px;',
				'onclick' => "HkkMediabrowserUtility.openDialog(this, '" . $this->getUrl('cms/wysiwyg_images/index',
					['target_element_id' => $elementId])
					. "', null, null,'" . $this->escapeQuote(
						__('Upload Images'), true) . "');",
				'disabled' => $element->getDisabled()
			]
		);
        $html .= '<div class="file-uploader-preview" style="display: flex;align-items: center;"><a class="attached_image" style="float: left;" href="'.$imageUrl.'" onclick="imagePreview(\''.$imageId.'\'); return false;"><img class="preview-image" '.(($element->getDisabled())?'style="display:none"':'').' id="'.$imageId.'" src="'.$imageUrl.'" /></a>';
		$html .= '<div class="actions"><button class="action-remove clear-img scalable" onclick="clearValue(this,\''.$elementId.'\');return false;"'.($element->getDisabled()?'disabled':'').'><span><span><span>&times;</span></span></span></button></div></div>';
        return $html;
    }
}