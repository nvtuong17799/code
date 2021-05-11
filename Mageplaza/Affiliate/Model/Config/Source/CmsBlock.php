<?php
namespace Mageplaza\Affiliate\Model\Config\Source;
use Magento\Cms\Model\BlockFactory;

class CmsBlock
{
    protected $blockFactory;

    public function __construct(BlockFactory $blockFactory)
    {
        $this->blockFactory = $blockFactory;
    }

    public function toOptionArray()
    {
        $block = $this->blockFactory->create()->getCollection();
        $arrResult = [];
        foreach ($block as $value){
            $arrResult[] = ['value' => $value->getIdentifier(), 'label' => $value->getTitle()];
        }
        return $arrResult;
    }
}
