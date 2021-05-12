<?php
namespace Mageplaza\Affiliate\Observer;

use Mageplaza\Affiliate\Model\AccountFactory;
use Mageplaza\Affiliate\Model\History;

class AfterPlaceOrder implements \Magento\Framework\Event\ObserverInterface
{

    protected $_helperData;
    protected $_helperEmail;
    protected $historyFactory;
    protected $accountFactory;
    protected $cookieManager;
    protected $cookieMetadataFactory;
    protected $sessionManager;
    protected $checkoutSession;

    public function __construct(
        \Mageplaza\Affiliate\Model\HistoryFactory $historyFactory,
        \Mageplaza\Affiliate\Model\AccountFactory $accountFactory,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\Session\SessionManagerInterface $sessionManager,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Mageplaza\Affiliate\Helper\Data $helperData,
        \Mageplaza\Affiliate\Helper\Email $helperEmail

    ) {
        $this->sessionManager =  $sessionManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->cookieManager = $cookieManager;
        $this->accountFactory = $accountFactory;
        $this->historyFactory = $historyFactory;
        $this->_helperData = $helperData;
        $this->_helperEmail = $helperEmail;
        $this->checkoutSession = $checkoutSession;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $quote = $observer->getEvent()->getData('quote');
        $order = $observer->getEvent()->getData('order');
        $order->setDiscountAffiliate($quote->getAffiliateDiscount());
        $order->setBaseDiscountAffiliate($quote->getAffiliateBaseDiscount());
        $order->setCommissionAffiliate($quote->getAffiliateCommission());
        $order->save();
        $code = '';
        $cookie = $this->cookieManager->getCookie(\Mageplaza\Affiliate\Controller\Refer\Index::COOKIE_NAME);
        if($cookie){
            $code = $cookie;
        }
        else if($this->checkoutSession->getAffiliateValue()){
            $code = $this->checkoutSession->getAffiliateValue();
        }
        $account = $this->accountFactory->create();
        $account->load($code, 'code');
        $account->setBalance($account->getBalance() + $quote->getAffiliateCommission())->save();
        $data = [
            'order_id' => $order->getId(),
            'order_increment_id' => $order->getIncrementId(),
            'customer_id' => $account->getCustomerId(),
            'is_admin_change' => 0,
            'amount' => $quote->getAffiliateCommission(),
            'status' => 1
        ];
        $history = $this->historyFactory->create();
        $history->addData($data)->save();

        if($history->getId()){
            $this->_helperEmail->sendEmail($history->getData('amount'), $account->getData('customer_id'));
        }
        $this->cookieManager->deleteCookie(
            \Mageplaza\Affiliate\Controller\Refer\Index::COOKIE_NAME,
            $this->cookieMetadataFactory->createPublicCookieMetadata()
            ->setPath($this->sessionManager->getCookiePath())
        );
        $this->checkoutSession->setAffiliateValue(null);
    }
}
