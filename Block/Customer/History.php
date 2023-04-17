<?php
namespace Mageplaza\GiftCard\Block\Customer;
use Magento\Framework\Pricing\PriceCurrencyInterface;
class History extends \Magento\Framework\View\Element\Template{
    protected $_historyFactory;
    protected $_codeFactory;
    protected $priceCurrency;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Mageplaza\GiftCard\Model\HistoryFactory $historyFactory,
        \Magento\Customer\Model\Session $customerSession,
        PriceCurrencyInterface $priceCurrency,
        \Mageplaza\GiftCard\Model\CodeFactory $codeFactory
    )
    {
        $this->_codeFactory = $codeFactory;
        $this->priceCurrency = $priceCurrency;
        $this->_historyFactory = $historyFactory;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    public function getCodeModel($id){
        $code = $this->_codeFactory->create();
        return $code->load($id);
    }
    public function getHistoryCollection(){
        $post = $this->_historyFactory->create();
        return $post->getCollection()->addFieldToFilter('entity_id', $this->customerSession->getCustomer()->getId());
    }
    public function getCurrency($currency){
        return $this->priceCurrency->convertAndFormat($currency);
    }
};
