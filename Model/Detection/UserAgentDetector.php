<?php
namespace Merlin\IntrusionDetection\Model\Detection;

use Merlin\IntrusionDetection\Api\DetectorInterface;
use Magento\Framework\App\RequestInterface;

class UserAgentDetector implements DetectorInterface
{
    public function getName(): string { return 'user_agent'; }

    public function inspect(RequestInterface $request): array
    {
        $ua = (string)($request->getServer('HTTP_USER_AGENT') ?? '');
        if ($ua === '' || strlen($ua) < 4) {
            return [true, 'medium', 'Empty or abnormal user agent'];
        }
        $bad = ['sqlmap', 'nikto', 'acunetix', 'nessus', 'wpscan'];
        foreach ($bad as $b) {
            if (stripos($ua, $b) !== false) {
                return [true, 'high', 'Scanner user agent'];
            }
        }
        return [false, 'low', ''];
    }
}
