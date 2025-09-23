<?php
namespace Merlin\IntrusionDetection\Model\Detection;

use Merlin\IntrusionDetection\Api\DetectorInterface;
use Merlin\IntrusionDetection\Model\Config;
use Merlin\IntrusionDetection\Model\RateLimiter;
use Magento\Framework\App\RequestInterface;

class RateLimiterDetector implements DetectorInterface
{
    public function __construct(private Config $config, private RateLimiter $rl) {}
    public function getName(): string { return 'rate_limit'; }

    public function inspect(RequestInterface $request): array
    {
        if (!$this->config->rlEnabled()) return [false, 'low', ''];
        $ip = $request->getServer('REMOTE_ADDR') ?? '';
        [$count] = $this->rl->hit('ip:' . $ip, $this->config->rlWindow(), $this->config->rlMax());
        $limit = $this->config->rlMax() * max(1, $this->config->rlBurst());
        if ($count > $limit) {
            return [true, 'medium', "Rate limit exceeded: {$count}>{$limit}"];
        }
        return [false, 'low', ''];
    }
}
