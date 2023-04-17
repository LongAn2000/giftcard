<?php

namespace Mageplaza\GiftCard\Controller\Index;

use JMS\Serializer\Exception\Exception;
use Magento\Framework\App\Action\Context;

class Test extends \Magento\Framework\App\Action\Action
{
    protected $_codeFactory;

    public function __construct(
        Context                               $context,
        \Mageplaza\GiftCard\Model\CodeFactory $codeFactory
    )
    {
        $this->_codeFactory = $codeFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $code = $this->_codeFactory->create();
        $data = [
            'code' => "code4",
            'balance' => 15,
            'amount_used' => 12,
            'create_from' => 'admin'
        ];
        try {
            //them
//            $code->addData($data)->save();
            $code->load(3);
            if($code->getData('giftcard_id')){
                //sua
//                $code->setCode('code3')->save();
//                $code->setBalance('12')->save();
                $code->delete();
                echo 'Success';
            }else echo 'Id not exist';

        }catch (\Exception $e){
            echo 'Error';
        }
        //sua



    }
}
