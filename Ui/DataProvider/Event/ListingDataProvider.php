<?php
namespace Merlin\IntrusionDetection\Ui\DataProvider\Event;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Merlin\IntrusionDetection\Model\ResourceModel\EventLog\CollectionFactory;
class ListingDataProvider extends AbstractDataProvider
{
    public function __construct($name,$primaryFieldName,$requestFieldName,CollectionFactory $collectionFactory,array $meta=[],array $data=[]){
        $this->collection=$collectionFactory->create(); parent::__construct($name,$primaryFieldName,$requestFieldName,$meta,$data);
    }
}
