<?php
namespace Merlin\IntrusionDetection\Model\ResourceModel\BlockedIp;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
class Collection extends AbstractCollection
{
protected function _construct()
{
$this->_init(\Merlin\IntrusionDetection\Model\BlockedIp::class, \Merlin\IntrusionDetection\Model\ResourceModel\BlockedIp::class);
}
}
