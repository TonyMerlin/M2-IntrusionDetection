<?php
namespace Merlin\IntrusionDetection\Model;

use Merlin\IntrusionDetection\Model\EventLogFactory;
use Merlin\IntrusionDetection\Model\ResourceModel\EventLog as EventResource;

class EventLogger
{
    public function __construct(
        private EventLogFactory $eventFactory,
        private EventResource $eventResource
    ) {}

    public function log(string $detector, string $severity, string $ip, string $path, ?string $ua, ?string $details = null): void
    {
        $event = $this->eventFactory->create();
        $event->setData([
            'ip' => $ip,
            'path' => $path,
            'user_agent' => $ua,
            'detector' => $detector,
            'severity' => $severity,
            'details' => $details,
        ]);
        $this->eventResource->save($event);
    }
}
