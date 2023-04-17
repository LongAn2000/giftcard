<?php
namespace Mageplaza\GiftCard\Controller\Model;

use Magento\Framework\App\Action\Context;

class GetList extends \Magento\Framework\App\Action\Action
{
    protected $_codeFactory;

    public function __construct(
        Context $context,
        \Mageplaza\GiftCard\Model\CodeFactory $codeFactory
    )
    {
        $this->_codeFactory = $codeFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $code = $this->_codeFactory->create();
//        $code->load(2);
//        echo "<pre>";
//        print_r($post->getData());
//        echo "</pre>";
        $collection = $code->getCollection();
        echo $collection->getSelect()->__toString();
        echo "<pre>";
        print_r($collection->getData());
        echo "</pre>";

    }
}
