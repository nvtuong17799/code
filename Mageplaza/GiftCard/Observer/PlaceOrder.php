<?php
namespace Mageplaza\GiftCard\Observer;
use Mageplaza\GiftCard\Helper;

class PlaceOrder implements \Magento\Framework\Event\ObserverInterface
{

    protected $_helperData;
    protected $_helperEmail;
    protected $_giftCardFactory;
    protected $_historyFactory;
    protected $_productFactory;


    public function __construct(
        Helper\Data $helperData,
        Helper\Email $helperEmail,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Mageplaza\GiftCard\Model\HistoryFactory $historyFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        $this->_giftCardFactory = $giftCardFactory;
        $this->_historyFactory = $historyFactory;
        $this->_productFactory = $productFactory;
        $this->_helperData = $helperData;
        $this->_helperEmail = $helperEmail;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getData('order');
        $items = $order->getAllVisibleItems();

        $chars = $this->_helperData->getCustomChars();

        foreach ($items as $item){
            $product = $this->_productFactory->create();
            $id = $item->getProductId();
            $product->load($id);
            if($product->getTypeId()=="virtual"){
                $giftCardAmount =  $product->getData('giftcard_amount');
                if(!empty($giftCardAmount) || $giftCardAmount == 0){
                    for ($i=0; $i<$item->getQtyOrdered(); $i++){
                        $data = [
                            'code'=> $this->randomString($this->getCodeLength(), $chars),
                            'balance'=> $giftCardAmount,
                            'created_from' => '#'.$order->getData('increment_id')
                        ];
                        $giftCard = $this->_giftCardFactory->create();
                        $giftCard->addData($data)->save();
                        $codes[] = $giftCard->getData('code');;
                        $history = $this->_historyFactory->create();
                        $history->addData([
                            'giftcard_id' => $giftCard->getData('giftcard_id'),
                            'customer_id' => $order->getData('customer_id'),
                            'amount' => $giftCard->getData('balance'),
                            'action' => 'Created from #'.$order->getData('increment_id')
                        ])->save();
                    }
                }
            }
        }
//        $this->_helperEmail->sendEmail($codes, $order->getData('customer_email'));
    }


    public function getCodeLength(){
        return $this->_helperData->getCodeConfig('length');
    }

    function randomString($length, $chars) {
        $str = '';
        for($i = 0; $i < $length; $i++) {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }
}
