<?php

namespace Upg\Library\Request;

use Upg\Library\PaymentMethods\Methods as PaymentMethods;
use Upg\Library\Request\Objects\Amount;
use Upg\Library\Request\Objects\BasketItem;
use Upg\Library\Request\Attributes\ObjectArray;

/**
 * Class CreateTransaction
 * This is the request class for any reserve request
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/reserve
 * @package Upg\Library\Request
 */
class Reserve extends AbstractRequest
{
    /**
     * This is the order number of the shop.
     * This id is created by the shop and is used as identifier for this transaction
     * @var string
     */
    private $orderID;

    /**
     * The PaymentMethod that should be used
     * @see \Upg\Library\PaymentMethods\Methods
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/paymentmethods
     * @var string
     */
    private $paymentMethod;

    /**
     * The unique id of the payment instrument. Required for payment method CC, CC3D, DD.
     * @var string
     */
    private $paymentInstrumentID;

    /**
     * the amount of the basket. If this field is set,
     * it will overwrite the amount that was set in the createTransaction call
     * Currently in development
     * @var Amount
     */
    private $amount;

    /**
     * A detailed list of all basket items
     * Currently in development
     * @var ObjectArray
     */
    private $basketItems;

    /**
     * CVV for credit card transactions. Required for payment method CC, CC3D.
     * @var string
     */
    private $cvv;

    /**
     * Set the Order ID
     * @see Reserve::orderID
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
     * @see Reserve::orderID
     * @return string
     */
    public function getOrderID()
    {
        return $this->orderID;
    }

    /**
     * Set the payment method
     * @see Reserve::paymentMethod
     * @param string $paymentMethod
     * @return $this
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    /**
     * Set the payment method
     * @see Reserve::paymentMethod
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Set the paymentInstrumentID field
     * @see Reserve::paymentInstrumentID
     * @param string $paymentInstrumentID
     * @return $this
     */
    public function setPaymentInstrumentID($paymentInstrumentID)
    {
        $this->paymentInstrumentID = $paymentInstrumentID;
        return $this;
    }

    /**
     * Get the paymentInstrumentID field
     * @see Reserve::paymentInstrumentID
     * @return string
     */
    public function getPaymentInstrumentID()
    {
        return $this->paymentInstrumentID;
    }

    /**
     * Set the amount field
     * @see Reserve::amount
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
     * @see Reserve::amount
     * @return Amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Add Basket Item the the basketItems field
     * @see Reserve::basketItems
     * @param BasketItem $item
     * @return $this
     */
    public function addBasketItem(BasketItem $item)
    {
        if (empty($this->basketItems)) {
            $this->basketItems = new ObjectArray();
        }

        $this->basketItems->append($item);
        return $this;
    }

    /**
     * Get the basketItems as an array
     * @see Reserve::basketItems
     * @see BasketItem
     * @return ObjectArray
     */
    public function getBasketItems()
    {
        return $this->basketItems;
    }

    /**
     * Set the cvv field
     * @see Reserve::$ccv
     * @param $ccv
     * @return $this
     */
    public function setCcv($ccv)
    {
        $this->cvv = $ccv;
        return $this;
    }

    /**
     * Get the cvv field
     * @return string
     */
    public function getCcv()
    {
        return $this->cvv;
    }

    /**
     * Get the serializer data
     * @return array
     */
    public function getPreSerializerData()
    {
        $data = array(
            'orderID' => $this->getOrderID(),
            'paymentMethod' => $this->getPaymentMethod(),
        );

        if (!empty($this->paymentInstrumentID)) {
            $data['paymentInstrumentID'] = $this->getPaymentInstrumentID();
        }

        if (!empty($this->amount)) {
            $data['amount'] = $this->getAmount();
        }

        if (!empty($this->cvv)) {
            $data['cvv'] = $this->getCcv();
        }

        return $data;
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

        $validationData['paymentMethod'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "paymentMethod is required"
        );

        $validationData['paymentMethod'][] = array(
            'name' => 'Callback',
            'value' => 'Upg\Library\PaymentMethods\Methods::validate',
            'message' => "paymentMethod must be certain values"
        );

        $validationData['cvv'][] = array(
            'name' => 'MaxLength',
            'value' => '4',
            'message' => "cvv must be between 1 and 4 characters"
        );

        return $validationData;
    }
}
