<?php

namespace Mageplaza\Affiliate\Controller\Account;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Mageplaza\Affiliate\Helper\Data;

;

class Index extends Action
{
    protected $resultPageFactory = false;
    protected $customerSession;
    protected $helperData;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        Session $session,
        Data $helperData
    )
    {
        $this->resultPageFactory = $pageFactory;
        parent::__construct($context);
        $this->customerSession = $session;
        $this->helperData = $helperData;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Affiliate Account'));
        if (!$this->customerSession->isLoggedIn()) {
            return $this->_redirect('customer/account/login');
        }
        if($this->helperData->getGeneralConfig('enable_affiliate') == 0){
            return $this->_redirect('customer/account/');
        }
        return $resultPage;
    }

}
