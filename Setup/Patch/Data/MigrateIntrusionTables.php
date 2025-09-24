<?php
declare(strict_types=1);

namespace Merlin\IntrusionDetection\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class MigrateIntrusionTables implements DataPatchInterface
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
            $this->migrate(
                $setup,
                'merlin_intrusion_event_old',
                'merlin_intrusion_event',
                ['event_id'] // PK to exclude so it re-autoincrements cleanly
            );

            $this->migrate(
                $setup,
                'merlin_blocked_ip_old',
                'merlin_blocked_ip',
                ['block_id'] // PK to exclude
            );
        } finally {
            $setup->endSetup();
        }

        return $this;
    }

    private function migrate(ModuleDataSetupInterface $setup, string $old, string $new, array $excludeCols): void
    {
        $conn = $setup->getConnection();
        $oldTable = $setup->getTable($old);
        $newTable = $setup->getTable($new);

        if (!$conn->isTableExists($oldTable) || !$conn->isTableExists($newTable)) {
            return; // nothing to do
        }

        // Build a safe column intersection (exclude PKs)
        $oldDesc = $conn->describeTable($oldTable);
        $newDesc = $conn->describeTable($newTable);

        $oldCols = array_keys($oldDesc);
        $newCols = array_keys($newDesc);

        $cols = array_values(array_diff(array_intersect($oldCols, $newCols), $excludeCols));
        if (!$cols) {
            // nothing in common — drop the old table and move on
            $conn->dropTable($oldTable);
            return;
        }

        // Quote column identifiers
        $quoted = array_map(fn($c) => $conn->quoteIdentifier($c), $cols);
        $colsList = implode(',', $quoted);

        // Copy data
        $sql = sprintf(
            'INSERT INTO %s (%s) SELECT %s FROM %s',
            $conn->quoteIdentifier($newTable),
            $colsList,
            $colsList,
            $conn->quoteIdentifier($oldTable)
        );
        $conn->query($sql);

        // Drop the old backup table
        $conn->dropTable($oldTable);
    }

    public static function getDependencies(): array
    {
        // Ensure the rename (schema) happens before this data migration
        return [\Merlin\IntrusionDetection\Setup\Patch\Schema\RenameIntrusionTables::class];
    }

    public function getAliases(): array { return []; }
}
