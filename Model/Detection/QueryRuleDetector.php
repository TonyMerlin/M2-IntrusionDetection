<?php
namespace Merlin\IntrusionDetection\Model\Detection;

use Merlin\IntrusionDetection\Api\DetectorInterface;
use Magento\Framework\App\RequestInterface;

class QueryRuleDetector implements DetectorInterface
{
    public function getName(): string { return 'query_rule'; }

    public function inspect(RequestInterface $request): array
    {
        $q = (string)($request->getRequestUri() ?? '');
        $patterns = [
            "(?i)(union\s+select)",
            "(?i)(information_schema)",
            "(?i)(/etc/passwd)",
            "(?i)(<script|%3Cscript)",
            "(?i)(\bOR\b\s+1=1)",
        ];
        foreach ($patterns as $re) {
            if (@preg_match("/$re/", $q) && preg_match("/$re/", $q)) {
                return [true, 'critical', 'Payload match: ' . $re];
            }
        }
        return [false, 'low', ''];
    }
}
