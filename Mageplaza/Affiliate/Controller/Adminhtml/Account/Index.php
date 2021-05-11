<?php

namespace Mageplaza\Affiliate\Controller\Adminhtml\Account;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory = false;

    public function __construct(Context $context, PageFactory $pageFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $pageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Affiliate Account')));
        return $resultPage;

    }

}
