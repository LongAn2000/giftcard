<?php
namespace Mageplaza\GiftCard\Block\Adminhtml\Code\Edit\Tab;

class Code extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $codeFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Mageplaza\GiftCard\Model\CodeFactory $codeFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    )
    {
        $this->codeFactory = $codeFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }


    protected function _prepareForm()
    {
        /** @var \Mageplaza\GiftCard\Model\ResourceModel $model  */
        $model = $this->_coreRegistry->registry('giftcard_action');
        $form = $this->_formFactory->create();
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Gift Card information')]);

        $idCode = $this->getRequest()->getParams();
        if(isset($idCode['id'])){
            $fieldset->addField('code', 'text', [
                'name'     => 'code',
                'label'    => __('Code'),
                'disabled' => true,
            ]);
            $fieldset->addField('balance', 'text', [
                'name'     => 'balance',
                'label'    => __('Balance'),
                'required' => true,
            ]);
            $fieldset->addField('create_from', 'text', [
                'name'     => 'create_from',
                'label'    => __('Create From'),
                'disabled' => true,
            ]);
            $fieldset->addField('giftcard_id', 'hidden', [
                'name'     => 'giftcard_id',
            ]);
            $code = $this->codeFactory->create();
            $code->load($idCode['id']);
            $model->setData('code',$code->getData('code'));
            $model->setData('giftcard_id',$idCode['id']);
            $model->setData('balance',$code->getData('balance'));
            $model->setData('create_from',$code->getData('create_from'));

        }
        else{
            $fieldset->addField('code_length', 'text', [
                'name'     => 'code_length',
                'label'    => __('Code Length'),
                'class' => 'validate-number',
                'required' => false,
            ]);
            $fieldset->addField('balance', 'text', [
                'name'     => 'balance',
                'label'    => __('Balance'),
                'required' => true,
            ]);
            $fieldset->addField('code', 'hidden', [
                'name'     => 'code',
            ]);
            $fieldset->addField('amount_used', 'hidden', [
                'name'     => 'amount_used',
            ]);
            $fieldset->addField('create_from', 'hidden', [
                'name'     => 'create_from',
            ]);
            $model->setData('code_length',\Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Framework\App\Config\ScopeConfigInterface::class)
                ->getValue(
                    'giftcard/code_config/length',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                ));
            $model->setData('balance','200');
        }

        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }


    public function getTabLabel()
    {
        return __('Gift card information');
    }

    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}
