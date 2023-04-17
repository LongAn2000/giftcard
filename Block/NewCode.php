<?php
namespace Mageplaza\GiftCard\Block;
class NewCode extends \Magento\Framework\View\Element\Template
{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
    {
        parent::__construct($context);
    }

    public function createcode()
    {
        return __('Hello World');
    }
}
