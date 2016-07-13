<?php

namespace Upg\Library\Request\Objects;

/**
 * Class Amount
 * For the Amount json objects
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/json-objects
 * @package Upg\Library\Request\Objects
 */
class Amount extends AbstractObject
{

    /**
     * @var int Amount in smallest possible denomination ie cents, pence etc
     */
    private $amount;

    /**
     * @var int VAT\Tax amount in smallest possible denomination ie cents, pence etc
     */
    private $vatAmount;

    /**
     * @var float VAT\Tax rate in percentage to two decimal places
     */
    private $vatRate;

    public function __construct($amount = 0, $vatAmount = 0, $vatRate = 0)
    {
        if ($amount >= 0) {
            $this->setAmount($amount);
            $this->setVatAmount($vatAmount);
            $this->setVatRate($vatRate);
        }
    }

    /**
     * Set the amount for the object
     *
     * @param int $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Get the amount that has been set
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set the vat amount
     *
     * @param int $vatAmount
     * @return $this
     */
    public function setVatAmount($vatAmount)
    {
        $this->vatAmount = $vatAmount;
        return $this;
    }

    /**
     * Return the vat amount that has been set
     *
     * @return int
     */
    public function getVatAmount()
    {
        return $this->vatAmount;
    }

    /**
     * Set the vat rate
     *
     * @param float $vatRate
     * @return $this
     */
    public function setVatRate($vatRate)
    {
        $this->vatRate = $vatRate;
        return $this;
    }

    public function getVatRate()
    {
        return $this->vatRate;
    }

    public function toArray()
    {
        $return = array(
            'amount' => $this->amount,
        );

        if ($this->vatAmount) {
            $return['vatAmount'] = $this->vatAmount;
        }

        if ($this->vatRate) {
            $return['vatRate'] = $this->vatRate;
        }

        return $return;
    }

    /**
     * @see http://www.manula.com/manuals/payco/payment-api/2.0/en/topic/json-objects
     * @return array
     */
    public function getValidationData()
    {
        $validationData = array();

        $validationData['amount'][] = array('name' => 'required', 'value' => null, 'message' => "Amount is required");
        $validationData['amount'][] = array(
            'name' => 'Integer',
            'value' => null,
            'message' => "Amount must be an integer"
        );
        $validationData['amount'][] = array(
            'name' => 'Regex',
            'value' => '/^\d{0,16}$/',
            'message' => "Amount must be between 1 and 16 digits"
        );

        $validationData['vatAmount'][] = array(
            'name' => 'Integer',
            'value' => null,
            'message' => "VatAmount must be an integer"
        );
        $validationData['vatAmount'][] = array(
            'name' => 'Regex',
            'value' => '/^\d{0,16}$/',
            'message' => "VatAmount must be between 1 and 16 digits"
        );

        $validationData['vatRate'][] = array(
            'name' => 'Number',
            'value' => null,
            'message' => "VatRate must be an number"
        );
        $validationData['vatRate'][] = array(
            'name' => 'Regex',
            'value' => '/^\d{1,2}.{0,1}\d{0,2}$/',
            'message' => "VatRate must be 1 to 2 digits followed by 1 to 2 decimal place"
        );

        return $validationData;
    }
}
