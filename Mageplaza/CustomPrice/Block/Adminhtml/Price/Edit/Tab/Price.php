<?php
namespace Mageplaza\CustomPrice\Block\Adminhtml\Price\Edit\Tab;
use Magento\Catalog\Model\ProductFactory;
use Magento\Customer\Model\CustomerFactory;

class Price extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $_helperData;

    protected $customerFactory;

    protected $productFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Mageplaza\GiftCard\Helper\Data $helperData,
        CustomerFactory $customerFactory,
        ProductFactory $productFactory,
        array $data = []
    )
    {
        $this->productFactory = $productFactory;
        $this->customerFactory = $customerFactory;
        $this->_helperData = $helperData;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $form = $this->_formFactory->create();
        $customer = $this->customerFactory->create();
        $product = $this->productFactory->create();
        $customPrice = $this->_coreRegistry->registry('customprice_data');
        $product->load($customPrice->getProductId());
        $customer->load($customPrice->getCustomerId());
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Rule Information')]);
        if($customPrice->getId()){
            $fieldset->addField('id', 'hidden', [
               'name' => 'customprice_id',
                'value' => $customPrice->getId()
            ]);
            $fieldset->addField('sku', 'hidden', [
               'name' => 'product_sku',
                'value' => $product->getData('sku')
            ]);
            $fieldset->addField('customer_id', 'hidden', [
               'name' => 'customer_id',
                'value' => $customPrice->getCustomerId()
            ]);
            $fieldset->addField('customer_name', 'text', [
                'name' => 'customer_name',
                'label'    => __('Customer'),
                'value' => $customer->getData('firstname')." ".$customer->getData('lastname'),
                'disabled' => true,
            ]);
            $fieldset->addField('product_name', 'text', [
                'name' => 'product_name',
                'label' => __('Product'),
                'value' => $product->getData('name'),
                'disabled' => true
            ]);
            $fieldset->addField('price', 'text', [
                'name' => 'price',
                'label'    => __('Specific Price'),
                'value' => $customPrice->getPrice(),
                'required' => true,
                'class' => 'number not-negative-amount'
            ]);
            $fieldset->addField('start_date', 'date', [
                'name' => 'start_date',
                'label'    => __('Start Date'),
                'value' => $customPrice->getData('start_date'),
                'date_format' => 'yyyy-MM-dd'
            ]);
            $fieldset->addField('end_date', 'date', [
                'name' => 'end_date',
                'label'    => __('End Date'),
                'value' => $customPrice->getData('end_date'),
                'date_format' => 'yyyy-MM-dd'
            ]);
        }else{
            $fieldset->addField('id', 'text', [
                'name' => 'customer_id',
                'label'    => __('Customer ID'),
                'class' => 'integer not-negative-amount'
            ]);
            $fieldset->addField('sku', 'text', [
                'name' => 'product_sku',
                'label'    => __('Product Sku'),
            ]);
            $fieldset->addField('price', 'text', [
                'name' => 'price',
                'label'    => __('Specific Price'),
                'required' => true,
                'class' => 'number not-negative-amount'
            ]);
            $fieldset->addField('start_date', 'date', [
                'name' => 'start_date',
                'label'    => __('Start Date'),
                'date_format' => 'yyyy-MM-dd'
            ]);
            $fieldset->addField('end_date', 'date', [
                'name' => 'end_date',
                'label'    => __('End Date'),
                'date_format' => 'yyyy-MM-dd'
            ]);
        }

        $this->setForm($form);
        return parent::_prepareForm();
    }


    public function getTabLabel()
    {
        return __('Rule Information');
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
