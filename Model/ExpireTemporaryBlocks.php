<?php
namespace Merlin\IntrusionDetection\Model\Cron;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Merlin\IntrusionDetection\Model\ResourceModel\BlockedIp\CollectionFactory;
use Merlin\IntrusionDetection\Model\ResourceModel\BlockedIp as BlockedRes;

class ExpireTemporaryBlocks
{
    public function __construct(private CollectionFactory $factory, private BlockedRes $res, private DateTime $date) {}

    public function execute(): void
    {
        $now = $this->date->gmtDate();
        $col = $this->factory->create();
        $col->addFieldToFilter('expires_at', ['lt' => $now]);
        foreach ($col as $item) { $this->res->delete($item); }
    }
}
