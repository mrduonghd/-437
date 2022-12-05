<?php

namespace Mpx\PaypalCheckout\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * class PaypalCheckoutInfo
 * Model PaypalCheckoutInfo
 */
class PaypalCheckoutInfo extends AbstractDb
{
    /**
     * @param Context $context
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init('paypal_checkout_info', 'id');
    }
}
