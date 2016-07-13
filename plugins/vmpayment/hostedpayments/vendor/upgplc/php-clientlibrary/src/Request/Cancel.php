<?php

namespace Upg\Library\Request;

use Upg\Library\PaymentMethods\Methods as PaymentMethods;
use Upg\Library\Request\Objects\Amount;
use Upg\Library\Request\Objects\BasketItem;

/**
 * Class Cancel
 * This is the request class for any Cancel request
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/cancel
 * @package Upg\Library\Request
 */
class Cancel extends AbstractRequest
{
    /**
     * This is the order number of the shop.
     * This id is created by the shop and is used as identifier for this transaction
     * @var string
     */
    private $orderID;

    /**
     * Set the Order ID
     * @see Cancel::orderID
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
     * @see Cancel::orderID
     * @return string
     */
    public function getOrderID()
    {
        return $this->orderID;
    }

    /**
     * Get the serializer data
     * @return array
     */
    public function getPreSerializerData()
    {
        return array(
            'orderID' => $this->getOrderID(),
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

        return $validationData;
    }

}
