<?php

namespace Upg\Library\Request\Objects;

use Upg\Library\Validation\Helper\Constants;

/**
 * Class PaymentInstrument
 * For paymentInstrument Json objects
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/json-objects
 * @package Upg\Library\Request\Objects
 */
class PaymentInstrument extends AbstractObject
{

    /**
     * Payment Instrument Type value: For payments instrument that is an bank account
     */
    const PAYMENT_INSTRUMENT_TYPE_BANK = 'BANKACCOUNT';

    /**
     * Payment Instrument Type value: For payment instrument that is an card
     */
    const PAYMENT_INSTRUMENT_TYPE_CARD = 'CREDITCARD';

    /**
     * Issuer Type: For Visa cards
     */
    const ISSUER_VISA = 'VISA';

    /**
     * Issuer Type: For Mastercard cards
     */
    const ISSUER_MC = 'MC';

    /**
     * Issuer Type: For American Express cards
     */
    const ISSUER_AMEX = 'AMEX';

    /**
     * @var string The unique ID of the payment instrument. Created by PayCo so in requests can be blank
     */
    private $paymentInstrumentID;

    /**
     * @var string The type of payment instrument Must be 'BANKACCOUNT' or 'CREDITCARD'
     */
    private $paymentInstrumentType;

    /**
     * @var string The account holder of the payment instrument
     */
    private $accountHolder;

    /**
     * @var string The credit Card Number only set for CREDITCARD
     */
    private $number;

    /**
     * @var \DateTime The expiry in date for CREDITCARD
     */
    private $validity;

    /**
     * @var string The card issuer only set for CREDITCARD and must be VISA,MC,AMEX
     */
    private $issuer;

    /**
     * @var string IBAN of a bank account only used for BANKACCOUNT
     */
    private $iban;

    /**
     * @var string BIC of a bank account only used for BANKACCOUNT
     */
    private $bic;

    /**
     * Set the ID for the payment instrument. For new payment instruments please leave this field blank
     * @param string $paymentInstrumentID
     * @return $this
     */
    public function setPaymentInstrumentID($paymentInstrumentID)
    {
        $this->paymentInstrumentID = $paymentInstrumentID;
        return $this;
    }

    /**
     * Get the payment instrument
     * @return string
     */
    public function getPaymentInstrumentID()
    {
        return $this->paymentInstrumentID;
    }

    /**
     * Set Payment Instrument only certain values can be set
     * @see Upg\Library\Request\Objects\PaymentInstrument::PAYMENT_INSTRUMENT_TYPE_BANK
     * @see Upg\Library\Request\Objects\PaymentInstrument::PAYMENT_INSTRUMENT_TYPE_CARD
     * @param string $paymentInstrumentType
     * @return $this
     */
    public function setPaymentInstrumentType($paymentInstrumentType)
    {
        $this->paymentInstrumentType = $paymentInstrumentType;
        return $this;
    }

    /**
     * Get the payment instrument Type
     * @return string
     */
    public function getPaymentInstrumentType()
    {
        return $this->paymentInstrumentType;
    }

    /**
     * Set the account holer of the payment instrument
     * @param string $accountHolder
     * @return $this
     */
    public function setAccountHolder($accountHolder)
    {
        $this->accountHolder = $accountHolder;
        return $this;
    }

    /**
     * Get the account holder
     * @return string
     */
    public function getAccountHolder()
    {
        return $this->accountHolder;
    }

    /**
     * Set the account number for card transaction
     * @param $number
     * @return string
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * Return the number
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set the expiry date for CREDITCARD transactions
     * @param \DateTime $validity
     * @return $this
     */
    public function setValidity(\DateTime $validity)
    {
        $this->validity = $validity;
        return $this;
    }

    /**
     * Return the expiry date
     * @return \DateTime
     */
    public function getValidity()
    {
        return $this->validity;
    }

    /**
     * Set the issuer for CREDITCARD transactions please see the links for possible values
     * @see Upg\Library\Request\Objects\PaymentInstrument::ISSUER_VISA
     * @see Upg\Library\Request\Objects\PaymentInstrument::ISSUER_MC
     * @see Upg\Library\Request\Objects\PaymentInstrument::ISSUER_AMEX
     * @param string $issuer
     * @return $this
     */
    public function setIssuer($issuer)
    {
        $this->issuer = $issuer;
        return $this;
    }

    /**
     * Get the credit card issuer see the links for possible values
     * @see Upg\Library\Request\Objects\PaymentInstrument::ISSUER_VISA
     * @see Upg\Library\Request\Objects\PaymentInstrument::ISSUER_MC
     * @see Upg\Library\Request\Objects\PaymentInstrument::ISSUER_AMEX
     * @return string
     */
    public function getIssuer()
    {
        return $this->issuer;
    }

    /**
     * Set the IBAN for BANKACCOUNT instruments
     * @param string $iban
     * @return $this
     */
    public function setIban($iban)
    {
        $this->iban = $iban;
        return $this;
    }

    /**
     * Get the IBAN for BANKACCOUNT instruments
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * Set the BIC for BANKACCOUNT instruments
     * @param $bic
     * @return $this
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
    }

    /**
     * Get the BIC for BANKACCOUNT instruments
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Return the array for validation
     * @return array
     */
    public function toArray()
    {
        $return = array(
            'paymentInstrumentType' => $this->getPaymentInstrumentType(),
            'accountHolder' => $this->getAccountHolder(),
        );

        if ($this->getPaymentInstrumentType() == static::PAYMENT_INSTRUMENT_TYPE_BANK) {
            $return['iban'] = $this->getIban();
            $return['bic'] = $this->getBic();
        }

        if ($this->getPaymentInstrumentType() == static::PAYMENT_INSTRUMENT_TYPE_CARD) {
            $return['number'] = $this->getNumber();
            $return['validity'] = (empty($this->validity) ? '' : $this->getValidity()->format("Y-m"));
            $return['issuer'] = $this->getIssuer();
        }

        if ($this->paymentInstrumentID) {
            $return['paymentInstrumentID'] = $this->getPaymentInstrumentID();
        }

        return $return;
    }

    /**
     * @return array
     */
    public function getValidationData()
    {
        $validationData = array();

        $validationData['paymentInstrumentType'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "PaymentInstrumentType is required"
        );

        $validationData['paymentInstrumentType'][] = array(
            'name' => 'Callback',
            'value' => get_class($this) . '::validatePaymentInstrumentType',
            'message' => "PaymentInstrumentType must be certain values"
        );

        $validationData['accountHolder'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "AccountHolder is required"
        );

        $validationData['accountHolder'][] = array(
            'name' => 'MaxLength',
            'value' => '50',
            'message' => "AccountHolder must be less than or equal to 50 characters"
        );

        $validationData['number'][] = array(
            'name' => 'MaxLength',
            'value' => '16',
            'message' => "Number must be less than or equal to 16 characters"
        );

        $validationData['issuer'][] = array(
            'name' => 'Callback',
            'value' => get_class($this) . '::validateIssuerType',
            'message' => "PaymentInstrumentType must be certain values"
        );

        $validationData['iban'][] = array(
            'name' => 'MaxLength',
            'value' => '34',
            'message' => "Iban must be no more than 34 characters"
        );

        $validationData['bic'][] = array(
            'name' => 'Regex',
            'value' => '/^[a-zA-Z0-9]{1,11}$/',
            'message' => "Bic must be 11 characters long and contain alphanumeric characters"
        );

        return $validationData;
    }

    /**
     * Validate that required fields are set for each payment instrument type
     * @return array
     */
    public function customValidation()
    {
        $validationArray = array();

        if ($this->getPaymentInstrumentType() == static::PAYMENT_INSTRUMENT_TYPE_BANK) {
            if (empty($this->iban)) {
                $validationArray[__CLASS__]['iban'] = array("For bank payments iban must be set");
            }

            if (empty($this->bic)) {
                $validationArray[__CLASS__]['bic'] = array("For bank payments bic must be set");
            }
        }

        if ($this->getPaymentInstrumentType() == static::PAYMENT_INSTRUMENT_TYPE_CARD) {
            if (empty($this->number)) {
                $validationArray[__CLASS__]['number'] = array("For card payments number must be set");
            }

            if (empty($this->validity)) {
                $validationArray[__CLASS__]['validity'] = array("For card payments validity must be set");
            }

            if (empty($this->issuer)) {
                $validationArray[__CLASS__]['issuer'] = array("For card payments issuer must be set");
            }
        }

        return $validationArray;
    }

    public static function validatePaymentInstrumentType($value)
    {
        return Constants::validateConstant(__CLASS__, $value, 'PAYMENT_INSTRUMENT_TYPE');
    }

    public static function validateIssuerType($value)
    {
        return Constants::validateConstant(__CLASS__, $value, 'ISSUER');
    }
}
