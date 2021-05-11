<?php
namespace Mageplaza\Affiliate\Model\ResourceModel;

class History extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('affiliate_history', 'history_id');
    }

}
