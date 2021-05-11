<?php
namespace Mageplaza\Affiliate\Plugin\frontend;

use Magento\Checkout\Model\Session;

class ReCollectTotals{
    protected $checkoutSession;
    public function __construct(
        Session $session
    )
    {
        $this->checkoutSession = $session;
    }
    public function afterExecute(\Magento\Checkout\Controller\Cart\Index $subject, $result)
    {
        $quote = $this->checkoutSession->getQuote();
        $quote->collectTotals()->save();
        return $result;
    }
}
