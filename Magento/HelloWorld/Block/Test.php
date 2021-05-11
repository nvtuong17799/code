<?php
namespace Magento\HelloWorld\Block;

class Test extends \Magento\Framework\View\Element\Template
{
    const TEST = '133';

    protected $_test;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        $test = null,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_test = $test;
    }

    public function getTest(){
        return $this->_test;
    }
}
