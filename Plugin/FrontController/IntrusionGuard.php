<?php
namespace Merlin\IntrusionDetection\Plugin\FrontController;
use Magento\Framework\App\FrontControllerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\App\State;
use Merlin\IntrusionDetection\Model\Config;
use Merlin\IntrusionDetection\Model\EventLogger;
use Merlin\IntrusionDetection\Model\BlockService;
class IntrusionGuard
{
    /** @var \Merlin\IntrusionDetection\Api\DetectorInterface[] */
    private array $detectors;
    public function __construct(private Config $config, private EventLogger $logger, private BlockService $blockService, private RawFactory $rawFactory, private State $appState, array $detectors = []){ $this->detectors=$detectors; }
    public function aroundDispatch(FrontControllerInterface $subject, callable $proceed, RequestInterface $request)
    {
        if (!$this->config->isEnabled()) { return $proceed($request); }
        try { if ($this->appState->getAreaCode()==='adminhtml') { return $proceed($request); } } catch(\Exception $e){}
        $ip=$request->getServer('REMOTE_ADDR') ?? ''; $path=$request->getRequestUri() ?? $request->getPathInfo(); $ua=(string)($request->getServer('HTTP_USER_AGENT') ?? '');
        $hits=[]; foreach($this->detectors as $d){ [$hit,$sev,$det]= $d->inspect($request); if($hit){ $this->logger->log($d->getName(),$sev,$ip,(string)$path,$ua,$det); $hits[]=[$d->getName(),$sev]; } }
        if ($hits && $this->config->mode()!=='detect') {
            foreach($hits as [$name,$sev]){ if(in_array($sev,['high','critical'],true)){ $this->blockService->block($ip,'Auto by '.$name,60); break; } }
            $r=$this->rawFactory->create(); $r->setHttpResponseCode(403); $r->setHeader('X-Merlin-IDS','blocked',true); $r->setContents('Forbidden'); return $r;
        }
        return $proceed($request);
    }
}
