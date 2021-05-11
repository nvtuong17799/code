<?php
namespace Mageplaza\CustomPrice\Model;
class CustomPrice extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Mageplaza\CustomPrice\Model\ResourceModel\CustomPrice::class);
    }
}
