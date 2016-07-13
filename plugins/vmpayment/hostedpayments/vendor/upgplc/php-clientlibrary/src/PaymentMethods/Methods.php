<?php

namespace Upg\Library\PaymentMethods;

use Upg\Library\Validation\Helper\Constants;

/**
 * Class PaymentMethods
 * Contains the payment methods and validator
 * @package Upg\Library\PaymentMethods
 */
class Methods
{
    /**
     * Payment method: Direct Debit
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/paymentmethods
     */
    const PAYMENT_METHOD_TYPE_DD = "DD";

    /**
     * Payment methods: Credit Card
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/paymentmethods
     */
    const PAYMENT_METHOD_TYPE_CC = "CC";

    /**
     * Payment method: Credit Card 3D Secure
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/paymentmethods
     */
    const PAYMENT_METHOD_TYPE_CC3D = "CC3D";

    /**
     * Payment method: Cash in advance
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/paymentmethods
     */
    const PAYMENT_METHOD_TYPE_PREPAID = "PREPAID";

    /**
     * Payment method: PayPal
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/paymentmethods
     */
    const PAYMENT_METHOD_TYPE_PAYPAL = "PAYPAL";

    /**
     * Payment method: Sofortüberweisung
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/paymentmethods
     */
    const PAYMENT_METHOD_TYPE_SU = "SU";

    /**
     * Payment method: Bill Payment
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/paymentmethods
     */
    const PAYMENT_METHOD_TYPE_BILL = "BILL";

    /**
     * Payment method: Bill Payment Secure
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/paymentmethods
     */
    const PAYMENT_METHOD_TYPE_BILL_SECURE = "BILL_SECURE";

    /**
     * Payment method: Cash on delivery
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/paymentmethods
     */
    const PAYMENT_METHOD_TYPE_COD = "COD";

    /**
     * Payment method: Ideal
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/paymentmethods
     */
    const PAYMENT_METHOD_TYPE_IDEAL = "IDEAL";

    /**
     * Payment method: Installment
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/paymentmethods
     */
    const PAYMENT_METHOD_TYPE_INSTALLMENT = "INSTALLMENT";

    /**
     * Payment method: PayCo Wallet
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/paymentmethods
     */
    const PAYMENT_METHOD_TYPE_PAYCO_WALLET = "PAYCO_WALLET";

    /**
     * Tag for the constraint validator
     */
    const VALIDATION_TAG_PAYMENT_METHOD = "PAYMENT_METHOD_TYPE";

    public static function validate($value)
    {
        return Constants::validateConstant(__CLASS__, $value, static::VALIDATION_TAG_PAYMENT_METHOD);
    }
}
