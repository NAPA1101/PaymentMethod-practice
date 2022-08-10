<?php

namespace Checkout\CustomPayment\Model;

use \Magento\Payment\Model\Method\AbstractMethod;

class PaymentMethod extends AbstractMethod
{

    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = 'custompayment';
}