<?php
namespace Mageplaza\CustomPrice\Model\ResourceModel;

class CustomPrice extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('mageplaza_customprice', 'entity_id');
    }

}
