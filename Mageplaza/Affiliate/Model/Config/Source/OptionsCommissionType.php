<?php
namespace Mageplaza\Affiliate\Model\Config\Source;


class OptionsCommissionType
{
    public function toOptionArray()
    {
        return [
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
