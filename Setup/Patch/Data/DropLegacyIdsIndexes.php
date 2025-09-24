<?php
declare(strict_types=1);

namespace Merlin\IntrusionDetection\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class DropLegacyIdsIndexes implements DataPatchInterface
{
    public function __construct(private ModuleDataSetupInterface $setup) {}

    public function apply()
    {
        $conn = $this->setup->getConnection();
        $this->setup->startSetup();
        try {
            // merlin_intrusion_event old names
            $this->dropIndexIfExists('merlin_intrusion_event', 'MERLIN_INTRUSION_EVENT_IP');
            $this->dropIndexIfExists('merlin_intrusion_event', 'MERLIN_INTRUSION_EVENT_CREATED_AT');

            // merlin_blocked_ip old names seen in the wild
            $this->dropIndexIfExists('merlin_blocked_ip', 'MERLIN_BLOCKED_IP_IP');          // old non-unique ip index
            $this->dropIndexIfExists('merlin_blocked_ip', 'MERLIN_BLOCKED_IP_EXPIRES_AT');  // old expires index

            // If you previously had differently cased names, drop those too (MySQL can be case-insensitive by collation/filesystem)
            $this->dropIndexIfExists('merlin_blocked_ip', 'merlin_blocked_ip_ip');
            $this->dropIndexIfExists('merlin_intrusion_event', 'merlin_intrusion_event_ip');

            // Do NOT drop MERLIN_BLOCKED_IP_UNIQ (that’s our desired unique ip index).
        } finally {
            $this->setup->endSetup();
        }
        return $this;
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        $conn = $this->setup->getConnection();
        $t = $this->setup->getTable($table);
        if (!$conn->isTableExists($t)) {
            return;
        }
        $indexes = $conn->getIndexList($t); // array keyed by index name
        if (isset($indexes[$indexName])) {
            $conn->dropIndex($t, $indexName);
        }
    }

    public static function getDependencies(): array { return []; }
    public function getAliases(): array { return []; }
}
