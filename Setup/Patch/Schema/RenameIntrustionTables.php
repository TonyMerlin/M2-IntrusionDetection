<?php
declare(strict_types=1);

namespace Merlin\IntrusionDetection\Setup\Patch\Schema;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;

class RenameIntrusionTables implements SchemaPatchInterface
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
            $pairs = [
                ['merlin_intrusion_event', 'merlin_intrusion_event_old'],
                ['merlin_blocked_ip',      'merlin_blocked_ip_old'],
            ];

            foreach ($pairs as [$orig, $backup]) {
                $origName = $setup->getTable($orig);
                $backupName = $setup->getTable($backup);

                if ($conn->isTableExists($origName)) {
                    // If a previous _old table exists, leave it to avoid overwriting
                    if (!$conn->isTableExists($backupName)) {
                        $conn->renameTable($origName, $backupName);
                    } else {
                        // If both exist, drop the (possibly empty) freshly created one so declarative can recreate later
                        $conn->dropTable($origName);
                    }
                }
            }
        } finally {
            $setup->endSetup();
        }
        return $this;
    }

    public static function getDependencies(): array { return []; }
    public function getAliases(): array { return []; }
}
