<?php
namespace Mageplaza\Affiliate\Model\Total\Quote;

class CalDiscount extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    protected $_priceCurrency;
    protected $_checkoutSession;
    protected $cookieManager;
    protected $accountFactory;
    protected $helperData;

    public function __construct(
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Mageplaza\Affiliate\Model\AccountFactory $accountFactory,
        \Mageplaza\Affiliate\Helper\Data $helperData
    ){
        $this->_checkoutSession = $checkoutSession;
        $this->_priceCurrency = $priceCurrency;
        $this->cookieManager = $cookieManager;
        $this->accountFactory = $accountFactory;
        $this->helperData = $helperData;
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);
        $typeDiscount = $this->helperData->getRuleConfig('enable_discount');
        $discountValue = $this->helperData->getRuleConfig('discount_value');
        $typeCommission = $this->helperData->getRuleConfig('commission_type');
        $commissionValue = $this->helperData->getRuleConfig('commission_value');

        $account = $this->accountFactory->create();
        $account->load($this->_checkoutSession->getAffiliateValue(),'code');

        $grandTotal = $total->getGrandTotal();

        if($typeDiscount=='no'){
            $baseDiscount = null;
        }
        if ($typeDiscount == 'fix_value'){
            $baseDiscount = $discountValue;
        }
        if ($typeDiscount == 'percentage'){
            $baseDiscount = $grandTotal*($discountValue/100);
        }

        $cookie = $this->getCookie(\Mageplaza\Affiliate\Controller\Refer\Index::COOKIE_NAME);
        $enableAffiliate = $this->helperData->getGeneralConfig('enable_affiliate');
        $discount =  $this->_priceCurrency->convert($baseDiscount);
        if(($cookie && $enableAffiliate==1) || $account->getId()){
            if($grandTotal != 0){
                $total->setBaseGrandTotal($total->getBaseGrandTotal() - $baseDiscount);
                $total->setGrandTotal($total->getGrandTotal() - $discount);

                $quote->setAffiliateDiscount(-$discount);
                $quote->setAffiliateBaseDiscount(-$baseDiscount);

                $newGrandTotal = $total->getGrandTotal();
                if($typeCommission == 'fix_value'){
                    $quote->setAffiliateCommission($commissionValue);
                }
                if($typeCommission == 'percentage'){
                    $quote->setAffiliateCommission(($commissionValue/100)*$newGrandTotal);
                }
            }
        }else{
            $quote->setAffiliateDiscount(null);
            $quote->setAffiliateBaseDiscount(null);
            $quote->setAffiliateCommission(null);
        }

        return $this;
    }


    public function fetch(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        return [
            'code' => $this->getCode(),
            'title' => $this->getLabel(),
            'value' => $quote->getAffiliateBaseDiscount(),
        ];
    }

    public function getLabel()
    {
        return __('Discount');
    }

    public function getCookie($name)
    {
        return $this->cookieManager->getCookie($name);
    }
}
