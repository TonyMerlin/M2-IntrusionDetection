<?php
namespace Merlin\IntrusionDetection\Controller\Honeypot;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\RawFactory;

class Index extends Action
{
    public function __construct(Context $context, private RawFactory $rawFactory)
    { parent::__construct($context); }

    public function execute()
    {
        $result = $this->rawFactory->create();
        $result->setHttpResponseCode(404);
        $result->setContents('Not Found');
        return $result;
    }
}
