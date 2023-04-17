<?php
namespace Mageplaza\GiftCard\Controller\Adminhtml\Code;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Mageplaza\GiftCard\Model\ResourceModel\Code as CodeResource;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use const _PHPStan_582a9cb8b\__;


class Save extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    protected $newCode;
    public function __construct(
        Context $context,
        CodeResource $resource,
        \Mageplaza\GiftCard\Model\CodeFactory $codeFactory
    )
    {
        parent::__construct($context);
        $this->codeFactory = $codeFactory;
        $this->codeResource = $resource;
    }
    public function setCode($length){
        return $this->newCode = substr(str_shuffle('ABCDEFGHIJKLMLOPQRSTUVXYZ0123456789'),0 , $length);
    }

    public function execute(): ResultInterface
    {
        $data = $this->getRequest()->getParams();
//        echo '<pre>';
//        print_r($data);
//        echo '<pre>';
//        die();
        if(isset($data['code_length'])){
            $data['code'] = $this->setCode($data['code_length']);
            $data['create_from'] = 'admin';

        }
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data){
            $model = $this->codeFactory->create();
            $id = $this->getRequest()->getParam('giftcard_id');
            if($id){
                $model->load($id);
            }

            $model->setData($data);

            try {
//                $this->codeResource->save($model);
                $model->save();
                $this->messageManager->addSuccessMessage(__('Save Success.'));
                //check for `back` parameter
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('giftcard/code/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('giftcard/code/index');
            }catch (LocalizedException $exception){
                $this->messageManager->addExceptionMessage($exception);
            }catch (\Throwable $e){
                $this->messageManager->addErrorMessage(__('Not Success.'));
            }
        }
        return $resultRedirect->setPath('giftcard/code/index');
    }
}
