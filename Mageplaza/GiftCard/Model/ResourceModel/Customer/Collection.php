<?php
namespace Mageplaza\GiftCard\Model\ResourceModel\Customer;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'customer_id';

    protected function _construct()
    {
        $this->_init(\Mageplaza\GiftCard\Model\Customer::class, \Mageplaza\GiftCard\Model\ResourceModel\Customer::class);
    }
}
