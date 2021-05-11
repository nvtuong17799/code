<?php
namespace Magento\HelloWorld\Controller\Model;

use Magento\Framework\App\Action\Context;

class GetList extends \Magento\Framework\App\Action\Action
{
    protected $_postFactory;

    public function __construct(
        Context $context,
        \Magento\HelloWorld\Model\PostFactory $postFactory,
        $test1 = 1
    )
    {
        $this->_postFactory = $postFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        // Khởi tạo đối tượng
        $post = $this->_postFactory->create();

        echo "<pre>";
        var_dump($post->getTest());

        // Load đối tượng
//        $a = $post->load(1, 'status');
//        // In đối tượng
//        echo "<pre>";
//        print_r($post->getData());
//        echo "</pre>";

//        //Thao tác với collection
//        $collection = $post->getCollection()->addFieldToFilter('status', 1);
//        echo get_class($collection);
//        echo $collection->getSelect()->__toString();
//        echo "<pre>";
//        print_r($collection->getData());
//        echo "</pre>";

    }
}
