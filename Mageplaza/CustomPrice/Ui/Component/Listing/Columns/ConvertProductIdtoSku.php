<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mageplaza\CustomPrice\Ui\Component\Listing\Columns;

use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class ConvertProductIdtoSku extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;


    /**
     * @var ProductFactory
     */
    protected $productFactory;


    /**
     * ConvertProductIdtoSku constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param ProductFactory $productFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        ProductFactory $productFactory,
        array $components = [],
        array $data = []
    ) {
        $this->productFactory = $productFactory;
        $this->urlBuilder = $urlBuilder;
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
        $product = $this->productFactory->create();
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $product->load($item[$this->getName()]);
                $item[$this->getName()] = $product->getData('sku');
            }
        }
        return $dataSource;
    }
}
