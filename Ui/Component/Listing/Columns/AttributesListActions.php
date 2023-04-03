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
class AttributesListActions extends Column
{
	/** Url path */
	const BRAND_INDEX_PATH = 'shopbybrand/index/index';
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
		$editUrl = self::BRAND_INDEX_PATH
	) {
		$this->urlBuilder = $urlBuilder;
		$this->editUrl = $editUrl;
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
				if (isset($item['attribute_id'])) {
					$item[$name]['edit'] = [
						'href' => $this->urlBuilder->getUrl($this->editUrl, ['attribute_id' => $item['attribute_id']]),
						'label' => __('Edit Options')
					];
				}
			}
		}
		return $dataSource;
	}
}