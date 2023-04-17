<?php

namespace Mageplaza\GiftCard\Block\Adminhtml\Code;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    protected $_coreRegistry;

    public function __construct(
        \Magento\Framework\Registry           $coreRegistry,
        \Magento\Backend\Block\Widget\Context $context,
        array                                 $data = []
    )
    {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }
    protected function _construct()
    {
        $this->_objectId = 'giftcard_id';//khai bao id
        $this->_blockGroup = 'Mageplaza_GiftCard';//ten module
        $this->_controller = 'adminhtml_code';//duong dan cua controller
        parent::_construct();

/////  for update btn name

        $this->buttonList->update('save', 'label', __('Save GiftCard'));

///// for add new custome btn

        $this->addButton('save_edit',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ],

                'sort_order' => 80,
            ]
        );
    }

}
