<?php
declare(strict_types=1);

namespace Merlin\IntrusionDetection\Controller\Adminhtml\Block;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Merlin\IntrusionDetection\Model\BlockedIpFactory;

class Edit extends Action {
    const ADMIN_RESOURCE = 'Merlin_IntrusionDetection::blocks';
    private $pageFactory; private $factory;
    public function __construct(Action\Context $context, PageFactory $pageFactory, BlockedIpFactory $factory){ parent::__construct($context); $this->pageFactory=$pageFactory; $this->factory=$factory; }
    public function execute(){
        $id=(int)$this->getRequest()->getParam('block_id');
        if($id){ $m=$this->factory->create()->load($id); if(!$m->getId()){ $this->messageManager->addErrorMessage(__('This record no longer exists.')); return $this->_redirect('*/*/'); } }
        $p=$this->pageFactory->create(); $p->getConfig()->getTitle()->prepend($id?__('Edit Blocked IP'):__('New Blocked IP')); return $p;
    }
}
