<?php
declare(strict_types=1);

namespace Merlin\IntrusionDetection\Model;

use Merlin\IntrusionDetection\Api\BlockServiceInterface;
use Merlin\IntrusionDetection\Model\ResourceModel\BlockedIp as BlockedIpResource;
use Merlin\IntrusionDetection\Model\BlockedIpFactory;

class BlockService implements BlockServiceInterface {
    private $blockedIpFactory; private $blockedIpResource;
    public function __construct(BlockedIpFactory $blockedIpFactory, BlockedIpResource $blockedIpResource){ $this->blockedIpFactory=$blockedIpFactory; $this->blockedIpResource=$blockedIpResource; }
    public function block(string $ip, string $reason = null, int $minutes = 60): void {
        $model = $this->blockedIpFactory->create();
        $this->blockedIpResource->load($model, $ip, 'ip');
        $expires = $minutes > 0 ? (new \DateTime('now', new \DateTimeZone('UTC')))->modify('+' . $minutes . ' minutes')->format('Y-m-d H:i:s') : null;
        $model->setData(['ip'=>$ip,'reason'=>$reason,'expires_at'=>$expires]);
        $this->blockedIpResource->save($model);
    }
    public function unblock(string $ip): void {
        $model = $this->blockedIpFactory->create();
        $this->blockedIpResource->load($model, $ip, 'ip');
        if ($model->getId()) { $this->blockedIpResource->delete($model); }
    }
    public function isBlocked(string $ip): bool {
        $model = $this->blockedIpFactory->create();
        $this->blockedIpResource->load($model, $ip, 'ip');
        if (!$model->getId()) return false;
        $expires = $model->getData('expires_at');
        if ($expires && strtotime($expires) < time()) { $this->blockedIpResource->delete($model); return false; }
        return true;
    }
    public function listBlocks(): array {
        $col = $this->blockedIpFactory->create()->getCollection();
        $out = [];
        foreach ($col as $m) { $out[] = ['ip'=>(string)$m->getData('ip'),'reason'=>$m->getData('reason'),'expires_at'=>$m->getData('expires_at'),'created_at'=>$m->getData('created_at')]; }
        return $out;
    }
}
