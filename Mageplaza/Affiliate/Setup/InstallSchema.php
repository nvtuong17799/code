<?php
namespace Mageplaza\Affiliate\Setup;

use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('affiliate_account')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('affiliate_account')
            )
                ->addColumn(
                    'account_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true,
                    ],
                    'Account ID'
                )
                ->addColumn(
                    'customer_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'unsigned' => true],
                    'Customer ID '
                )
                ->addColumn(
                    'code',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'unique' => true],
                    'Account code'
                )
                ->addColumn(
                    'balance',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    '12,4',
                    [],
                    'Money of account'
                )
                ->addColumn(
                    'status',
                    \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                    null,
                    [],
                    'active/inactive'
                )->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'Created At'
                )
                ->setComment('Affiliate Account');

            $installer->getConnection()->createTable($table);
            $installer->getConnection()->addForeignKey(
                $installer->getFkName('affiliate_account', 'customer_id', 'customer_entity', 'entity_id'),
                $installer->getTable('affiliate_account'),
                'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            );
        }

        if (!$installer->tableExists('affiliate_history')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('affiliate_history')
            )
                ->addColumn(
                    'history_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true,
                    ],
                    'History affiliate ID'
                )
                ->addColumn(
                    'order_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true],
                    'Order ID '
                )
                ->addColumn(
                    'order_increment_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    32,
                    [],
                    'Order Increment Id'
                )
                ->addColumn(
                    'customer_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true],
                    'Customer ID'
                )
                ->addColumn(
                    'is_admin_change',
                    \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                    null,
                    [],
                    'active/inactive'
                )->addColumn(
                    'amount',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    '12,4',
                    [],
                    'Amount of account'
                )->addColumn(
                    'status',
                    \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                    null,
                    [],
                    'active/inactive'
                )->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'Created At'
                )
                ->setComment('Affiliate History');

            $installer->getConnection()->createTable($table);
            $installer->getConnection()->addForeignKey(
                $installer->getFkName('affiliate_history', 'customer_id', 'customer_entity', 'entity_id'),
                $installer->getTable('affiliate_history'),
                'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            );
            $installer->getConnection()->addForeignKey(
                $installer->getFkName('affiliate_history', 'order_id', 'sales_order', 'entity_id'),
                $installer->getTable('affiliate_history'),
                'order_id',
                $installer->getTable('sales_order'),
                'entity_id',
                Table::ACTION_CASCADE
            );
            $installer->getConnection()->addForeignKey(
                $installer->getFkName('affiliate_history', 'order_increment_id', 'sales_order', 'increment_id'),
                $installer->getTable('affiliate_history'),
                'order_increment_id',
                $installer->getTable('sales_order'),
                'increment_id',
                Table::ACTION_CASCADE
            );
        }

        $installer->endSetup();
    }
}
