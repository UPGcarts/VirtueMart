<?php
namespace Upg\Library\Request;

use Upg\Library\PaymentMethods\Methods as PaymentMethods;

/**
 * Class GetTransactionStatus
 * This is the request class for any getTransactionStatus request object
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/getstatus
 * @package Upg\Library\Request
 */
class GetTransactionStatus extends AbstractRequest
{
    /**
     * This is the order number of the shop.
     * This id is created by the shop and is used as identifier for this transaction
     * @var string
     */
    private $orderID;

    /**
     * Set the Order ID
     * @see GetTransactionStatus::orderID
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
     * @see GetTransactionStatus::orderID
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
