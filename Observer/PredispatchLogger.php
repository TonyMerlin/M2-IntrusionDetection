<?php
declare(strict_types=1);

namespace Merlin\IntrusionDetection\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class PredispatchLogger implements ObserverInterface {
    private $eventFactory; private $eventResource; private $config;
    public function __construct(\Merlin\IntrusionDetection\Model\EventLogFactory $eventFactory, \Merlin\IntrusionDetection\Model\ResourceModel\EventLog $eventResource, \Merlin\IntrusionDetection\Model\Config $config){ $this->eventFactory=$eventFactory; $this->eventResource=$eventResource; $this->config=$config; }
    public function execute(Observer $observer){
        if (!$this->config->isEnabled()) return;
        $controller = $observer->getEvent()->getControllerAction(); if (!$controller) return;
        $request = $controller->getRequest();
        $m = $this->eventFactory->create();
        $m->setData(['ip'=>(string)($request->getServer('REMOTE_ADDR') ?? ''),'path'=>(string)($request->getRequestUri() ?? $request->getPathInfo() ?? ''),'user_agent'=>(string)($request->getServer('HTTP_USER_AGENT') ?? ''),'detector'=>'Predispatch','severity'=>'low','details'=>null]);
        try { $this->eventResource->save($m); } catch (\Exception $e) {}
    }
}
