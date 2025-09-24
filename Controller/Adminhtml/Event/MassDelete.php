<?php
namespace Merlin\IntrusionDetection\Controller\Adminhtml\Event;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Merlin\IntrusionDetection\Model\ResourceModel\EventLog\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;
class MassDelete extends Action
{
    const ADMIN_RESOURCE = 'Merlin_IntrusionDetection::events';
    public function __construct(Context $context, private Filter $filter, private CollectionFactory $factory){ parent::__construct($context); }
    public function execute(){ $c=$this->filter->getCollection($this->factory->create()); $n=0; foreach($c as $i){ $i->delete(); $n++; } $this->messageManager->addSuccessMessage(__('%1 event(s) were deleted.', $n)); return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/'); }
}
