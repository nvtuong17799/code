<?php
namespace Magento\HelloWorld\Model\ResourceModel\Post;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'post_id';

    protected function _construct()
    {
        $this->_init(\Magento\HelloWorld\Model\Post::class, \Magento\HelloWorld\Model\ResourceModel\Post::class);
    }
}
