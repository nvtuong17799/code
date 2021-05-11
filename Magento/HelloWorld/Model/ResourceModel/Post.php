<?php
namespace Magento\HelloWorld\Model\ResourceModel;

class Post extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('magento_helloworld_post', 'post_id');
    }

}
