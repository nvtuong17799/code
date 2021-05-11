<?php
namespace Mageplaza\Affiliate\Controller\Account;

use Magento\Framework\App\Action\Action;

class NewAction extends Action
{

    protected $_resultPageFactory;
    protected $_coreRegistry;
    protected $_accountFactory;
    protected $_customerSession;
    protected $_helperData;

    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\App\Action\Context $context,
        \Mageplaza\Affiliate\Model\AccountFactory $accountFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Mageplaza\Affiliate\Helper\Data $helperData
    )
    {
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->_accountFactory = $accountFactory;
        $this->_customerSession = $customerSession;
        $this->_helperData = $helperData;
    }


    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $account = $this->_accountFactory->create();
        $account->load($this->_customerSession->getCustomerId(),'customer_id');
        if(!$account->getId()){
            $data = [
              'customer_id' => $this->_customerSession->getCustomerId(),
                'code'  => $this->randomString($this->_helperData->getGeneralConfig('code_length'), $this->_helperData->getChars()),
                'status' => 1
            ];
            $account->addData($data)->save();
            $message = __('You was created Account success!');
            $this->messageManager->addSuccessMessage($message);
        }
        else{
            $message = __('Account already exists');
            $this->messageManager->addErrorMessage($message);
        }
        return $this->_redirect('*/*/', [$resultPage]);
    }

    function randomString($length, $chars) {
        $str = '';
        for($i = 0; $i < $length; $i++) {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }
}
