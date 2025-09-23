<?php
namespace Merlin\IntrusionDetection\Model\ResourceModel\EventLog;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
class Collection extends AbstractCollection
{
protected function _construct()
{
$this->_init(\Merlin\IntrusionDetection\Model\EventLog::class, \Merlin\IntrusionDetection\Model\ResourceModel\EventLog::class);
}
}
