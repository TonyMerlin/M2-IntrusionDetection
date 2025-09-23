<?php
namespace Merlin\IntrusionDetection\Controller\Adminhtml\Block;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Merlin\IntrusionDetection\Model\BlockedIpFactory;
use Magento\Framework\Controller\ResultFactory;
class Delete extends Action
{
    const ADMIN_RESOURCE = 'Merlin_IntrusionDetection::blocks';
    public function __construct(Context $context, private BlockedIpFactory $factory){ parent::__construct($context); }
    public function execute(){
        $id=(int)$this->getRequest()->getParam('block_id');
        if($id){
            $m=$this->factory->create()->load($id);
            if($m->getId()){
                try{ $m->delete(); $this->messageManager->addSuccessMessage(__('The block was deleted.')); }
                catch(\Exception $e){ $this->messageManager->addErrorMessage($e->getMessage()); }
            }
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/');
    }
}
