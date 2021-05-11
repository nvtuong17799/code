<?php
namespace Magento\HelloWorld\Controller\Model;

use Magento\Framework\App\Action\Context;

class Curd extends \Magento\Framework\App\Action\Action
{
    protected $_postFactory;

    public function __construct(
        Context $context,
        \Magento\HelloWorld\Model\PostFactory $postFactory
    )
    {
        $this->_postFactory = $postFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $post = $this->_postFactory->create();

        $data = [
            'name'         => "Post name",
            'post_content' => "In this article, we will find out how to install and upgrade sql script for module in Magento 2. When you install or upgrade a module, you may need to change the database structure or add some new data for current table. To do this, Magento 2 provide you some classes which you can do all of them.",
            'url_key'      => '/magento-2-module-development/magento-2-how-to-create-sql-setup-script.html',
            'tags'         => 'magento 2, mageplaza helloworld',
            'status'       => 1
        ];

        try{
           $post->load(2);
           $a = $post->save(); //Thêm mới dữ liệu
            echo get_class($this->_postFactory);
            if($post->getData('post_id')){
//               $post->delete(); //Xóa
//               $post->setData('name','tuong')->save(); //Sửa
//               $post->setName('aaaa')->save(); //Sửa bằng Magic Method
//               echo $post->getName(); //in ra tên đã set

                echo "Success";
            }else{
                echo "Test id does not exist";
            }

        }catch (\Exception $e){
            echo "Error!";
        }


    }
}
