<?php
namespace Mageplaza\GiftCard\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Setup\Exception;

class Multiaction extends \Magento\Framework\App\Action\Action
{
    protected $_giftCardFactory;

    public function __construct(
        Context $context,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory
    )
    {
        $this->_giftCardFactory = $giftCardFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getParams();

        if(!empty($data)){
            if($data['action'] == 'getlist'){
                $this->getList();
            }
            if($data['action'] == 'add'){
                $this->addGiftCard($data);
            }
            if($data['action'] == 'edit'){

                $this->editGiftCard($data);
            }
            if($data['action'] == 'del'){
                $this->deleteGiftCard($data['id']);
            }
        }
        else{
            $this->getList();
        }
    }

    public function getList()
    {
        $giftCard = $this->_giftCardFactory->create();
        //Thao tác với collection
        $collection = $giftCard->getCollection();
        echo "<pre>";
        print_r($collection->getData());
        echo "</pre>";
    }

    public function addGiftCard($data){
        $giftCard = $this->_giftCardFactory->create();
        try{
            $giftCard->addData($data)->save(); //Thêm mới dữ liệu
            echo "Add GiftCard Success";

        }catch (\Exception $e){
            echo "Error: ".$e;
        }

    }
    public function editGiftCard($data){
        $giftCard = $this->_giftCardFactory->create();
        try{
            $giftCard->load($data['id']);
            if($giftCard->getData('giftcard_id')){
                foreach ($data as $key=>$value){
                    $giftCard->setData($key, $value);
                }
                $giftCard->save();
                echo "Edit Success With id =  ".$data['id'];
            }else{
                echo 'GiftCard does not exists!';
            }
        }catch (Exception $e){
            echo "Error: ".$e;
        }
    }
    public function deleteGiftCard($id){
        $giftCard = $this->_giftCardFactory->create();
        try{
            $giftCard->load($id);
            if($giftCard->getData('giftcard_id')){
                $giftCard->delete(); //Xóa
                echo "Delete Successfully";
            }else{
                echo 'GiftCard does not exists!';
            }
        }catch (Exception $e){
            echo "Error: ".$e;
        }
    }
}
