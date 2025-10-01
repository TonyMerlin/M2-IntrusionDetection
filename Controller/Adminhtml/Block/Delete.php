<?php
declare(strict_types=1);

namespace Merlin\IntrusionDetection\Controller\Adminhtml\Block;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\Result\RedirectFactory;

class Delete extends Action
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
        $id = (int)$this->getRequest()->getParam('block_id');
        if ($id) {
            $this->rc->getConnection()->delete(
                $this->rc->getTableName('merlin_blocked_ip'),
                ['block_id = ?' => $id]
            );
            $this->messageManager->addSuccessMessage(__('IP removed.'));
        }
        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
