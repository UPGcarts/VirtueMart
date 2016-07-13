<?php

namespace Upg\Library\Request;

use Upg\Library\PaymentMethods\Methods as PaymentMethods;
use Upg\Library\Request\Objects\Amount;
use Upg\Library\Request\Objects\BasketItem;

/**
 * Class Capture
 * This is the request class for any capture request
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/capture
 * @package Upg\Library\Request
 */
class Capture extends AbstractRequest
{
    /**
     * This is the order number of the shop.
     * This id is created by the shop and is used as identifier for this transaction
     * @var string
     */
    private $orderID;

    /**
     * This is the unique reference of a capture or a partial capture (e.g. the invoice number)
     * @var string
     */
    private $captureID;

    /**
     * Amount for the capture
     * @var Amount
     */
    private $amount;

    /**
     * Set the Order ID
     * @see Capture::orderID
     * @param string $orderID
     * @return $this
     */
    public function setOrderID($orderID)
    {
        $this->orderID = $orderID;
        return $this;
    }

    /**
     * Get the set order ID
     * @see Capture::orderID
     * @return string
     */
    public function getOrderID()
    {
        return $this->orderID;
    }

    /**
     * Set the captureID field
     * @see Capture::captureID
     * @param $captureID
     * @return $this
     */
    public function setCaptureID($captureID)
    {
        $this->captureID = $captureID;
        return $this;
    }

    /**
     * Get the captureID field
     * @see Capture::captureID
     * @return string
     */
    public function getCaptureID()
    {
        return $this->captureID;
    }

    /**
     * Set the amount field
     * @see Capture::amount
     * @param Amount $amount
     * @return $this
     */
    public function setAmount(Amount $amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Get the amount field
     * @see Capture::amount
     * @return Amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get the serializer data
     * @return array
     */
    public function getPreSerializerData()
    {
        return array(
            'orderID' => $this->getOrderID(),
            'captureID' => $this->getCaptureID(),
            'amount' => $this->getAmount()
        );

    }

    /**
     * Get the validation
     * @return array
     */
    public function getClassValidationData()
    {
        $validationData = array();

        $validationData['orderID'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "orderID is required"
        );

        $validationData['orderID'][] = array(
            'name' => 'MaxLength',
            'value' => '30',
            'message' => "orderID must be between 1 and 30 characters"
        );

        $validationData['captureID'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "captureID is required"
        );

        $validationData['captureID'][] = array(
            'name' => 'MaxLength',
            'value' => '30',
            'message' => "captureID must be between 1 and 30 characters"
        );

        $validationData['amount'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "amount is required"
        );

        return $validationData;
    }
}
