<?php
namespace Mageplaza\Affiliate\Controller\Adminhtml\Account;
use Mageplaza\Affiliate\Model\AccountFactory;

class Edit extends \Magento\Backend\App\Action
{

    protected $_resultPageFactory;
    protected $_coreRegistry;
    protected $_accountFactory;

    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        AccountFactory $accountFactory
    )
    {
        $this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->_accountFactory = $accountFactory;
    }


    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $id = $this->getRequest()->getParam('id');
        $account = $this->_accountFactory->create();
        $account->load($id);
        $this->_coreRegistry->register('account_data',$account);
        if(!empty($id)){
            $resultPage->getConfig()->getTitle()->prepend((__("Edit #%1", $id)));
        }else{
            $resultPage->getConfig()->getTitle()->prepend((__('New Affiliate Account')));
        }
        return $resultPage;
    }
}
