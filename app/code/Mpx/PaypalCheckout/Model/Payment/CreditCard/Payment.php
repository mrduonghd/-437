<?php

namespace Mpx\PaypalCheckout\Model\Payment\CreditCard;

/**
 * class Payment
 * Credit Card
 */
class Payment extends \Mpx\PaypalCheckout\Model\Payment\PaypalCheckout\Payment
{
    const CODE = 'paypalcc';

    protected $_code = self::CODE;
}
