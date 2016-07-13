<?php

namespace Upg\Library\Request\Objects;

use Upg\Library\Validation\Helper\Constants;

/**
 * Class HostedPagesText
 * For hostedPagesText json Objects
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/json-objects
 * @package Upg\Library\Request\Objects
 */
class HostedPagesText extends AbstractObject
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
     * Payment method: Dummy
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/paymentmethods
     */
    const PAYMENT_METHOD_TYPE_DUMMY = "DUMMY";

    const TAG_PAYMENT_METHOD = "PAYMENT_METHOD_TYPE";

    /**
     * @var string Payment method type to change text on
     */
    private $paymentMethodType;

    /**
     * @var int Fee to be associated with a payment method. Must be given as an int
     */
    private $fee;

    /**
     * @var string Adtional text to be shown with the method on the payment page
     */
    private $description;

    /**
     * @var string Locale determind communication language e.g. for e-mails
     */
    private $locale;

    /**
     * Set the payment method must be certain values
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/paymentmethods
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_DD
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_CC
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_CC3D
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_PREPAID
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_PAYPAL
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_SU
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_BILL
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_BILL_SECURE
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_COD
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_IDEAL
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_INSTALLMENT
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_PAYCO_WALLET
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_DUMMY
     * @param $paymentMethodType The payment method you want to set a property for
     * @return $this
     */
    public function setPaymentMethodType($paymentMethodType)
    {
        $this->paymentMethodType = $paymentMethodType;
        return $this;
    }

    /**
     * Return the payment method that has been set
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/paymentmethods
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_DD
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_CC
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_CC3D
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_PREPAID
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_PAYPAL
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_SU
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_BILL
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_BILL_SECURE
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_COD
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_IDEAL
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_INSTALLMENT
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_PAYCO_WALLET
     * @see Upg\Library\Request\Objects\HostedPagesText::PAYMENT_METHOD_TYPE_DUMMY
     * @return string
     */
    public function getPaymentMethodType()
    {
        return $this->paymentMethodType;
    }

    /**
     * Set the fee as the lowest currency unit, ie cents, pence etc
     * @param int $fee
     * @return $this
     */
    public function setFee($fee)
    {
        $this->fee = $fee;
        return $this;
    }

    /**
     * Return the fee that has been set
     * @return int
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * Set the description
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the locale
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/supported-languages
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_DE
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_EN
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_ES
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_FI
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_FR
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_IT
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_NL
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_TU
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_RU
     * @param $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Return the set Locale
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/supported-languages
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_DE
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_EN
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_ES
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_FI
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_FR
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_IT
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_NL
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_TU
     * @see Upg\Library\Request\Objects\HostedPagesText::LOCALE_RU
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    public function toArray()
    {
        return array(
            'paymentMethodType' => $this->getPaymentMethodType(),
            'fee' => $this->getFee(),
            'description' => $this->getDescription(),
            'locale' => $this->getLocale()
        );
    }

    public function getValidationData()
    {
        $validationData = array();

        $validationData['paymentMethodType'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "paymentMethodType is required"
        );

        $validationData['paymentMethodType'][] = array(
            'name' => 'Callback',
            'value' => get_class($this) . '::validatePaymentMethodType',
            'message' => "paymentMethodType must be certain values"
        );

        $validationData['fee'][] = array(
            'name' => 'Regex',
            'value' => '/^[0-9]{1,16}$/',
            'message' => "Fee must be a numeric non decimal place value and no more than 16 characters"
        );

        $validationData['description'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "Description is required"
        );

        $validationData['description'][] = array(
            'name' => 'MaxLength',
            'value' => '255',
            'message' => "Description must be no more than 255 characters long"
        );

        $validationData['locale'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "Locale is required"
        );

        $validationData['locale'][] = array(
            'name' => 'Callback',
            'value' => 'Upg\Library\Locale\Codes::validateLocale',
            'message' => "Locale must be certain values"
        );

        return $validationData;
    }

    public static function validatePaymentMethodType($value)
    {
        return Constants::validateConstant(__CLASS__, $value, static::TAG_PAYMENT_METHOD);
    }
}
