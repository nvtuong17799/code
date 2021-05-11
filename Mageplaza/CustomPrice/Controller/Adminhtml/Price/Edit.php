<?php
namespace Mageplaza\CustomPrice\Controller\Adminhtml\Price;

use Mageplaza\CustomPrice\Model\CustomPrice;
use Mageplaza\CustomPrice\Model\CustomPriceFactory;

class Edit extends \Magento\Backend\App\Action
{

    protected $_resultPageFactory;
    protected $_coreRegistry;
    protected $_customPriceFactory;

    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        CustomPriceFactory $customPriceFactory
    )
    {
        $this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->_customPriceFactory = $customPriceFactory;
    }


    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $id = $this->getRequest()->getParam('id');
        $customPrice = $this->_customPriceFactory->create();
        $customPrice->load($id);
        $this->_coreRegistry->register('customprice_data',$customPrice);
        if(!empty($id)){
            $resultPage->getConfig()->getTitle()->prepend((__("Edit #%1", $id)));
        }else{
            $resultPage->getConfig()->getTitle()->prepend((__('New Rule')));
        }

        return $resultPage;
    }
}
