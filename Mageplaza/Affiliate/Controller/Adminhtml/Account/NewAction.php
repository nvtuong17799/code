<?php
namespace Mageplaza\Affiliate\Controller\Adminhtml\Account;

class NewAction extends \Magento\Framework\App\Action\Action
{
    protected $_resultForwardFactory = false;

    public function __construct
    (
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $resultPage = $this->_resultForwardFactory->create();
        $resultPage->forward('edit');
        return $resultPage;
    }

}
