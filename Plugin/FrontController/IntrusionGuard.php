<?php
namespace Merlin\IntrusionDetection\Plugin\FrontController;

use Magento\Framework\App\FrontControllerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RawFactory;
use Merlin\IntrusionDetection\Model\Config;
use Merlin\IntrusionDetection\Model\EventLogger;
use Merlin\IntrusionDetection\Model\BlockService;

class IntrusionGuard
{
    /** @var \Merlin\IntrusionDetection\Api\DetectorInterface[] */
    private array $detectors;

    public function __construct(
        private Config $config,
        private EventLogger $logger,
        private BlockService $blockService,
        private RawFactory $rawFactory,
        array $detectors = []
    ) {
        $this->detectors = $detectors;
    }

    public function aroundDispatch(FrontControllerInterface $subject, callable $proceed, RequestInterface $request)
    {
        if (!$this->config->isEnabled()) { return $proceed($request); }

        $ip = $request->getServer('REMOTE_ADDR') ?? '';
        $path = $request->getRequestUri() ?? $request->getPathInfo();
        $ua = (string)($request->getServer('HTTP_USER_AGENT') ?? '');

        $hits = [];
        foreach ($this->detectors as $detector) {
            [$isHit, $severity, $details] = $detector->inspect($request);
            if ($isHit) {
                $this->logger->log($detector->getName(), $severity, $ip, (string)$path, $ua, $details);
                $hits[] = [$detector->getName(), $severity];
            }
        }

        if ($hits && $this->config->mode() !== 'detect') {
            foreach ($hits as [$name, $sev]) {
                if (in_array($sev, ['high','critical'], true)) {
                    $this->blockService->block($ip, 'Auto by ' . $name, 60);
                    break;
                }
            }
            $result = $this->rawFactory->create();
            $result->setHttpResponseCode(403);
            $result->setHeader('X-Merlin-IDS', 'blocked', true);
            $result->setContents('Forbidden');
            return $result;
        }

        return $proceed($request);
    }
}
