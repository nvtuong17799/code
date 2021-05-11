<?php

namespace Mageplaza\GiftCard\Controller\Mygiftcard;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $_customerSession;
    protected $_helperData;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Mageplaza\GiftCard\Helper\Data $helperData
    ) {
        $this->_helperData = $helperData;
        $this->_customerSession = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Gift Card'));
        $id = $this->_customerSession->getCustomerId();
        if(empty($id))
        {
            return $this->_redirect('customer/account/login');
        }
        if($this->getEnableGC() == 0){
            return $this->_redirect('customer/account/');
        }

        return $resultPage;
    }

    public function getEnableGC(){
        return $this->_helperData->getGeneralConfig('enable_giftcard');
    }

}
