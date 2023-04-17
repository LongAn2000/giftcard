<?php

namespace Mageplaza\GiftCard\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $data = [
            'code' => 'code2',
            'balance'=> 13,
            'amount_used' => 10,
            'create_from' => 'admin'

        ];

        $table = $setup->getTable('giftcard_code');
        $setup->getConnection()->insert($table, $data);

        $setup->endSetup();

    }
}
