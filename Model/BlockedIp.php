<?php
namespace Merlin\IntrusionDetection\Model;
use Magento\Framework\Model\AbstractModel;
class BlockedIp extends AbstractModel
{
protected function _construct()
{
$this->_init(\Merlin\IntrusionDetection\Model\ResourceModel\BlockedIp::class);
}
}
