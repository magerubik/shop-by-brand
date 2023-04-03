<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Shopbybrand
 */
namespace Magerubik\Shopbybrand\Ui\Component\Listing\Columns;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magerubik\Shopbybrand\Helper\Data as ShopbybrandHelper;
class ShopbybrandActions extends Column
{
	/** @var UrlInterface */
    protected $urlBuilder;
	 /**
     * @var string
     */
    private $editUrl;
	/**
	* @param ContextInterface $context
	* @param UiComponentFactory $uiComponentFactory
	* @param UrlInterface $urlBuilder
	* @param array $components
	* @param array $data
	* @param string $editUrl
	*/
	public function __construct(
		ContextInterface $context,
		UiComponentFactory $uiComponentFactory,
		UrlInterface $urlBuilder,
		array $components = [],
		array $data = [],
		ShopbybrandHelper $helper
	) {
		$this->urlBuilder = $urlBuilder;
		$this->helper = $helper;
		parent::__construct($context, $uiComponentFactory, $components, $data);
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
			foreach ($dataSource['data']['items'] as & $item) {
				$name = $this->getData('name');
				if (isset($item['option_id'])) {
					$item[$name]['edit'] = [
						'href' => $this->urlBuilder->getUrl('shopbybrand/index/edit', ['option_id' => $item['option_id']]),
						'label' => __('Edit')
					];
					if (isset($item['brand_object'])) {
                        $brand = $item['brand_object'];
                    } else {
                        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
						$model = $objectManager->create('Magerubik\Shopbybrand\Model\Brand');
                        $model->setOptionId($item['option_id']);
                        $brand = $model->load(null);
                        $item['brand_object'] = $brand;
                    }
					$item[$name]['preview'] = [
						'href'      => $this->helper->getBrandPageUrl($brand),
						'label'     => __('Preview'),
                        'target'    => 'blank'
					];
				}
			}
		}
		return $dataSource;
	}
}