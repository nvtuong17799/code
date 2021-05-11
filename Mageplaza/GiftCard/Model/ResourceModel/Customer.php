<?php
namespace Mageplaza\GiftCard\Model\ResourceModel;

class Customer extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('giftcard_customer_balance', 'customer_id');
    }

}
