<?php

namespace Magento\HelloWorld\Controller\Index;

class Example extends \Magento\Framework\App\Action\Action
{

    protected $title;
    public $name;
    protected $age;
    protected $address;

    public function execute()
    {
        $this->setStudent('a',12,'bacninh');
        echo $this->name."<br/>";
        echo $this->age."<br/>";
        echo $this->address."<br/>";

    }

    public function setStudent($name, $age, $address){
        $this->name = $name;
        $this->age = $age;
        $this->address = $address;
        return $this;
    }
}

