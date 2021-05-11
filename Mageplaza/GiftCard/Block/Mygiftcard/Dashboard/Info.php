<?php
namespace Mageplaza\GiftCard\Block\Mygiftcard\Dashboard;

class Info extends \Magento\Framework\View\Element\Template
{
    protected $_customerSession;
    protected $_customerFactory;
    protected $_priceCurrency;
    protected $_helperData;


    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Mageplaza\GiftCard\Model\CustomerFactory $customerFactory,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Mageplaza\GiftCard\Helper\Data $helperData,
        array $data = []
    )
    {
        $this->_helperData = $helperData;
        $this->_priceCurrency = $priceCurrency;
        $this->_customerFactory = $customerFactory;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function getBalance(){
        $cus = $this->_customerSession->create();
        $customer = $this->_customerFactory->create();
        $customer->load($cus->getCustomer()->getId());
        return $customer->getData('balance');
    }
    public function getCurrencyWithFormat($price)
    {
        return $this->_priceCurrency->format($price,false,2);
    }

    public function getRedeem(){
        return $this->_helperData->getGeneralConfig('enable_redeem');
    }

    public function getFormAction()
    {
        return $this->getBaseUrl().'giftcard/mygiftcard/redeemaction';
    }

}
