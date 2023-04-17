<?php

namespace Mageplaza\GiftCard\Plugin;

use Magento\Quote\Model\Quote\TotalsCollector;
use Magento\Framework\View\Element\Template\Context;

class CouponPlugin2
{
    protected $_checkoutSession;
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession
    ) {

        $this->_checkoutSession = $checkoutSession;
    }

    public function afterGetCouponCode(\Magento\Checkout\Block\Cart\Coupon $subject, $result)
    {
        $code = $this->_checkoutSession->getData('coupon_code');
        if($code) {
            return $code;
        }else{
            return $result;
        }
    }
}

