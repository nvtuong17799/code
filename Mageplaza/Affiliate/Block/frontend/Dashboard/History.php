<?php
namespace Mageplaza\Affiliate\Block\frontend\Dashboard;

class History extends \Magento\Framework\View\Element\Template
{
    protected $_customerSession;

    protected $_collectionFactory;

    protected $_priceCurrency;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Mageplaza\Affiliate\Model\ResourceModel\History\CollectionFactory $collectionFactory,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        array $data = []
    )
    {
        $this->_priceCurrency = $priceCurrency;
        $this->_collectionFactory = $collectionFactory;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
    }


    public function getHistory(){
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;
        $collection = $this->_collectionFactory->create();
        $collection->addFieldToFilter('customer_id',['eq'=>$this->_customerSession->getId()]);
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        return $collection;
    }

    /**
     * @param $price
     * @return float
     */
    public function getCurrencyWithFormat($price)
    {
        return $this->_priceCurrency->format($price,false,2);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getHistory()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'customer_giftcard_dashboard_history.pager'
            )->setAvailableLimit([5 => 5, 10 => 10, 15 => 15, 20 => 20])
                ->setShowPerPage(true)->setCollection(
                    $this->getHistory()
                );
            $this->setChild('pager', $pager);
            $this->getHistory()->load();
        }
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

}
