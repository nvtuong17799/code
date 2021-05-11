<?php
namespace Mageplaza\Affiliate\Block\frontend\Dashboard;

class Account extends \Magento\Framework\View\Element\Template
{
    protected $_customerSession;
    protected $_accountFactory;
    protected $_priceCurrency;
    protected $_helperData;


    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Mageplaza\Affiliate\Model\AccountFactory $accountFactory,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Mageplaza\Affiliate\Helper\Data $helperData,
        array $data = []
    )
    {
        $this->_helperData = $helperData;
        $this->_priceCurrency = $priceCurrency;
        $this->_accountFactory = $accountFactory;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function getAccount(){
        $account = $this->_accountFactory->create();
        $account->load($this->_customerSession->getId(),'customer_id');
        return $account;
    }
    public function getCurrencyWithFormat($price)
    {
        return $this->_priceCurrency->format($price,false,2);
    }

    public function getLink()
    {
        $account = $this->getAccount();
        $key = $this->_helperData->getGeneralConfig('url_key');
        $value = $account->getCode();
        return $this->getUrl('affiliate/refer/index/', [$key => $value]);
    }

}
