<?php
namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;

use Magento\Setup\Exception;
use Mageplaza\GiftCard\Helper\Data;

class Save extends \Magento\Backend\App\Action
{

    protected $_resultPageFactory;

    protected $_giftCardFactory;

    protected $_messageManager;

    protected $_helperData;

    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\App\Action\Context $context,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Mageplaza\GiftCard\Helper\Data $helperData,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->_helperData = $helperData;
        $this->_messageManager = $messageManager;
        $this->_giftCardFactory = $giftCardFactory;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }


    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $gc = $this->_giftCardFactory->create();
        $valuePost = $this->getRequest()->getPostValue();
        if(!empty($valuePost['giftcard_id'])){
            $id = $valuePost['giftcard_id'];
            try{
                $gc->load($id);
                if($gc->getData('giftcard_id')){
                    foreach($valuePost as $key=>$value){
                        $gc->setData($key,$value)->save();
                    }
                    $message = __("Edit Gift Card %1 Success", $gc->getCode());
                    $this->_messageManager->addSuccessMessage($message);
                    if ($this->getRequest()->getParam('back')) {
                        return $this->_redirect('*/*/edit', ['id'=>$gc->getId()]);
                    }
                }else{
                    $message = "GiftCard does not exists!";
                    $this->_messageManager->addErrorMessage($message);
                }
            }catch (\Exception $e){
                $message = 'Error!';
                $this->_messageManager->addErrorMessage($message);
            }
        }else{
            try {
                $valuePost['code'] = $this->randomString($valuePost['code_length'], $this->_helperData->getCustomChars());
                $gc->addData($valuePost)->save();
                $message = 'You add Gift Card Success';
                $this->_messageManager->addSuccessMessage($message);
                if ($this->getRequest()->getParam('back')) {
                    return $this->_redirect('*/*/edit', ['id'=>$gc->getId()]);
                }
            }catch (\Exception $e){
                $message = 'Error '.$e->getMessage();
                $this->_messageManager->addErrorMessage($message);
            }
        }
        return $this->_redirect('*/*/', [$resultPage]);
    }

    function randomString($length, $chars ) {
        $str = '';
        for($i = 0; $i < $length; $i++) {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }
}
