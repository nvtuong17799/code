<?php

namespace Mageplaza\Affiliate\Controller\Adminhtml\Account;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory = false;
    protected $accountFactory;
    protected $data;
    protected $messageManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Mageplaza\Affiliate\Model\AccountFactory $accountFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Mageplaza\Affiliate\Helper\Data $data
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $pageFactory;
        $this->accountFactory = $accountFactory;
        $this->messageManager = $messageManager;
        $this->data = $data;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $valuePost = $this->getRequest()->getPostValue();
        $account = $this->accountFactory->create();

        if(!empty($valuePost['account_id'])){
            $account->load($valuePost['account_id']);
            if($account->getId()){
                foreach($valuePost as $key=>$value){
                    $account->setData($key,$value)->save();
                }
                $message = __("Edit Account #%1 Success", $account->getCode());
                $this->messageManager->addSuccessMessage($message);
            }else{
                $message = __("Account do not exists");
                $this->messageManager->addErrorMessage($message);
            }
        }
        else{
            $account->load($valuePost['customer_id'],'customer_id');
            if($account->getId()){
                $message = 'Account already exists!';
                $this->messageManager->addErrorMessage($message);
            }
            else{
                $valuePost['code'] = $this->randomString($this->data->getGeneralConfig('code_length'), $this->data->getChars());
                $account->addData($valuePost)->save();
                $message = 'Create account Success';
                $this->messageManager->addSuccessMessage($message);
            }
        }
        if ($this->getRequest()->getParam('back')) {
            return $this->_redirect('*/*/edit', ['id'=>$account->getId()]);
        }
        return $this->_redirect('*/*/', [$resultPage]);

    }

    function randomString($length, $chars ) {
        $str = '';
        for($i = 0; $i < $length; $i++) {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }

}
