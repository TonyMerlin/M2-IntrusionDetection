<?php
declare(strict_types=1);

namespace Merlin\IntrusionDetection\Controller\Adminhtml\Block;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Merlin\IntrusionDetection\Model\BlockedIpFactory;

class Save extends Action {
    const ADMIN_RESOURCE = 'Merlin_IntrusionDetection::blocks';
    private $factory;
    public function __construct(Action\Context $context, BlockedIpFactory $factory){ parent::__construct($context); $this->factory=$factory; }
    public function execute(){
        $data=$this->getRequest()->getPostValue();
        if(!$data){ return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/'); }
        $id=(int)($data['block_id']??0); $m=$this->factory->create(); if($id){ $m->load($id); }
        $m->addData(['ip'=>$data['ip']??'','reason'=>$data['reason']??null,'expires_at'=>$data['expires_at']??null]);
        try{ $m->save(); $this->messageManager->addSuccessMessage(__('Saved.'));
            if($this->getRequest()->getParam('back')){ return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/edit',['block_id'=>$m->getId()]); }
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/');
        } catch(\Exception $e){ $this->messageManager->addErrorMessage($e->getMessage()); return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/edit',['block_id'=>$id]); }
    }
}
