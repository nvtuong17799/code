<?php
namespace Mageplaza\CustomPrice\Observer;

use Magento\Checkout\Model\Session;
use Mageplaza\CustomPrice\Model\ResourceModel\CustomPrice\CollectionFactory;

class ChangePriceInQuote implements \Magento\Framework\Event\ObserverInterface
{
    protected $_collectionFactory;

    protected $_checkoutSession;

    protected $_date;

    public function __construct(
        CollectionFactory $collectionFactory,
        Session $checkoutSession,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) {
        $this->_date = $date;
        $this->_collectionFactory = $collectionFactory;
        $this->_checkoutSession = $checkoutSession;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quoteItem = $observer->getEvent()->getData('quote_item');
        $quoteItem = ( $quoteItem->getParentItem() ? $quoteItem->getParentItem() : $quoteItem );
        $quote = $this->_checkoutSession->getQuote();
        $date = $this->_date->gmtDate();
        $customPrice = $this->_collectionFactory->create();
        $customPrice->addFieldToFilter('customer_id',$quote->getCustomerId())
            ->addFieldToFilter('product_id', $quoteItem->getProductId());
        foreach ($customPrice as $item) {
            if ($date >= $item['start_date'] && $date <= $item['end_date']) {
                $quoteItem->setCustomPrice($item['price']);
                $quoteItem->setOriginalCustomPrice($item['price']);
            }
        }
        $quoteItem->save();
    }

}
