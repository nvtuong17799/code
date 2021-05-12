<?php
namespace Mageplaza\Affiliate\Plugin\frontend;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;

class ApplyAffiliateCode{
    protected $_request;
    protected $_resultFactory;
    protected $_messageManager;
    protected $_checkoutSession;
    protected $accountFactory;
    protected $cookieManager;
    protected $_helperData;

    public function __construct(
        RequestInterface $request,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        ManagerInterface $messageManager,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Mageplaza\Affiliate\Model\AccountFactory $accountFactory,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Mageplaza\Affiliate\Helper\Data $helperData
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_messageManager = $messageManager;
        $this->_resultFactory = $resultFactory;
        $this->_request = $request;
        $this->accountFactory = $accountFactory;
        $this->cookieManager = $cookieManager;
        $this->_helperData = $helperData;
    }

    public function aroundExecute(\Magento\Checkout\Controller\Cart\CouponPost $subject, callable $proceed)
    {
        $redirect = $this->_resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        if($this->_helperData->getGeneralConfig('enable_affiliate') == 1){
            $affiliateCode = $this->_request->getParam('remove') == 1
                ? ''
                : trim($this->_request->getParam('coupon_code'));

            $cookie = $this->cookieManager->getCookie(\Mageplaza\Affiliate\Controller\Refer\Index::COOKIE_NAME);

            $cartQuote = $this->_checkoutSession->getQuote();
            $account = $this->accountFactory->create();
            $account->load($affiliateCode, 'code');
            $codeLength = strlen($affiliateCode);
            if($codeLength){
                if($account->getId()){
                    if($cookie){
                        $message = __('You can\'t use affiliate code, because You was entered link refer with code "%1" and cookie already exists!', $cookie);
                        $this->_messageManager->addError($message);
                    }else{
                        $this->_messageManager->addSuccess(__(
                            'You used affiliate code "%1".',
                            $affiliateCode
                        ));
                        $this->_checkoutSession->setAffiliateValue($account->getCode());
                        $cartQuote->collectTotals();
                        $cartQuote->save();
                    }
                    $result = $redirect->setPath('checkout/cart/');
                }else{
                    $result = $proceed();
                }
            }else{
                if($this->_checkoutSession->getAffiliateValue()){
                    $this->_checkoutSession->setAffiliateValue(NULL);
                    $cartQuote->collectTotals();
                    $cartQuote->save();
                    $this->_messageManager->addSuccess('You canceled the affiliate code.');
                    $result = $redirect->setPath('checkout/cart/');
                }else{
                    $result = $proceed();
                }
            }
            return $result;
        }else{
            $this->_messageManager->addError('Affiliate function is disabled by Admin. Contact us for enable!');
            return $redirect->setPath('checkout/cart/');
        }
    }

    public function afterGetCouponCode(\Magento\Checkout\Block\Cart\Coupon $subject, $result){
        if($this->_checkoutSession->getAffiliateValue())
        {
            $result = $this->_checkoutSession->getAffiliateValue();
        }
        return $result;
    }
}
