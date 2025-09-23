<?php
namespace Merlin\IntrusionDetection\Model\Detection;

use Merlin\IntrusionDetection\Api\DetectorInterface;
use Magento\Framework\App\RequestInterface;

class PathAnomalyDetector implements DetectorInterface
{
    public function getName(): string { return 'path_anomaly'; }

    public function inspect(RequestInterface $request): array
    {
        $path = '/' . ltrim($request->getRequestUri() ?? ($request->getPathInfo() ?? ''), '/');
        $bad = ['../', '/.git', '/wp-admin', '/wp-login', '/phpmyadmin', '/.env', '/server-status'];
        foreach ($bad as $needle) {
            if (stripos($path, $needle) !== false) {
                return [true, 'high', 'Probing path: ' . $needle];
            }
        }
        return [false, 'low', ''];
    }
}
