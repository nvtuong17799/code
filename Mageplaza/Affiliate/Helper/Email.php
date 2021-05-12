<?php
namespace Mageplaza\Affiliate\Helper;

use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;

class Email extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $inlineTranslation;
    protected $escaper;
    protected $transportBuilder;
    protected $logger;
    protected $customerFactory;

    public function __construct(
        Context $context,
        StateInterface $inlineTranslation,
        Escaper $escaper,
        TransportBuilder $transportBuilder,
        CustomerFactory $customerFactory
    ) {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->logger = $context->getLogger();
        $this->customerFactory = $customerFactory;
    }

    public function sendEmail($value, $customerId)
    {
        $customer = $this->customerFactory->create();
        $customer->load($customerId);
        try {
            $this->inlineTranslation->suspend();
            $sender = [
                'name' => $this->escaper->escapeHtml('Affiliate Commission'),
                'email' => $this->escaper->escapeHtml('tuongnv@mageplaza.com'),
            ];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('affiliate_email')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars([
                    'templateVar'  => $value,
                ])
                ->setFrom($sender)
                ->addTo($customer->getEmail())
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }
}
