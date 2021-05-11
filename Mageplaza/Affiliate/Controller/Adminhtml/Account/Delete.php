<?php
namespace Mageplaza\Affiliate\Controller\Adminhtml\Account;

class Delete extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;

    protected $_accountFactory;

    protected $_messageManager;

    public function __construct
    (
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Mageplaza\Affiliate\Model\AccountFactory $accountFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_messageManager = $messageManager;
        $this->_accountFactory = $accountFactory;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $account = $this->_accountFactory->create();
        try{
            $account->load($id);
            if($account->getId()){
                $account->delete(); //Xóa
                $message = 'Account '.$account->getCode().' have been deleted.';
                $this->_messageManager->addSuccessMessage($message);
            }else{
                $message = 'Account does not exists!';
                $this->_messageManager->addErrorMessage($message);
            }
        }catch (\Exception $e){
            $message = 'Error: '.$e;
            $this->_messageManager->addErrorMessage($message);
        }
        $resultPage = $this->_resultPageFactory->create();
        return $this->_redirect('*/*/', [$resultPage]);
    }

}
