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

class ConvertToTitle extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * ConvertFields constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
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
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
               if($item['is_admin_change'] == 1){
                   $item['order_increment_id'] = 'Changed by Admin';
               }else{
                   $item['order_increment_id'] = 'Created from order #'.$item['order_increment_id'];
               }
            }
        }
        return $dataSource;
    }
}
