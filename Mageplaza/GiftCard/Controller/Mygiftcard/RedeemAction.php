<?php

namespace Mageplaza\GiftCard\Controller\Mygiftcard;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Mageplaza\GiftCard\Model\GiftCardFactory;

class RedeemAction extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected $_customerSession;
    protected $_helperData;
    protected $_giftCardFactory;
    protected $_historyFactory;
    protected $_customerFactory;
    protected $_messageManager;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Mageplaza\GiftCard\Model\HistoryFactory $historyFactory,
        \Mageplaza\GiftCard\Model\CustomerFactory $customerFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->_messageManager = $messageManager;
        $this->_customerFactory = $customerFactory;
        $this->_historyFactory = $historyFactory;
        $this->_giftCardFactory = $giftCardFactory;
        $this->_customerSession = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $valuePost = $this->getRequest()->getPostValue();
        $redeemValue = $valuePost['redeem'];
        $this->addBalance($redeemValue);

        return $this->_redirect('*/*/index');
    }

    public function addBalance($value)
    {
        $cus = $this->_customerFactory->create();
        $cus->load($this->getCustomerSession()->getId());
        $history = $this->_historyFactory->create();
        $balanceCus = $cus->getData('balance');
        $gc = $this->_giftCardFactory->create();
        $collection = $gc->getCollection()->addFieldToFilter('code', ['eq' => "$value"]);
        if(!$collection->getData()){
            $message = "Gift Card is not invalid";
            $this->_messageManager->addError($message);
        }else{
            foreach ($collection as $item) {
                if ($item['balance'] > $item['amount_used']) {
                    $item['balance'] -= $item['amount_used'];
                    $balanceCus += $item['balance'];
                    $history->addData([
                        'giftcard_id' => $item['giftcard_id'],
                        'customer_id' => $this->getCustomerSession()->getId(),
                        'amount' => $item['balance'],
                        'action' => 'Redeem'
                    ])->save();
                    $gc->load($item['giftcard_id']);
                    $item['balance'] += $item['amount_used'];
                    $gc->setData('amount_used', $item['balance'])->save();
                    $message = "Gift Card used successfully!";
                    $this->_messageManager->addSuccess($message);
                } else {
                    $message = "Gift Card was used";
                    $this->_messageManager->addError($message);
                }
            }
            $cus->setData('balance', $balanceCus)->save();
        }
    }

    public function getCustomerSession()
    {
        return $this->_customerSession;
    }
}
