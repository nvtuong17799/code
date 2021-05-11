<?php
namespace Mageplaza\Affiliate\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
        $installer = $setup;

        $installer->startSetup();

        if(version_compare($context->getVersion(), '1.0.1', '<')) {
            if ($installer->tableExists('quote')) {
                $installer->getConnection()->addColumn(
                    $installer->getTable('quote'),
                    'affiliate_discount',
                    [
                        'type' => Table::TYPE_DECIMAL,
                        'size' => '12,4',
                        'nullable' => true,
                        'comment' => 'Affiliate discount',
                    ]
                );
                $installer->getConnection()->addColumn(
                    $installer->getTable('quote'),
                    'affiliate_base_discount',
                    [
                        'type' => Table::TYPE_DECIMAL,
                        'size' => '12,4',
                        'nullable' => true,
                        'comment' => 'Affiliate base discount',
                    ]
                );
                $installer->getConnection()->addColumn(
                    $installer->getTable('quote'),
                    'affiliate_commission',
                    [
                        'type' => Table::TYPE_DECIMAL,
                        'size' => '12,4',
                        'nullable' => true,
                        'comment' => 'Affiliate commission',
                    ]
                );
            }
        }

        if(version_compare($context->getVersion(), '1.0.2', '<')) {
            if ($installer->tableExists('sales_order')) {
                $installer->getConnection()->addColumn(
                    $installer->getTable('sales_order'),
                    'discount_affiliate',
                    [
                        'type' => Table::TYPE_DECIMAL,
                        'size' => '20,4',
                        'nullable' => true,
                        'comment' => 'Affiliate discount',
                    ]
                );
                $installer->getConnection()->addColumn(
                    $installer->getTable('sales_order'),
                    'base_discount_affiliate',
                    [
                        'type' => Table::TYPE_DECIMAL,
                        'size' => '20,4',
                        'nullable' => true,
                        'comment' => 'Affiliate base discount',
                    ]
                );
                $installer->getConnection()->addColumn(
                    $installer->getTable('sales_order'),
                    'commission_affiliate',
                    [
                        'type' => Table::TYPE_DECIMAL,
                        'length' => '20,4',
                        'nullable' => true,
                        'comment' => 'Affiliate commission',
                    ]
                );
            }
        }

        $installer->endSetup();
    }
}
