<?php
namespace Mageplaza\Affiliate\Model;
class Account extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Mageplaza\Affiliate\Model\ResourceModel\Account::class);
    }
}
