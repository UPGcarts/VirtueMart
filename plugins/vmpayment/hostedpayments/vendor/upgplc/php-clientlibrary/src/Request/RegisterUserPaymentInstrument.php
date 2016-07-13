<?php
namespace Upg\Library\Request;

use Upg\Library\Request\Objects\PaymentInstrument;

/**
 * Class RegisterUserPaymentInstrument
 * The registerUserPaymentIntsrument call adds the functionality to register a payment instrument to a user.
 * Input data consist of the following:
 * User information (existing user-id or complete user data)
 * Payment information (existing payment instrument-id or payment instrument data)
 * @package Upg\Library\Request
 */
class RegisterUserPaymentInstrument extends AbstractRequest
{
    /**
     * The unique user id of the customer.
     * @var string
     */
    private $userID;

    /**
     * The payment instrument to register. The PaymentInstrumentID has to be empty
     * @var paymentInstrument
     */
    private $paymentInstrument;

    /**
     * Set the user ID
     * @see RegisterUserPaymentInstrument::userID
     * @param string $userID
     * @return $this
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
        return $this;
    }

    /**
     * Get the user id
     * @see RegisterUserPaymentInstrument::userID
     * @return string
     */
    public function getUserId()
    {
        return $this->userID;
    }

    /**
     * Set the paymentInstrument field
     * @param PaymentInstrument $paymentInstrument
     * @return $this
     */
    public function setPaymentInstrument(PaymentInstrument $paymentInstrument)
    {
        $this->paymentInstrument = $paymentInstrument;
        return $this;
    }

    /**
     * Get the paymentInstrument field
     * @return PaymentInstrument
     */
    public function getPaymentInstrument()
    {
        return $this->paymentInstrument;
    }

    public function getPreSerializerData()
    {
        return array(
            'userID' => $this->getUserId(),
            'paymentInstrument' => $this->getPaymentInstrument(),
        );
    }

    /**
     * Validation meta data
     * @return array
     */
    public function getClassValidationData()
    {
        $validationData = array();

        $validationData['userID'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "userID is required"
        );

        $validationData['userID'][] = array(
            'name' => 'MaxLength',
            'value' => '50',
            'message' => "userID must be between 1 and 50 characters"
        );

        $validationData['paymentInstrument'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "paymentInstrument is required"
        );

        return $validationData;
    }
}
