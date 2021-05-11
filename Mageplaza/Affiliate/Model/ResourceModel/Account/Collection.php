<?php
namespace Mageplaza\Affiliate\Model\ResourceModel\Account;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'account_id';

    protected function _construct()
    {
        $this->_init(\Mageplaza\Affiliate\Model\Account::class, \Mageplaza\Affiliate\Model\ResourceModel\Account::class);
    }
}
