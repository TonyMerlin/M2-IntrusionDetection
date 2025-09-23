<?php
namespace Merlin\IntrusionDetection\Model;
use Magento\Framework\Model\AbstractModel;
class EventLog extends AbstractModel
{
protected function _construct()
{
$this->_init(\Merlin\IntrusionDetection\Model\ResourceModel\EventLog::class);
}
}
