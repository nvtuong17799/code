<?php
namespace Mageplaza\ProductFeed\Plugin;

class CustomConditions{
    protected $_productFactory;
    public function __construct(
        \Magento\CatalogRule\Model\Rule\Condition\ProductFactory $conditionFactory
    )
    {
        $this->_productFactory = $conditionFactory;
    }

    public function afterGetNewChildSelectOptions(
        \Magento\CatalogRule\Model\Rule\Condition\Combine $subject, $result
    ){
        $productAttributes = $this->_productFactory->create()->loadAttributeOptions()->getAttributeOption();
        $attributes = [];
        foreach ($productAttributes as $code => $label) {
            $attributes[] = [
                'value' => 'Magento\CatalogRule\Model\Rule\Condition\Product|' . $code,
                'label' => $label,
            ];
        }

        $valueOption = ['type_id', 'is_in_stock'];
        $labelOption = ['Type', 'Stock'];
        $option = array_combine($valueOption,$labelOption);
        foreach ($option as $value => $label) {
            $attributes[] = [
                'value' => 'Mageplaza\ProductFeed\Model\Rule\Condition\CustomConditions|' . $value,
                'label' => $label,
            ];
        }

        array_pop($result);
        return array_merge_recursive(
            $result,
            [
                ['label' => __('Product Attribute'), 'value' => $attributes]
            ]
        );
    }
}
