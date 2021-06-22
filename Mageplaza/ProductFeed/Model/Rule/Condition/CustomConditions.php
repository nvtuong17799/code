<?php
namespace Mageplaza\ProductFeed\Model\Rule\Condition;

use Magento\CatalogRule\Model\Rule\Condition\Combine;
use Magento\CatalogRule\Model\Rule\Condition\Product;

class CustomConditions extends \Magento\Rule\Model\Condition\AbstractCondition
{

    protected $productType;
    protected $combine;
    protected $_stockItemRepository;

    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \Mageplaza\ProductFeed\Model\Config\Source\ProductType $productType,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        Combine $combine,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->productType = $productType;
        $this->combine = $combine;
        $this->_stockItemRepository = $stockItemRepository;
    }

    public function loadAttributeOptions()
    {
        $option = [
            'type_id' => 'Type',
            'is_in_stock'=>'Stock'
        ];
        $this->setAttributeOption($option);
        return $this;
    }


    public function getValueSelectOptions(){
        $opt = [];
        $attrCode = $this->getAttribute();
        if ($this->hasValueOption()) {
            switch ($attrCode){
                case 'type_id':
                    foreach ($this->productType->toArray() as $key => $value) {
                        $opt[] = ['value' => $key, 'label' => $value];
                    }
                    break;
                case 'is_in_stock':
                    $opt[] = ['value' => 0, 'label' => 'Out of Stock'];
                    $opt[] = ['value' => 1, 'label' => 'In Stock'];
                    break;
            }
        }
        return $opt;
    }

    public function getValueElementType()
    {
        return 'select';
    }

    public function validate(\Magento\Framework\Model\AbstractModel $model)
    {
        $attrCode = $this->getAttribute();
        if ('is_in_stock' == $attrCode) {
            $productId = (int)$model->getEntityId();
            $productStock = $this->_stockItemRepository->get($productId);
            return $this->validateAttribute((int)$productStock->getIsInStock());
        }
        return $this->validateAttribute($model->getData($attrCode));
    }

    public function collectValidatedAttributes($productCollection)
    {
        foreach ($this->combine->getConditions() as $condition) {
            /** @var Product|Combine $condition */
            $condition->collectValidatedAttributes($productCollection);
        }
        return $this->combine;
    }
}
