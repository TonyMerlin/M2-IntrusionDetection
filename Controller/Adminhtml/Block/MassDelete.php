<?php
namespace Merlin\IntrusionDetection\Controller\Adminhtml\Block;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Merlin\IntrusionDetection\Model\ResourceModel\BlockedIp\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;
class MassDelete extends Action
{
    const ADMIN_RESOURCE = 'Merlin_IntrusionDetection::blocks';
    public function __construct(Context $context, private Filter $filter, private CollectionFactory $factory){ parent::__construct($context); }
    public function execute(){
        $c=$this->filter->getCollection($this->factory->create()); $n=0;
        foreach($c as $i){ $i->delete(); $n++; }
        $this->messageManager->addSuccessMessage(__('%1 record(s) were deleted.', $n));
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/');
    }
}
