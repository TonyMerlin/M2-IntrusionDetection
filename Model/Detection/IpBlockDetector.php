<?php
namespace Merlin\IntrusionDetection\Model\Detection;
use Merlin\IntrusionDetection\Api\DetectorInterface;
use Merlin\IntrusionDetection\Model\BlockService;
use Magento\Framework\App\RequestInterface;
class IpBlockDetector implements DetectorInterface
{
public function __construct(private BlockService $blockService) {}
public function getName(): string { return 'ip_block'; }
public function inspect(RequestInterface $request): array
{
$ip = $request->getServer('REMOTE_ADDR') ?? '';
if ($ip && $this->blockService->isBlocked($ip)) { return [true, 'high', 'IP currently blocked']; }
return [false, 'low', ''];
}
}
