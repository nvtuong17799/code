<?php
namespace Mageplaza\GiftCard\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context ) {
        $installer = $setup;

        $installer->startSetup();

        if(version_compare($context->getVersion(), '2.0.0', '<')) {
            if (!$installer->tableExists('giftcard_history')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('giftcard_history')
                )
                    ->addColumn(
                        'history_id',
                        Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary'  => true,
                            'unsigned' => true,
                        ]
                    )
                    ->addColumn(
                        'giftcard_id',
                        Table::TYPE_INTEGER,
                        null,
                        [
                            'nullable' => false,
                            'unsigned' => true,
                        ]
                    )
                    ->addColumn(
                        'customer_id',
                        Table::TYPE_INTEGER,
                        null,
                        [
                            'nullable' => false,
                            'unsigned' => true,
                        ]
                    )
                    ->addColumn(
                        'amount',
                        Table::TYPE_DECIMAL,
                        '12,4',
                        ['nullable' => false]
                    )
                    ->addColumn(
                        'action',
                        Table::TYPE_TEXT,
                        '255',
                        ['nullable' => false]
                    )
                    ->addColumn(
                        'action_time',
                        Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => Table::TIMESTAMP_INIT]
                    );
                $installer->getConnection()->createTable($table);

                $installer->getConnection()->addForeignKey(
                    $installer->getFkName('giftcard_history', 'giftcard_id', 'giftcard_code', 'giftcard_id'),
                    $installer->getTable('giftcard_history'),
                    'giftcard_id',
                    $installer->getTable('giftcard_code'),
                    'giftcard_id',
                    Table::ACTION_CASCADE
                );

                $installer->getConnection()->addForeignKey(
                    $installer->getFkName('giftcard_history', 'customer_id', 'customer_entity', 'entity_id'),
                    $installer->getTable('giftcard_history'),
                    'customer_id',
                    $installer->getTable('customer_entity'),
                    'entity_id',
                    Table::ACTION_CASCADE
                );
            }

            if (!$installer->tableExists('giftcard_customer_balance')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('giftcard_customer_balance')
                )
                    ->addColumn(
                        'customer_id',
                        Table::TYPE_INTEGER,
                        null,
                        [
                            'nullable' => false,
                            'unsigned' => true,
                        ]
                    )
                    ->addColumn(
                        'balance',
                        Table::TYPE_DECIMAL,
                        '12,4',
                        ['nullable => false']
                    );
                $installer->getConnection()->createTable($table);

                $installer->getConnection()->addForeignKey(
                    $installer->getFkName('giftcard_customer_balance', 'customer_id', 'customer_entity', 'entity_id'),
                    $installer->getTable('giftcard_customer_balance'),
                    'customer_id',
                    $installer->getTable('customer_entity'),
                    'entity_id',
                    Table::ACTION_CASCADE
                );
            }

        }

        if(version_compare($context->getVersion(), '2.1.0', '<')) {
            if ($installer->tableExists('quote')) {
                $installer->getConnection()->addColumn(
                    $installer->getTable('quote'),
                    'giftcard_code',
                    [
                        'type' => Table::TYPE_TEXT,
                        'size' => 255,
                        'nullable' => true,
                        'comment' => 'GiftCard code',
                    ]
                );

                $installer->getConnection()->addColumn(
                    $installer->getTable('quote'),
                    'giftcard_base_discount',
                    [
                        'type' => Table::TYPE_DECIMAL,
                        'size' => '12,4',
                        'nullable' => true,
                        'comment' => 'GiftCard base discount',
                    ]
                );

                $installer->getConnection()->addColumn(
                    $installer->getTable('quote'),
                    'giftcard_discount',
                    [
                        'type' => Table::TYPE_DECIMAL,
                        'size' => '12,4',
                        'nullable' => true,
                        'comment' => 'GiftCard discount',
                    ]
                );

            }
        }

        $installer->endSetup();
    }
}
