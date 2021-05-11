<?php
namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;

class Edit extends \Magento\Backend\App\Action
{

    protected $_resultPageFactory;
    protected $_coreRegistry;
    protected $_giftCardFactory;

    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Mageplaza\GiftCard\Model\GiftCardFactory $giftCardFactory,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    )
    {
        $this->_giftCardFactory = $giftCardFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }


    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $giftCard = $this->_giftCardFactory->create();
        $giftCard->load($id);
        $this->_coreRegistry->register('giftcard_form_data',$giftCard);
        $resultPage = $this->_resultPageFactory->create();
        if(!empty($id)){
            $resultPage->getConfig()->getTitle()->prepend((__("Gift Card %1", $giftCard->getCode())));
        }else{
            $resultPage->getConfig()->getTitle()->prepend((__('New Gift Card')));
        }

        return $resultPage;
    }
}
