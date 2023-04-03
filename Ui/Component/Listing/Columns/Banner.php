<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */
 
namespace Magerubik\Shopbybrand\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class Banner extends \Magento\Ui\Component\Listing\Columns\Column
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
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
		$this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$this->_imageHelper = $this->_objectManager->get('Magerubik\Shopbybrand\Helper\Image');
        $this->_urlBuilder = $urlBuilder;
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
			$objectManager = $this->_objectManager;
			$mediaUrl = $objectManager->get('\Magento\Store\Model\StoreManagerInterface')->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
			$repository = $objectManager->get('Magento\Framework\View\Asset\Repository');
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $model = $objectManager->create('Magerubik\Shopbybrand\Model\Brand');
                $model->setOptionId($item['option_id']);
                $brand = $model->load(null);
				if($brand->getBrandCover()){
					$brandThumbnail = $this->_imageHelper->init($brand->getBrandCover())->resize(120,50)->__toString();
					$brandOriginal = $mediaUrl.$brand->getBrandCover();
				}else{
					$brandThumbnail = $brandOriginal = $repository->getUrl('Magerubik_Shopbybrand/images/placeholder_thumbnail.jpg');
				}
                $item[$fieldName . '_src'] = $brandThumbnail;
                $item[$fieldName . '_alt'] = $brand->getBrandLabel();
                $item[$fieldName . '_link'] = $this->_urlBuilder->getUrl('shopbybrand/index/edit', ['option_id' => $item['option_id']]);
                $item[$fieldName . '_orig_src'] = $brandOriginal;
            }
        }
        return $dataSource;
    }
}
