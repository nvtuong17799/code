<?php
namespace Mageplaza\Affiliate\Model\Config\Source;


class OptionsApplyDiscount
{
    public function toOptionArray()
    {
        return [
            [
                'value' => 'no',
                'label' => __('No')
            ],
            [
                'value' => 'fix_value',
                'label' => __('Fixed Value')
            ],
            [
                'value' => 'percentage',
                'label' => __('Percentage of order total')
            ],
        ];
    }
}
