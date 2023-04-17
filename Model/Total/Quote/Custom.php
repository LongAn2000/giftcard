<?php
namespace Mageplaza\GiftCard\Model\Total\Quote;
/**
 * Class Custom
 * @package Mageplaza\GiftCard\Model\Total\Quote
 */
class Custom extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $_priceCurrency;
    protected $_checkoutSession;
    protected $_codeFactory;
    protected $balanceToUse = 0;
    /**
     * Custom constructor.
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Mageplaza\GiftCard\Model\CodeFactory             $codeFactory,
        \Magento\Checkout\Model\Session $checkoutSession
    ){
        $this->_priceCurrency = $priceCurrency;
        $this->_checkoutSession = $checkoutSession;
        $this->_codeFactory = $codeFactory;
    }
    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this|bool
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total,
    )
    {
        parent::collect($quote, $shippingAssignment, $total);
        $code = $this->_checkoutSession->getData('coupon_code');
        $codeModel = $this->_codeFactory->create();
        $data = $codeModel->load($code,'code');
        $balance = $data->getBalance();
        if ($balance) {
            if($balance < $quote->getSubtotal()){
                $customDiscount = -$balance;
                $total->addTotalAmount('customdiscount', $customDiscount);
                $total->addBaseTotalAmount('customdiscount', $customDiscount);
                $total->setCustomDiscount( $customDiscount);
                $quote->setCustomDiscount($customDiscount);
            }
            else {
                $customDiscount = - $quote->getSubtotal() + 10;
                $this->balanceToUse = $customDiscount;
                $total->addTotalAmount('customdiscount', $customDiscount);
                $total->addBaseTotalAmount('customdiscount', $customDiscount);
                $total->setCustomDiscount( $customDiscount);
                $quote->setCustomDiscount($customDiscount);
            }
        }
        return $this;

    }

    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $code = $this->_checkoutSession->getData('coupon_code');
        $codeModel = $this->_codeFactory->create();
        $data = $codeModel->load($code,'code');
        $balance = $data->getBalance();
        if($balance < $quote->getSubtotal()){
            return [
                'code' => 'giftcard_code',
                'value' => $balance
            ];
        }
        else{
            return [
                'code' => 'giftcard_code',
                'value' => $this->balanceToUse,
            ];
        }

    }

}
