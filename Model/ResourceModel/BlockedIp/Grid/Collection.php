<?php
declare(strict_types=1);

namespace Merlin\IntrusionDetection\Model\ResourceModel\BlockedIp\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\Document;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Merlin\IntrusionDetection\Model\ResourceModel\BlockedIp as BlockedIpResource;

class Collection extends SearchResult
{
    protected function _construct()
    {
        $this->_init(Document::class, BlockedIpResource::class);
        $this->setMainTable('merlin_blocked_ip');

        $this->addFilterToMap('ip', 'main_table.ip');
        $this->addFilterToMap('created_at', 'main_table.created_at');
        $this->addFilterToMap('expires_at', 'main_table.expires_at');
    }
}
