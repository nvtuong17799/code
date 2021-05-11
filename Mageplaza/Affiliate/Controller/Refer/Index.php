<?php

namespace Mageplaza\Affiliate\Controller\Refer;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class Index extends Action
{
    const COOKIE_NAME = 'Affiliate_Cookie';
    const COOKIE_DURATION = 365*24*60*60; // seconds


    protected $accountFactory;
    protected $cookieManager;
    protected $cookieMetadataFactory;
    protected $_sessionManager;
    protected $customerSession;
    protected $helperData;

    public function __construct(
        Context $context,
        \Mageplaza\Affiliate\Model\AccountFactory $accountFactory,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\Session\SessionManagerInterface $sessionManager,
        \Magento\Customer\Model\Session $customerSession,
        \Mageplaza\Affiliate\Helper\Data $helperData
    )
    {
        parent::__construct($context);
        $this->accountFactory = $accountFactory;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->_sessionManager = $sessionManager;
        $this->customerSession = $customerSession;
        $this->helperData = $helperData;
    }

    public function execute()
    {
        $affiliateCode = $this->_request->getParam('key');
        $account = $this->accountFactory->create();
        $account->load($this->customerSession->getCustomerId(), 'customer_id');
        if($this->helperData->getGeneralConfig('enable_affiliate')==1 && $affiliateCode!==$account->getCode()){
            $metadata = $this->cookieMetadataFactory
                ->createPublicCookieMetadata()
                ->setPath($this->_sessionManager->getCookiePath())
                ->setDuration(self::COOKIE_DURATION);
            $this->cookieManager
                ->setPublicCookie(self::COOKIE_NAME, $affiliateCode, $metadata);
        }

        return $this->_redirect($this->_sessionManager->getCookiePath());
    }

}
