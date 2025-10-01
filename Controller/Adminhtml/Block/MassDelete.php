<?php
declare(strict_types=1);

namespace Merlin\IntrusionDetection\Controller\Adminhtml\Block;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\Result\RedirectFactory;

class MassDelete extends Action
{
    public const ADMIN_RESOURCE = 'Merlin_IntrusionDetection::blocks';

    public function __construct(
        Action\Context $context,
        private readonly ResourceConnection $rc,
        private readonly RedirectFactory $resultRedirectFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $ids = (array)($this->getRequest()->getParam('selected') ?? []);
        $table = $this->rc->getTableName('merlin_blocked_ip');

        if ($ids) {
            $this->rc->getConnection()->delete($table, ['block_id IN (?)' => $ids]);
            $this->messageManager->addSuccessMessage(__('Deleted %1 record(s).', count($ids)));
        } else {
            $this->messageManager->addNoticeMessage(__('No records selected.'));
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
