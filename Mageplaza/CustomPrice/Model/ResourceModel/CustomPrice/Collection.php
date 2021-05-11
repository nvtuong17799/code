<?php
namespace Mageplaza\CustomPrice\Model\ResourceModel\CustomPrice;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(\Mageplaza\CustomPrice\Model\CustomPrice::class, \Mageplaza\CustomPrice\Model\ResourceModel\CustomPrice::class);
    }
}
