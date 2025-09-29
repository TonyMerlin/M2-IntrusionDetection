<?php
declare(strict_types=1);
namespace Merlin\IntrusionDetection\Plugin\FrontController;

use Magento\Framework\App\FrontControllerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\State;
use Magento\Backend\Helper\Data as BackendHelper;
use Merlin\IntrusionDetection\Model\Config;
use Merlin\IntrusionDetection\Model\EventLogger;
use Merlin\IntrusionDetection\Api\BlockServiceInterface;

class IntrusionGuard {
    private $config; private $logger; private $blockService; private $appState; private $backendHelper; private $response; private $detectors;
    public function __construct(Config $config, EventLogger $logger, BlockServiceInterface $blockService, State $appState, BackendHelper $backendHelper, ResponseInterface $response, array $detectors = []){
        $this->config=$config; $this->logger=$logger; $this->blockService=$blockService; $this->appState=$appState; $this->backendHelper=$backendHelper; $this->response=$response; $this->detectors=$detectors;
    }
    public function aroundDispatch(FrontControllerInterface $subject, callable $proceed, RequestInterface $request){
        if (!$this->config->isEnabled()) return $proceed($request);
        try { if ($this->appState->getAreaCode()==='adminhtml') return $proceed($request); } catch(\Exception $e){}
        $admin = trim((string)$this->backendHelper->getAreaFrontName(), '/');
        $uri = '/' . ltrim((string)($request->getRequestUri() ?? $request->getPathInfo() ?? ''), '/');
        if ($admin !== '' && strpos(ltrim($uri,'/'), $admin) === 0) return $proceed($request);
        $ip  = (string)($request->getServer('REMOTE_ADDR') ?? '');
        $path = (string)($request->getRequestUri() ?? $request->getPathInfo());
        $ua  = (string)($request->getServer('HTTP_USER_AGENT') ?? '');
        $hits = [];
        foreach ($this->detectors as $detector) {
            $res = $detector->inspect($request);
            $isHit = (bool)($res[0] ?? false);
            $sev   = (string)($res[1] ?? 'low');
            $det   = $res[2] ?? null;
            if ($isHit) { $this->logger->log($detector->getName(), $sev, $ip, $path, $ua, is_string($det)?$det:null); $hits[] = [$detector->getName(), $sev]; }
        }
        if ($hits && $this->config->mode() !== 'detect') {
            foreach ($hits as $h) { if (in_array($h[1], ['high','critical'], true)) { $this->blockService->block($ip, 'Auto by '.$h[0], 60); break; } }
            $this->response->setHttpResponseCode(403);
            if (method_exists($this->response,'setBody')) $this->response->setBody('Forbidden'); else if (method_exists($this->response,'setContent')) $this->response->setContent('Forbidden');
            return $this->response;
        }
        return $proceed($request);
    }
}
