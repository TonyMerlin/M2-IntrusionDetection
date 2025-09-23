<?php
namespace Merlin\IntrusionDetection\Model\Detection;

use Merlin\IntrusionDetection\Api\DetectorInterface;
use Merlin\IntrusionDetection\Model\Config;
use Magento\Framework\App\RequestInterface;

class HoneypotDetector implements DetectorInterface
{
    public function __construct(private Config $config) {}
    public function getName(): string { return 'honeypot'; }

    public function inspect(RequestInterface $request): array
    {
        $hp = '/' . trim($this->config->hpUrl(), '/');
        $uri = '/' . ltrim($request->getRequestUri() ?? '', '/');
        if ($this->config->hpEnabled() && str_starts_with($uri, $hp)) {
            return [true, 'high', 'Honeypot trip'];
        }
        return [false, 'low', ''];
    }
}
