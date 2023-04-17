<?php
namespace Mageplaza\GiftCard\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Quote\Model\Quote;

class CreateGiftCard implements ObserverInterface
{
    protected $_checkoutSession;
    protected $_codeFactory;
    protected $_historyFactory;
    protected $newCode;
    public function __construct(
        \Mageplaza\GiftCard\Model\CodeFactory $codeFactory,
        \Mageplaza\GiftCard\Model\HistoryFactory $historyFactory,
        \Magento\Checkout\Model\Session                   $checkoutSession
    )
    {
        $this->_historyFactory = $historyFactory;
        $this->_codeFactory = $codeFactory;
        $this->_checkoutSession = $checkoutSession;
    }

    public function setCode($length){
        return $this->newCode = substr(str_shuffle('ABCDEFGHIJKLMLOPQRSTUVXYZ0123456789'),0 , $length);
    }
    public function execute(Observer $observer)
    {
        /** @var Quote $quote */
        $quote = $observer->getEvent()->getData('quote');
        $couponCode = $this->_checkoutSession->getData('coupon_code');
        $code = $this->_codeFactory->create();
        $history = $this->_historyFactory->create();
        if($couponCode){
            $code->load($couponCode, 'code');
            $discount = $quote->getCustomDiscount();
            $code->setBalance($code->getBalance() + $discount);
            $code->setAmountUsed($code->getAmountUsed() - $discount);
            $code->save();
            $datahistory = [
                'giftcard_id' => $code->getGiftcardId(),
                'entity_id' => $quote->getCustomerId(),
                'amount'=> $code->getAmountUsed(),
                'action' => 'Used for order '. $quote->getReservedOrderId()
            ];
            $history->addData($datahistory)->save();
            $this->_checkoutSession->setData('coupon_code', '');
        }
        $items = $quote->getItems();

        foreach ($items as $item) {
            for($i = 0; $i < $quote->getItemsQty(); $i++){
                if($item->getIsVirtual() == "1"){
//                    $codeModel = $this->_codeFactory->create();
//                    $historyModel = $this->_historyFactory->create();
                    $data = [
                        'code' => $this->setCode(\Magento\Framework\App\ObjectManager::getInstance()
                            ->get(\Magento\Framework\App\Config\ScopeConfigInterface::class)
                            ->getValue(
                                'giftcard/code_config/length',
                                \Magento\Store\Model\ScopeInterface::SCOPE_STORE)),
                        'balance'=> $item->getPrice(),
                        'create_from' => $quote->getReservedOrderId()
                    ];
                    $code->addData($data)->save();
                    $data2 = [
                        'giftcard_id' => $code->getId(),
                        'entity_id' => $quote->getCustomerId(),
                        'amount'=> $code->getBalance(),
                        'action' => 'Get from order '. $quote->getReservedOrderId()
                    ];
                    $history->addData($data2)->save();
                }
            }
        }
    }
}
