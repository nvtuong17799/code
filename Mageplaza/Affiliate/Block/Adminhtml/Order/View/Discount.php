<?php
namespace Mageplaza\Affiliate\Block\Adminhtml\Order\View;

class Discount extends \Magento\Sales\Block\Order\Totals
{
    protected function initTotals()
    {
        $discountLabel = 'Affiliate Discount';
        $commissionLabel = 'Commission';
        $order = $this->getParentBlock()->getOrder();
        if ($order->getDiscountAffiliate() < 0) {
            $this->getParentBlock()
                ->addTotal(new \Magento\Framework\DataObject(array(
                    'code' => 'affiliate',
                    'value' => $order->getDiscountAffiliate(),
                    'base_value' => $order->getBaseDiscountAffiliate(),
                    'label' => $discountLabel,
                )), 'shipping');
        }
        if ($order->getCommissionAffiliate() > 0) {
            $this->getParentBlock()
                ->addTotal(new \Magento\Framework\DataObject(array(
                    'code' => 'commission',
                    'value' => $order->getCommissionAffiliate(),
                    'label' => $commissionLabel,
                )), 'affiliate');
        }
    }
}


