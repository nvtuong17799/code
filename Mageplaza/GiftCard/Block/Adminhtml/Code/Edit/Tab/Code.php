<?php
namespace Mageplaza\GiftCard\Block\Adminhtml\Code\Edit\Tab;
class Code extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_helperData;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Mageplaza\GiftCard\Helper\Data $helperData,
        array $data = []
    )
    {
        $this->_helperData = $helperData;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $form = $this->_formFactory->create();
        $giftCard = $this->_coreRegistry->registry('giftcard_form_data');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Gift Card Information')]);
        if($giftCard->getId()){
            $fieldset->addField('giftcard_id', 'hidden', [
                'name' => 'giftcard_id',
                'value' => $giftCard->getId()
            ]);
            $fieldset->addField('code', 'text', [
                'name' => 'code',
                'label'    => __('Code'),
                'value' => $giftCard->getCode(),
                'disabled' => true,
                'class' => 'validate-number integer not-negative-amount',
            ]);
            $fieldset->addField('balance', 'text', [
                'name' => 'balance',
                'label'    => __('Balance'),
                'required' => true,
                'value' => $giftCard->getBalance(),
                'class' => 'validate-number not-negative-amount',
            ]);
            $fieldset->addField('created_form', 'text', [
                'name' => 'created_form',
                'label'    => __('Created From'),
                'value' => $giftCard->getCreatedFrom(),
                'disabled' => true,
            ]);
        }else{
            $fieldset->addField('code_length', 'text', [
                'name' => 'code_length',
                'label'    => __('Code Length'),
                'value' => $this->_helperData->getCodeConfig('length'),
                'class' => 'integer not-negative-amount'
            ]);
            $fieldset->addField('balance', 'text', [
                'name' => 'balance',
                'label'    => __('Balance'),
                'required' => true,
                'class' => 'number not-negative-amount',
            ]);
            $fieldset->addField('created_from', 'hidden', [
                'name' => 'created_from',
                'value' => 'admin'
            ]);
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }


    public function getTabLabel()
    {
        return __('Gift card information');
    }

    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}
