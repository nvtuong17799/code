<?php
namespace Mageplaza\GiftCard\Plugin\frontend;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;

class ApplyCode{
    protected $_giftCardFactory;
    protected $_request;
    protected $_resultFactory;
    protected $_messageManager;
    protected $_url;
    protected $_checkoutSession;
    protected $_helperData;

    public function __construct(
        RequestInterface $request,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        ManagerInterface $messageManager,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Mageplaza\GiftCard\Helper\Data $helperData
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_url = $url;
        $this->_messageManager = $messageManager;
        $this->_resultFactory = $resultFactory;
        $this->_request = $request;
        $this->_giftCardFactory = $giftCardFactory;
        $this->_helperData = $helperData;
    }

    public function aroundExecute(\Magento\Checkout\Controller\Cart\CouponPost $subject, callable $proceed)
    {
        $redirect = $this->_resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        if($this->_helperData->getGeneralConfig('enable_checkout') == 1){
            $couponCode = $this->_request->getParam('remove') == 1
                ? ''
                : trim($this->_request->getParam('coupon_code'));

            $cartQuote = $this->_checkoutSession->getQuote();

            $giftCard = $this->_giftCardFactory->create();
            $giftCard->load($couponCode, 'code');

            $codeLength = strlen($couponCode);
            $oldCouponCode = $cartQuote->getCouponCode();

            if($codeLength){
                if($giftCard->getData('amount_used') == NULL){
                    if($giftCard->getGiftcardId()){
                        $this->_messageManager->addSuccess(__(
                            'You used gift card code "%1".',
                            $couponCode
                        ));
                        $this->_checkoutSession->setValue($giftCard->getCode());
                        $cartQuote->collectTotals();
                        $cartQuote->save();
                        $result = $redirect->setPath('checkout/cart/');
                    }else{
                        $result = $proceed();
                    }
                }else{
                    $this->_messageManager->addError(__(
                        'Gift card %1 over value of use.',
                        $couponCode
                    ));
                    $result = $redirect->setPath('checkout/cart/');
                }
            }else{
                if(!strlen($oldCouponCode)){
                    $this->_checkoutSession->setValue(NULL);
                    $cartQuote->collectTotals();
                    $cartQuote->save();
                    $this->_messageManager->addSuccess('You canceled the gift card code.');
                    $result = $redirect->setPath('checkout/cart/');
                }else{
                    $result = $proceed();
                }
            }
            return $result;
        }else{
            $this->_messageManager->addError('Apply Gift Card function is disabled by Admin. Contact us for enable!');
            return $redirect->setPath('checkout/cart/');
        }
    }

    /**
     * @param \Magento\Checkout\Block\Cart\Coupon $subject
     * @param $result
     * @return mixed
     */
   public function afterGetCouponCode(\Magento\Checkout\Block\Cart\Coupon $subject, $result){
        if($this->_checkoutSession->getValue())
        {
            $result = $this->_checkoutSession->getValue();
        }
       return $result;
   }
}
