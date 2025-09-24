<?php
namespace Merlin\IntrusionDetection\Ui\DataProvider\BlockedIp;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Merlin\IntrusionDetection\Model\ResourceModel\BlockedIp\CollectionFactory;
class FormDataProvider extends AbstractDataProvider
{
    protected $loadedData;
    public function __construct($name,$primaryFieldName,$requestFieldName,CollectionFactory $collectionFactory,array $meta=[],array $data=[]){
        $this->collection=$collectionFactory->create(); parent::__construct($name,$primaryFieldName,$requestFieldName,$meta,$data);
    }
    public function getData(){
        if(isset($this->loadedData)) return $this->loadedData;
        $items=$this->collection->getItems();
        foreach($items as $item){ $this->loadedData[$item->getId()]=$item->getData(); }
        return $this->loadedData ?? [];
    }
}
