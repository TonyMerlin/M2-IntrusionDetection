<?php
declare(strict_types=1);

namespace Merlin\IntrusionDetection\Setup\Patch\Schema;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;

class ResetIntrusionTables implements SchemaPatchInterface
{
    public function __construct(
        private ModuleDataSetupInterface $moduleDataSetup
    ) {}

    public function apply()
    {
        $setup = $this->moduleDataSetup;
        $conn  = $setup->getConnection();

        $setup->startSetup();
        try {
            // Drop legacy/old tables so declarative schema can recreate them cleanly
            $tables = [
                $setup->getTable('merlin_intrusion_event'),
                $setup->getTable('merlin_blocked_ip'),
            ];

            foreach ($tables as $table) {
                if ($conn->isTableExists($table)) {
                    $conn->dropTable($table);
                }
            }
        } finally {
            $setup->endSetup();
        }

        return $this;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}
