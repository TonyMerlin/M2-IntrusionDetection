<?php
namespace Merlin\IntrusionDetection\Model;

use Magento\Framework\App\CacheInterface;

class RateLimiter
{
    private string $prefix = 'merlin_id_rl_';

    public function __construct(private CacheInterface $cache) {}

    /**
     * @return array [count, windowStart]
     */
    public function hit(string $key, int $window, int $limit): array
    {
        $cacheKey = $this->prefix . sha1($key . '|' . $window);
        $now = time();
        $payload = $this->cache->load($cacheKey);
        $data = $payload ? json_decode($payload, true) : ['start' => $now, 'count' => 0];
        if ($now - $data['start'] >= $window) { $data = ['start' => $now, 'count' => 0]; }
        $data['count']++;
        $this->cache->save(json_encode($data), $cacheKey, [], $window);
        return [$data['count'], $data['start']];
    }
}
