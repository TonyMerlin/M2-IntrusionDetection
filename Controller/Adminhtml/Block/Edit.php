<?php
namespace Merlin\IntrusionDetection\Controller\Adminhtml\Block;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Merlin\IntrusionDetection\Model\BlockedIpFactory;
class Edit extends Action
{
    const ADMIN_RESOURCE = 'Merlin_IntrusionDetection::blocks';
    public function __construct(Context $context, private PageFactory $resultPageFactory, private BlockedIpFactory $factory){ parent::__construct($context); }
    public function execute(){ $id=(int)$this->getRequest()->getParam('block_id'); if($id){ $m=$this->factory->create()->load($id); if(!$m->getId()){ $this->messageManager->addErrorMessage(__('This block record no longer exists.')); return $this->_redirect('*/*/'); } } $p=$this->resultPageFactory->create(); $p->getConfig()->getTitle()->prepend($id?__('Edit Blocked IP'):__('New Blocked IP')); return $p; }
}
