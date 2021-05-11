<?php
namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;

class Delete extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;

    protected $_giftCardFactory;

    protected $_messageManager;

    public function __construct
    (
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_messageManager = $messageManager;
        $this->_giftCardFactory = $giftCardFactory;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $gc = $this->_giftCardFactory->create();
        try{
            $gc->load($id);
            if($gc->getData('giftcard_id')){
                $gc->delete(); //Xóa
                $message = 'Gift Card  id: '.$id.' have been deleted.';
                $this->_messageManager->addSuccess($message);
            }else{
                $message = 'GiftCard does not exists!';
                $this->_messageManager->addError($message);
            }
        }catch (Exception $e){
            $message = 'Error!';
            $this->_messageManager->addError($message);
        }
        $resultPage = $this->_resultPageFactory->create();
        return $this->_redirect('*/*/', [$resultPage]);
    }

}
