<?php

namespace Mageplaza\GiftCard\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('giftcard_code')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('giftcard_code')
            )
                ->addColumn(
                    'giftcard_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary'  => true,
                        'unsigned' => true,
                    ],
                    'GiftCard ID'
                )
                ->addColumn(
                    'code',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'GiftCard Code'
                )
                ->addColumn(
                    'balance',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    '12,4',
                    [],
                    'GiftCard Value'
                )
                ->addColumn(
                    'amount_used',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    12,4,
                    [],
                    'Amount Used'
                )
                ->addColumn(
                    'create_from',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Create From'
                )
                ->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'Created At'
                )
                ->setComment('GiftCard Table');
            $installer->getConnection()->createTable($table);

            $installer->getConnection()->addIndex(
                $installer->getTable('giftcard_code'),
                $setup->getIdxName(
                    $installer->getTable('giftcard_code'),
                    ['code', 'balance', 'amount_used', 'create_from'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['code', 'balance', 'amount_used', 'create_from'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }
        $installer->endSetup();
    }
}
