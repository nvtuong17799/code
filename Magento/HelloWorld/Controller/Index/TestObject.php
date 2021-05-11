<?php
namespace Magento\HelloWorld\Controller\Index;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;

class TestObject extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    public function __construct(EntityFactory $entityFactory,
                                Logger $logger, FetchStrategy $fetchStrategy,
                                EventManager $eventManager,
                                $mainTable ='magento_helloworld_post',
                                $resourceModel = 'Magento\HelloWorld\Model\ResourceModel\Post',
                                $identifierName = null,
                                $connectionName = null)
    {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel, $identifierName, $connectionName);
    }

    public function getName()
    {
        echo 'Hello World - Tuong';
    }
}
