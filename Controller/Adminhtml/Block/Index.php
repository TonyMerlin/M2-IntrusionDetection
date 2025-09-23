<?php
namespace Merlin\IntrusionDetection\Controller\Adminhtml\Block;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
class Index extends Action
{
    const ADMIN_RESOURCE = 'Merlin_IntrusionDetection::blocks';
    public function __construct(Context $context, private PageFactory $resultPageFactory){ parent::__construct($context); }
    public function execute(){ $p=$this->resultPageFactory->create(); $p->setActiveMenu('Merlin_IntrusionDetection::blocks'); $p->getConfig()->getTitle()->prepend(__('Blocked IPs')); return $p; }
}
