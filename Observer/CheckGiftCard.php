<?php

namespace Mageplaza\GiftCard\Observer;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ActionFlag;

class CheckGiftCard implements ObserverInterface
{
    protected $_messageManager;
    protected $_codeFactory;
    protected $_actionFlag;
    protected $_checkoutSession;
    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $redirect;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface       $messageManager,
        \Mageplaza\GiftCard\Model\CodeFactory             $codeFactory,
        \Magento\Framework\App\ActionFlag                 $actionFlag,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Checkout\Model\Session                   $checkoutSession
    )
    {
        $this->_messageManager = $messageManager;
        $this->_codeFactory = $codeFactory;
        $this->_actionFlag = $actionFlag;
        $this->redirect = $redirect;
        $this->_checkoutSession = $checkoutSession;

    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var Action $controller */
        $controller = $observer->getControllerAction();
        $couponCode = $controller->getRequest()->getParam('coupon_code');
        $code = $this->_codeFactory->create();
        $data = $code->load($couponCode, 'code');
        if (!empty($data->getCode())) {
            if($code->getBalance() > 0){
                $this->_messageManager->addSuccessMessage('Gift code applied successfully.');
                $this->_checkoutSession->setData('coupon_code', $couponCode);
                $this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
                $this->redirect->redirect($controller->getResponse(), 'checkout/cart/index');
                return;
            }
        }
        $this->_checkoutSession->setData('coupon_code', '');
    }
}
