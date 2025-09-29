<?php
declare(strict_types=1);

namespace Merlin\IntrusionDetection\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config {
    private $scopeConfig;
    public function __construct(ScopeConfigInterface $scopeConfig){ $this->scopeConfig = $scopeConfig; }
    private function get(string $path){ return $this->scopeConfig->getValue('merlin_intrusion/'.$path, ScopeInterface::SCOPE_STORE); }
    public function isEnabled(): bool { return (bool)$this->get('general/enabled'); }
    public function mode(): string { return (string)($this->get('general/mode') ?: 'detect'); }
    public function rlEnabled(): bool { return (bool)$this->get('ratelimit/enabled'); }
    public function rlWindow(): int { return (int)($this->get('ratelimit/window_seconds') ?: 60); }
    public function rlMax(): int { return (int)($this->get('ratelimit/max_requests') ?: 120); }
    public function hpEnabled(): bool { return (bool)$this->get('honeypot/enabled'); }
    public function hpUrl(): string { return (string)($this->get('honeypot/url') ?: '/_hp'); }
}
