<?php
namespace Mageplaza\CustomPrice\Plugin;

use Magento\Catalog\Model\ProductFactory;
use Magento\Customer\Model\Session;
use Mageplaza\CustomPrice\Model\CustomPriceFactory;

class ChangePrice{

    protected $customPriceFactory;

    protected $session;

    protected $productFactory;

    public function __construct(
        CustomPriceFactory $customPriceFactory,
        ProductFactory $productFactory
    )
    {
        $this->customPriceFactory = $customPriceFactory;
        $this->productFactory = $productFactory;
    }

}
