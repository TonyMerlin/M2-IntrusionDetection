<?php
namespace Merlin\IntrusionDetection\Controller\Adminhtml\Block;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Merlin\IntrusionDetection\Model\BlockedIpFactory;
use Magento\Framework\Controller\ResultFactory;
class Save extends Action
{
    const ADMIN_RESOURCE = 'Merlin_IntrusionDetection::blocks';
    public function __construct(Context $context, private BlockedIpFactory $factory){ parent::__construct($context); }
    public function execute(){
        $d=$this->getRequest()->getPostValue();
        if(!$d){ return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/'); }
        $id=(int)($d['block_id']??0);
        $m=$this->factory->create(); if($id) $m->load($id);
        $m->addData(['ip'=>$d.get('ip',''),'reason'=>$d.get('reason'),'expires_at'=>($d.get('expires_at') or null)]);
        try{
            $m->save(); $this->messageManager->addSuccessMessage(__('You saved the block.'));
            if($this->getRequest()->getParam('back')){ return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/edit',['block_id'=>$m->getId()]); }
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/');
        } catch(\Exception $e){
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/edit',['block_id'=>$id]);
        }
    }
}
