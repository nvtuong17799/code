<?php
namespace Mageplaza\Affiliate\Model\ResourceModel\History;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'history_id';

    protected function _construct()
    {
        $this->_init(\Mageplaza\Affiliate\Model\History::class, \Mageplaza\Affiliate\Model\ResourceModel\History::class);
    }
}
