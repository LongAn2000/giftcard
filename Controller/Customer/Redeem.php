<?php
namespace Mageplaza\GiftCard\Controller\Customer;

class Redeem extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    protected $_codeFactory;
    protected $_historyFactory;
    protected $_customerFactory;
    protected $_customerSession;
    protected $_messageManager;
    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $_redirect;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface       $messageManager,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Mageplaza\GiftCard\Model\CodeFactory             $codeFactory,
        \Mageplaza\GiftCard\Model\HistoryFactory             $historyFactory,
        \Mageplaza\GiftCard\Model\CustomerFactory             $customerFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
    ) {
        parent::__construct($context);
        $this->_messageManager = $messageManager;
        $this->resultPageFactory = $resultPageFactory;
        $this->_codeFactory = $codeFactory;
        $this->_historyFactory = $historyFactory;
        $this->_customerFactory = $customerFactory;
        $this->_customerSession = $customerSession;
        $this->_redirect = $redirect;
    }

    public function execute()
    {
        $this->resultPage = $this->resultPageFactory->create();
        $code = $this->getRequest()->getParam('code');
        $codeModel = $this->_codeFactory->create();
        $historyModel = $this->_historyFactory->create();
        $customerModel = $this->_customerFactory->create();
        $codeData = $codeModel->load($code, 'code');
        $codeBalance = $codeModel->getBalance();
        if($codeData){
            if($codeBalance > 0){
                $idCustomer = $this->_customerSession->getId();
                $customerModel->load($idCustomer);
                $customerModel->setGiftcardBalance( $customerModel->getGiftcardBalance() + $codeBalance);
                $datahistory = [
                    'giftcard_id' => $codeModel->getGiftcardId(),
                    'entity_id' => $idCustomer,
                    'amount'=> $codeModel->getBalance(),
                    'action' => 'Redeem'
                ];
                $historyModel->addData($datahistory);
                $codeModel->setAmountUsed($codeModel->getBalance());
                $codeModel->setBalance(0);
            }
            else{
                $this->_messageManager->addErrorMessage('Code not valid');
                return $this->_redirect('giftcard/customer/index');
            }
        }
        try {
            $customerModel->save();
            $historyModel->save();
            $codeModel->save();
            $this->_messageManager->addSuccessMessage('Redeem successfully.');

        }catch (\Exception $e){
            $this->_messageManager->addErrorMessage('Not successfully');
        }
        return $this->_redirect('giftcard/customer/index');
    }
}
