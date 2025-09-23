<?php
namespace Merlin\IntrusionDetection\Model;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Merlin\IntrusionDetection\Model\BlockedIpFactory;
use Merlin\IntrusionDetection\Model\ResourceModel\BlockedIp as BlockedIpRes;
use Merlin\IntrusionDetection\Model\ResourceModel\BlockedIp\CollectionFactory as BlockedCollectionFactory;

class BlockService
{
    public function __construct(
        private BlockedIpFactory $blockedFactory,
        private BlockedIpRes $blockedRes,
        private BlockedCollectionFactory $collectionFactory,
        private DateTime $date
    ) {}

    public function isBlocked(string $ip): bool
    {
        $col = $this->collectionFactory->create();
        $col->addFieldToFilter('ip', $ip);
        $col->addFieldToFilter(['expires_at', 'expires_at'], [['null' => true], ['gteq' => $this->date->gmtDate()]]);
        return (bool)$col->getSize();
    }

    public function block(string $ip, ?string $reason = null, ?int $minutes = null): void
    {
        $model = $this->blockedFactory->create();
        $model->setData(['ip' => $ip, 'reason' => $reason]);
        if ($minutes) {
            $model->setData('expires_at', $this->date->gmtDate('Y-m-d H:i:s', strtotime("+{$minutes} minutes")));
        }
        try { $this->blockedRes->save($model); } catch (\Exception $e) { /* ignore duplicates */ }
    }

    public function unblock(string $ip): int
    {
        $col = $this->collectionFactory->create();
        $col->addFieldToFilter('ip', $ip);
        $count = 0;
        foreach ($col as $item) { $this->blockedRes->delete($item); $count++; }
        return $count;
    }
}
