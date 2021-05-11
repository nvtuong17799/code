<?php

namespace Mageplaza\CustomPrice\Controller\Index;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Mageplaza\CustomPrice\Model\ResourceModel\CustomPrice\CollectionFactory;

class ProcessAjax extends Action
{
    protected $_session;
    protected $data;
    protected $productFactory;
    protected $customPriceFactory;
    protected $_priceCurrency;
    protected $_checkoutSession;
    protected $_date;

    public function __construct(
        Context $context,
        Session $session,
        CollectionFactory $customPriceFactory,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    )
    {
        $this->_date = $date;
        $this->customPriceFactory = $customPriceFactory;
        $this->_priceCurrency = $priceCurrency;
        $this->_session = $session;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->_request->getParam('product');
        $date = $this->_date->gmtDate();
        $resultJson = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON);
        $customPrice = $this->customPriceFactory->create();
        $customPrice->addFieldToFilter('customer_id', $this->_session->getCustomerId())
            ->addFieldToFilter('product_id', $id);
        foreach ($customPrice as $item) {
            if ($date >= $item['start_date'] && $date <= $item['end_date']) {
                $price = $this->_priceCurrency->format(
                    $item['price'],
                    false,
                    2
                );
                $resultJson->setData($price);
            }
        }
        return $resultJson;
    }
}
