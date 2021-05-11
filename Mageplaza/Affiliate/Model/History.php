<?php
namespace Mageplaza\Affiliate\Model;
class History extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Mageplaza\Affiliate\Model\ResourceModel\History::class);
    }
}
