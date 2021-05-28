<?php

namespace Mageplaza\Blog\Controller\Post;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Mageplaza\Blog\Model\PostFactory;

class Rating extends Action
{
    protected $postFactory;

    public function __construct(
        Context $context,
        PostFactory $postFactory
    )
    {
        $this->postFactory = $postFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON);
        $params = $this->getRequest()->getParams();
        $post = $this->postFactory->create();
        $post->load($params['id']);

        if($post->getId()){
            $numberRating = $post->getData('rating_number');
            $starRating = $post->getData('rating_star');
            if($starRating == null){
                $post->setData('rating_star',$params['rating']);
                $post->setData('rating_number', '1');
            }else{
                $newStarRating = (($starRating*$numberRating)+$params['rating'])/($numberRating+1);
                $post->setData('rating_star', $newStarRating);
                $post->setData('rating_number', $numberRating+1);
            }
            $post->save();
            $resultJson->setData([
                'star'    => $post->getData('rating_star'),
                'number'  => $post->getData('rating_number')
            ]);
        }

        return $resultJson;
    }

}
