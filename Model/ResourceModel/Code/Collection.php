<?php
namespace Mageplaza\GiftCard\Model\ResourceModel\Code;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'code_id';
    protected $_eventPrefix = 'mageplaza_giftcard_code_collection';
    protected $_eventObject = 'code_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mageplaza\GiftCard\Model\Code', 'Mageplaza\GiftCard\Model\ResourceModel\Code');
    }

}
