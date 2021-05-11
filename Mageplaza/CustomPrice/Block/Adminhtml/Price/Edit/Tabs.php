<?php
namespace Mageplaza\CustomPrice\Block\Adminhtml\Price\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('customprice_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Rule Information'));
    }
}
