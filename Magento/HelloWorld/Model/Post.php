<?php
namespace Magento\HelloWorld\Model;
class Post extends \Magento\Framework\Model\AbstractModel
{
    protected $_test;
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [],
        $test = null
    )
    {
        $this->_test = $test;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init(\Magento\HelloWorld\Model\ResourceModel\Post::class);
    }

    public function getTest()
    {
        return $this->_test;
    }
}
