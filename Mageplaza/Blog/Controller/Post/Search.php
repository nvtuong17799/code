<?php

namespace Mageplaza\Blog\Controller\Post;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Mageplaza\Blog\Block\Sidebar;

class Search extends Action
{
    protected $search;
    public function __construct(Context $context, Sidebar\Search $search)
    {
        $this->search = $search;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON);
        $type = $this->getRequest()->getParam('type');
//        switch ($type){
//            case 'all':
//                $result = $this->search->getSearchAllData();
//                break;
//            case 'post':
//                $result = $this->search->getSearchObjectData('post');
//                break;
//            case 'category':
//                $result = $this->search->getSearchObjectData('category');
//                break;
//            case 'topic':
//                $result = $this->search->getSearchObjectData('topic');
//                break;
//            case 'tag':
//                $result = $this->search->getSearchObjectData('tag');
//                break;
//        }
        $resultJson->setData($type);
        return $resultJson;
    }

}
