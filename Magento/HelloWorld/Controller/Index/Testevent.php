<?php
namespace Magento\HelloWorld\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\DataObject;

class Testevent extends Action{
    public function execute()
    {
        $textDisplay = new DataObject(array('text'=>'tuong','abc'=>'def'));


//        echo "1111111";
        ///3333
        $this->_eventManager->dispatch('tuong',
            [
                'mp_text' => 'test_event',
                'mp1'=>'abc',
                'dataObject' => $textDisplay
            ]);

//        echo "222222";
    }
}
