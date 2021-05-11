<?php
namespace Mageplaza\CustomPrice\Controller\Adminhtml\Price;

use Magento\Setup\Exception;

class Delete extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;

    protected $customPriceFactory;

    protected $_messageManager;

    public function __construct
    (
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Mageplaza\CustomPrice\Model\CustomPriceFactory $customPriceFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_messageManager = $messageManager;
        $this->customPriceFactory = $customPriceFactory;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $customPrice = $this->customPriceFactory->create();
        try{
            $customPrice->load($id);
            if($customPrice->getData('entity_id')){
                $customPrice->delete(); //Xóa
                $message = __('Rule #%1 have been deleted.', $customPrice->getData('entity_id'));
                $this->_messageManager->addSuccess($message);
            }else{
                $message = 'Rule does not exists!';
                $this->_messageManager->addError($message);
            }
        }catch (Exception $e){
            $message = 'Error: '.$e;
            $this->_messageManager->addError($message);
        }
        $resultPage = $this->_resultPageFactory->create();
        return $this->_redirect('*/*/', [$resultPage]);
    }

}
