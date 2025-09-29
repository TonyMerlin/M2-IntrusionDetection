<?php
declare(strict_types=1);

namespace Merlin\IntrusionDetection\Controller\Adminhtml\Block;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Merlin\IntrusionDetection\Model\BlockedIpFactory;

class Delete extends Action {
    const ADMIN_RESOURCE = 'Merlin_IntrusionDetection::blocks';
    private $factory;
    public function __construct(Action\Context $context, BlockedIpFactory $factory){ parent::__construct($context); $this->factory=$factory; }
    public function execute(){
        $id=(int)$this->getRequest()->getParam('block_id');
        if($id){ $m=$this->factory->create()->load($id); if($m->getId()){ try{ $m->delete(); $this->messageManager->addSuccessMessage(__('Deleted.')); } catch(\Exception $e){ $this->messageManager->addErrorMessage($e->getMessage()); } } }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/');
    }
}
