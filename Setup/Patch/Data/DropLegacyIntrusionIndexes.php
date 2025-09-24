<?php
declare(strict_types=1);

namespace Merlin\IntrusionDetection\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class DropLegacyIntrusionIndexes implements DataPatchInterface
{
    public function __construct(private ModuleDataSetupInterface $moduleDataSetup) {}

    public function apply()
    {
        $setup = $this->moduleDataSetup;
        $conn  = $setup->getConnection();
        $setup->startSetup();
        try {
            $table = $setup->getTable('merlin_intrusion_event');

            if ($conn->isTableExists($table)) {
                // Drop old index names if they still exist
                $indexes = $conn->getIndexList($table);

                if (isset($indexes['MERLIN_INTRUSION_EVENT_IP'])) {
                    $conn->dropIndex($table, 'MERLIN_INTRUSION_EVENT_IP');
                }
                if (isset($indexes['MERLIN_INTRUSION_EVENT_CREATED_AT'])) {
                    $conn->dropIndex($table, 'MERLIN_INTRUSION_EVENT_CREATED_AT');
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
