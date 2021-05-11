<?php
namespace Mageplaza\GiftCard\Model\Total\Quote;

class Custom extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    protected $_priceCurrency;

    protected $_giftCardFactory;

    protected $_checkoutSession;

    public function __construct(
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Magento\Checkout\Model\Session $checkoutSession
    ){
        $this->_checkoutSession = $checkoutSession;
        $this->_giftCardFactory = $giftCardFactory;
        $this->_priceCurrency = $priceCurrency;
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);
        $giftCard = $this->_giftCardFactory->create();
        $giftCard->load($this->_checkoutSession->getValue(),'code');
        $grandTotal =  $total->getData('grand_total');
        if($giftCard->getData('giftcard_id')){

            if($giftCard->getData('amount_used') != NULL){
                $baseDiscount = $giftCard->getData('balance') - $giftCard->getData('amount_used');
            }else{
                $baseDiscount = $giftCard->getData('balance');
            }

            if ($grandTotal != 0 && $grandTotal <= $baseDiscount) {
                $discount = $this->_priceCurrency->convert($grandTotal);
                $giftCard->setAmountUsed($discount);
                $quote->setGiftcardDiscount(-$discount);
                $total->setBaseGrandTotal($total->getBaseGrandTotal() - $baseDiscount);
                $total->setGrandTotal($grandTotal - $discount);

            }
            if ($grandTotal != 0 && $grandTotal > $baseDiscount) {
                $discount = $this->_priceCurrency->convert($baseDiscount);
                $giftCard->setAmountUsed($discount);
                $quote->setGiftcardDiscount(-$discount);
                $total->setBaseGrandTotal($total->getBaseGrandTotal() - $baseDiscount);
                $total->setGrandTotal($grandTotal - $discount);
            }
        }
        else{
            $quote->setGiftcardDiscount(NULL);
            $quote->setGiftcardBaseDiscount(NULL);
            $quote->setGiftcardCode(NULL);
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
            'value' => $quote->getGiftcardDiscount(),
        ];
    }

    public function getLabel()
    {
        return __('Gift Card');
    }
}
