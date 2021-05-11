<?php

namespace Magento\HelloWorld\Observer;

class Test implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {


//        echo "3333";
        $data = $observer->getEvent()->getData('dataObject');
//        echo '<pre>';
//        var_dump($data);
//        echo "<pre>";
//        echo $data->getText();
//        echo $data->getAbc();
       echo $observer->getEvent()->getData('mp1');
//       echo $observer->getMpText();

        return $this;
    }
}
