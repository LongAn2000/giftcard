<?php
namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;

class Edit extends \Magento\Backend\App\Action
{

    protected $_resultPageFactory;

    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Mageplaza\GiftCard\Model\CodeFactory $codeFactory,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $coreRegistry;
        $this->codeFactory = $codeFactory;
        parent::__construct($context);
    }


    public function execute()
    {
        $codeModel = $this->codeFactory->create();
        $this->coreRegistry->register('giftcard_action', $codeModel);
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Code Manage')));
        return $resultPage;
    }
}
