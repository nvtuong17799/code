<?php
namespace Magento\HelloWorld\Controller\Index;

use Magento\Framework\App\Action\Context;

class Testa extends \Magento\Framework\App\Action\Action
{
    protected $_test;

    public function __construct(
        Context $context,
        $test1 = 1
    )
    {
        $this->_test = $test1;
        parent::__construct($context);
    }

    public function execute()
    {
        echo $this->_test;
//        echo gettype($this->_test);
//        die(__METHOD__);
    }
}
