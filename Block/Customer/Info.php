<?php
namespace Mageplaza\GiftCard\Block\Customer;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Pricing\PriceCurrencyInterface;
class Info extends \Magento\Framework\View\Element\Template{
    /** @var PriceCurrencyInterface $priceCurrency */
    protected $priceCurrency;
    protected $_customerFactory;

    public function __construct(
        Context                               $context,
        PriceCurrencyInterface $priceCurrency,
        \Magento\Customer\Model\Session $customerSession,
        \Mageplaza\GiftCard\Model\CustomerFactory $customerFactory
    )
    {
        $this->priceCurrency = $priceCurrency;
        $this->_customerFactory = $customerFactory;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    public function getConfig(){
        $value = \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Framework\App\Config\ScopeConfigInterface::class)
            ->getValue(
                'giftcard/general/allow_redeem',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            );
        return $value;
    }
    public function getBalance()
    {
        $id = $this->customerSession->getCustomer()->getId();
        $data = $this->_customerFactory->create();
        $data->load($id);//lay ra id
        $balance = $data->getData('giftcard_balance');
        return $this->priceCurrency->convertAndFormat($balance);

//        $collection = $post->getCollection();//lay ra danh sach: select *from...
//        echo get_class($collection);
//        echo $collection->Xyz();
//        echo $collection->getSelect()->__toString();
//        echo "<pre>";
//        print_r($collection->getData());
//        echo "</pre>";
    }
};
