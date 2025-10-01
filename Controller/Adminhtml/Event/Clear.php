<?php
declare(strict_types=1);

namespace Merlin\IntrusionDetection\Controller\Adminhtml\Event;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\Result\RedirectFactory;

class Clear extends Action
{
    public const ADMIN_RESOURCE = 'Merlin_IntrusionDetection::events';

    public function __construct(
        Action\Context $context,
        private readonly ResourceConnection $rc,
        private readonly RedirectFactory $resultRedirectFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $conn = $this->rc->getConnection();
        $conn->truncateTable($this->rc->getTableName('merlin_intrusion_event'));
        $this->messageManager->addSuccessMessage(__('Intrusion log cleared.'));
        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
