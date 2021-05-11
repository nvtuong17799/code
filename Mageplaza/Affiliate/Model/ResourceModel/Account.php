<?php
namespace Mageplaza\Affiliate\Model\ResourceModel;

class Account extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('affiliate_account', 'account_id');
    }

}
