<?php
namespace Merlin\IntrusionDetection\Model;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;


class Config
{
private const PATH = 'merlin_intrusion/';
public function __construct(private ScopeConfigInterface $scopeConfig) {}


public function isEnabled(): bool { return (bool)$this->get('general/enabled'); }
public function mode(): string { return (string)($this->get('general/mode') ?: 'detect'); }
public function logDetails(): bool { return (bool)$this->get('general/log_details'); }


public function rlEnabled(): bool { return (bool)$this->get('ratelimit/enabled'); }
public function rlWindow(): int { return (int)$this->get('ratelimit/window_seconds'); }
public function rlMax(): int { return (int)$this->get('ratelimit/max_requests'); }
public function rlBurst(): int { return (int)$this->get('ratelimit/burst_factor'); }


public function bfEnabled(): bool { return (bool)$this->get('bruteforce/enabled'); }
public function bfMaxFailures(): int { return (int)$this->get('bruteforce/max_failures'); }
public function bfLockMinutes(): int { return (int)$this->get('bruteforce/lock_minutes'); }


public function hpEnabled(): bool { return (bool)$this->get('honeypot/enabled'); }
public function hpUrl(): string { return (string)($this->get('honeypot/url') ?: 'merlin/honeypot'); }


private function get(string $path)
{
return $this->scopeConfig->getValue(self::PATH . $path, ScopeInterface::SCOPE_STORE);
}
}
