<?php
namespace Mageplaza\CustomPrice\Controller\Adminhtml\Price;

class Save extends \Magento\Backend\App\Action
{

    protected $_resultPageFactory;
    protected $customPriceFactory;
    protected $_messageManager;
    protected $productFactory;

    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\App\Action\Context $context,
        \Mageplaza\CustomPrice\Model\CustomPriceFactory $customPriceFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        $this->productFactory = $productFactory;
        $this->_messageManager = $messageManager;
        $this->customPriceFactory = $customPriceFactory;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $count = 0;
        $resultPage = $this->_resultPageFactory->create();
        $valuePost = $this->getRequest()->getPostValue();
        $customPrice = $this->customPriceFactory->create();
        $product = $this->productFactory->create();
        $product->load($product->getIdBySku($valuePost['product_sku']));
        $simplePrice = $product->getPriceInfo()->getPrice('regular_price')->getValue();

        if($valuePost['start_date']>=$valuePost['end_date']){
            $message = "Set time incorrect!";
            $this->_messageManager->addError($message);
        }
        else{
            $customPriceChecker = $customPrice->getCollection()
                ->addFieldToFilter('product_id',['eq'=>$product->getId()])
                ->addFieldToFilter('customer_id',['eq'=>$valuePost['customer_id']]);
            foreach ($customPriceChecker as $item) {
                if (($valuePost['end_date'] >= $item['start_date']
                    &&  $valuePost['start_date'] <= $item['end_date'])
                ) {
                    $count++;
                }
            }
            if(!empty($valuePost['customprice_id'])){
                $id = $valuePost['customprice_id'];
                $customPrice->load($id);

                if($customPrice->getData('entity_id')){
                    if($valuePost['price']>$simplePrice){
                        $message = "Specific price is greater than the current product price, Please enter agian!";
                        $this->_messageManager->addError($message);
                    }else{
                        if($count > 1){
                            $message = __("Time for Rule already exist");
                            $this->_messageManager->addError($message);
                        }else{
                            foreach($valuePost as $key=>$value){
                                $customPrice->setData($key,$value)->save();
                            }
                            $message = __("Edit Rule #%1 Success", $customPrice->getData('entity_id'));
                            $this->_messageManager->addSuccessMessage($message);
                        }
                    }
                    if ($this->getRequest()->getParam('back')) {
                        return $this->_redirect('*/*/edit', ['id'=>$customPrice->getId()]);
                    }
                }else{
                    $message = "Rule does not exists!";
                    $this->_messageManager->addErrorMessage($message);
                }
            }else{
                if($valuePost['price']>$simplePrice){
                    $message = "Specific price is greater than the current product price, Please enter agian!";
                    $this->_messageManager->addError($message);
                    return $this->_redirect('*/*/new');
                }
                else{
                    if ($count != 0){
                        $message = __("Time for Rule already exist, Please Edit!");
                        $this->_messageManager->addError($message);
                    }
                    else{
                        $customPrice->addData([
                            'customer_id'=>$valuePost['customer_id'],
                            'product_id'=>$product->getData('entity_id'),
                            'price' => $valuePost['price'],
                            'start_date'=>$valuePost['start_date'],
                            'end_date'=>$valuePost['end_date']
                        ]);
                        $customPrice->save();
                        $message = 'You add Rule Success';
                        $this->_messageManager->addSuccessMessage($message);
                    }

                    if ($this->getRequest()->getParam('back')) {
                        return $this->_redirect('*/*/edit', ['id'=>$customPrice->getId()]);
                    }
                }
            }
        }
        return $this->_redirect('*/*/', [$resultPage]);
    }
}
