<?php
namespace Merlin\IntrusionDetection\Controller\Adminhtml\Event;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
class Index extends Action
{
    const ADMIN_RESOURCE = 'Merlin_IntrusionDetection::events';
    public function __construct(Context $context, private PageFactory $resultPageFactory){ parent::__construct($context); }
    public function execute(){ $p=$this->resultPageFactory->create(); $p->setActiveMenu('Merlin_IntrusionDetection::events'); $p->getConfig()->getTitle()->prepend(__('Intrusion Events')); return $p; }
}
