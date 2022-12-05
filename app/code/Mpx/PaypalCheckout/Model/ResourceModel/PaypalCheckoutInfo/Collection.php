<?php

namespace Mpx\PaypalCheckout\Model\ResourceModel\PaypalCheckoutInfo;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mpx\PaypalCheckout\Model\PaypalCheckoutInfo;

/**
 * class Collection
 * Get Data DB
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     *  Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(PaypalCheckoutInfo::class, \Mpx\PaypalCheckout\Model\ResourceModel\PaypalCheckoutInfo::class);
    }
}
