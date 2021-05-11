<?php

namespace Magento\HelloWorld\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeData implements UpgradeDataInterface
{
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            $setup->startSetup();
            $data = [
                'name'         => "Tuong",
                'post_content' => "In this article, we will find out how to install and upgrade sql script for module in Magento 2. When you install or upgrade a module, you may need to change the database structure or add some new data for current table. To do this, Magento 2 provide you some classes which you can do all of them.",
                'url_key'      => '/magento-2-module-development/magento-2-how-to-create-sql-setup-script.html',
                'tags'         => 'magento 2,mageplaza helloworld',
                'status'       => 1
            ];

            $table = $setup->getTable('magento_helloworld_post');
            $setup->getConnection()->insert($table, $data);

            $setup->endSetup();
        }
    }
}
