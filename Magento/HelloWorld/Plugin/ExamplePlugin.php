<?php

namespace Magento\HelloWorld\Plugin;
use Magento\HelloWorld\Controller\Index\Example;

class ExamplePlugin
{
    /*public function beforeSetTitle(\Magento\HelloWorld\Controller\Index\Example $subject, $title)
    {
        $title = $title . " to ";
        echo __METHOD__ . "</br>";
        return [$title];
    }

    public function aroundSetTitle(\Magento\HelloWorld\Controller\Index\Example $subject, callable $proceed,$title)
    {
//        echo $subject->getName();
        $result = $proceed($title);
        return 11+$result;
        $title .= ' Mageplaza';
        echo __METHOD__ . " - Before proceed() </br>";
        $result = $proceed($title);
//        echo  $title.'<br/>';
        $result = 1;
        echo __METHOD__ . " - After proceed() </br>";
        return $result;
    }

    public function afterSetTitle(\Magento\HelloWorld\Controller\Index\Example $subject, $result)
    {
        echo __METHOD__."<br/>";
        echo $result;
        return 5;
        return $result;
    }*/

    public function beforeSetStudent(Example $subject, $name1, $age, $address){
        $name1 = 'tuong'.$name1;
        $age += 13;
        $address.='GiaBinh';
        return [$name1, $age, $address];
    }

    public function aroundSetStudent(Example $subject, callable $proceed,$name,$age,$address){
        $name.='van';

        $result=$proceed($name,$age,$address);
        $address.='daibai';
        return $result;
    }

    public function afterSetStudent(\Magento\HelloWorld\Controller\Index\Example $subject, $result)
    {
        $result->name = 'Tuong';
        return $result;
    }

}
