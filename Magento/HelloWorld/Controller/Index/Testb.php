<?php
namespace Magento\HelloWorld\Controller\Index;

use Magento\Framework\App\Action\Context;

class Testb extends \Magento\Framework\App\Action\Action
{
    const TEST = 121923;

    protected $_test;

    public function __construct(
        Context $context,
        $test = null
    )
    {
        parent::__construct($context);
        $this->_test = $test;
    }

    public function execute()
    {
        echo gettype($this->_test);
        echo "<pre>";
        var_dump($this->_test);
        echo "<pre>";
        die();
    }
}
