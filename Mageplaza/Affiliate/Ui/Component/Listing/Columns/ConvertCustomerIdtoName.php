<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mageplaza\Affiliate\Ui\Component\Listing\Columns;

use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class ConvertCustomerIdtoName extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * ConvertFields constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param CustomerFactory $customerFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        CustomerFactory $customerFactory,
        array $components = [],
        array $data = []
    ) {
        $this->customerFactory = $customerFactory;
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
        $customer = $this->customerFactory->create();
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $customer->load($item[$this->getName()]);
                $item[$this->getName()] = $customer->getData('firstname').' '.$customer->getData('lastname');
            }
        }
        return $dataSource;
    }
}
