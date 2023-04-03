<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */
namespace Magerubik\Shopbybrand\Ui\Component\Listing\Columns;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
class Urlkey extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
		$this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {	
   	    if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $model = $this->_objectManager->create('Magerubik\Shopbybrand\Model\Brand');
                $model->setOptionId($item['option_id']);
                $brand = $model->load(null);
                $data = $brand->getData();
                if (isset($data['brand_url_key']) && $data['brand_url_key'] != '') {
                     $item[$fieldName] = $data['brand_url_key'];
                } else {
                     $item[$fieldName] = urlencode(str_replace([' ',"'"],['-','-'],strtolower(trim($data['brand_label']))));
                }
            }
        }
        return $dataSource;
    }
}