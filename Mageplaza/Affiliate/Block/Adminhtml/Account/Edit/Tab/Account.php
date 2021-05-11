<?php
namespace Mageplaza\Affiliate\Block\Adminhtml\Account\Edit\Tab;
use Magento\Customer\Model\CustomerFactory;

class Account extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_helperData;

    protected $customerFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Mageplaza\GiftCard\Helper\Data $helperData,
        CustomerFactory $customerFactory,
        array $data = []
    )
    {
        $this->customerFactory = $customerFactory;
        $this->_helperData = $helperData;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $form = $this->_formFactory->create();
        $customer = $this->customerFactory->create();
        $account = $this->_coreRegistry->registry('account_data');
        $customer->load($account->getCustomerId());
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Account Information')]);
        if($account->getId()){
            $fieldset->addField('account_id', 'hidden', [
               'name' => 'account_id',
                'value' => $account->getId()
            ]);
            $fieldset->addField('customer_id', 'text', [
               'name' => 'customer_id',
                'label'    => __('Customer ID'),
                'value' => $account->getCustomerId(),
                'disabled' => true
            ]);
            $fieldset->addField('customer_name', 'text', [
                'name' => 'customer_name',
                'label'    => __('Customer Name'),
                'value' => $customer->getData('firstname')." ".$customer->getData('lastname')." (".$customer->getEmail().")",
                'disabled' => true,
            ]);
            $fieldset->addField('code', 'text', [
                'name' => 'code',
                'label' => __('Affiliate Code'),
                'value' => $account->getCode(),
                'disabled' => true
            ]);
            $fieldset->addField('status', 'select', [
                'name' => 'status',
                'label'    => __('Status'),
                'values' => array('1' => 'Active','0' => 'Inactive')
            ]);
            $fieldset->addField('balance', 'text', [
                'name' => 'balance',
                'label'    => __('Balance'),
                'value' => $account->getBalance(),
            ]);
            $fieldset->addField('create_at', 'date', [
                'name' => 'create_at',
                'label'    => __('Create At'),
                'value' => $account->getCreateAt(),
                'date_format' => 'YYYY-mm-dd',
                'disabled' => true
            ]);
        }else{
            $fieldset->addField('customer_id', 'text', [
                'name' => 'customer_id',
                'label'    => __('Customer ID'),
                'class' => 'integer not-negative-amount'
            ]);
            $fieldset->addField('code', 'hidden', [
                'name' => 'code',
            ]);
            $fieldset->addField('status', 'select', [
                'name' => 'status',
                'label'    => __('Status'),
                'values' => array('1' => 'Active','0' => 'Inactive')
            ]);
            $fieldset->addField('balance', 'text', [
                'name' => 'balance',
                'label'    => __('Initial Balance'),
                'required' => true,
                'class' => 'number not-negative-amount'
            ]);
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }


    public function getTabLabel()
    {
        return __('Account Information');
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
