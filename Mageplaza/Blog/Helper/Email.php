<?php
namespace Mageplaza\Blog\Helper;

use Magento\Customer\Api\CustomerRepositoryInterface;
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
    protected $_customerRepository;

    public function __construct(
        Context $context,
        StateInterface $inlineTranslation,
        Escaper $escaper,
        TransportBuilder $transportBuilder,
        CustomerRepositoryInterface $customerRepository
    ) {
        parent::__construct($context);
        $this->inlineTranslation   = $inlineTranslation;
        $this->escaper             = $escaper;
        $this->transportBuilder    = $transportBuilder;
        $this->logger              = $context->getLogger();
        $this->_customerRepository = $customerRepository;
    }

    public function sendEmail($object)
    {
        $customer     = $this->_customerRepository->getById($object->getEntityId());
        try {
            $this->inlineTranslation->suspend();
            $users     = $customer->getId() ? $customer->getFirstname() . ' ' . $customer->getLastname() : __('Guest');
            $sender = [
                'name' => $this->escaper->escapeHtml('Blog Comment Notification'),
                'email' => $this->escaper->escapeHtml('tuongnv@mageplaza.com'),
            ];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('comment_email')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars([
                    'templateVar'  => $users,
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
