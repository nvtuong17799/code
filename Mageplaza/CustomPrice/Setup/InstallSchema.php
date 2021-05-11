<?php
namespace Mageplaza\CustomPrice\Setup;

use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('mageplaza_customprice')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('mageplaza_customprice')
            )
                ->addColumn(
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true,
                    ],
                    'Custom Price ID'
                )
                ->addColumn(
                    'customer_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    255,
                    ['nullable' => false, 'unsigned' => true],
                    'Customer ID '
                )
                ->addColumn(
                    'product_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    255,
                    ['nullable' => false, 'unsigned' => true],
                    'Customer ID '
                )
                ->addColumn(
                    'price',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    '12,4',
                    [],
                    'Specific Price'
                )
                ->addColumn(
                    'start_date',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false],
                    'Start date'
                )->addColumn(
                    'end_date',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false],
                    'End date')
                ->setComment('Custom Price Table');

            $installer->getConnection()->createTable($table);
            $installer->getConnection()->addForeignKey(
                $installer->getFkName('mageplaza_customprice', 'product_id', 'catalog_product_entity', 'entity_id'),
                $installer->getTable('mageplaza_customprice'),
                'product_id',
                $installer->getTable('catalog_product_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            );

            $installer->getConnection()->addForeignKey(
                $installer->getFkName('mageplaza_customprice', 'customer_id', 'customer_entity', 'entity_id'),
                $installer->getTable('mageplaza_customprice'),
                'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            );

        }
        $installer->endSetup();
    }
}
