<?php
namespace Upg\Library\Request;

/**
 * Class DeleteUserPaymentInstrument
 * The deleteUserPaymentIntsrument call adds the functionality to delete a payment instrument from a user.
 * Input data consist of the following:
 * User information (existing user-id or complete user data)
 * Payment information (existing payment instrument-id or payment instrument data)
 * @see http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/deletepaymentinstrumentofuser
 * @package Upg\Library\Request
 */
class DeleteUserPaymentInstrument extends AbstractRequest
{
    /**
     * unique identifier of the payment instrument
     * @var string
     */
    private $paymentInstrumentID;

    /**
     * Set the paymentInstrumentID field
     * @see DeleteUserPaymentInstrument::paymentInstrumentID
     * @param string $paymentInstrumentID
     */
    public function setPaymentInstrumentID($paymentInstrumentID)
    {
        $this->paymentInstrumentID = $paymentInstrumentID;
    }

    /**
     * Get the paymentInstrumentID field
     * @see DeleteUserPaymentInstrument::paymentInstrumentID
     * @return string
     */
    public function getPaymentInstrumentID()
    {
        return $this->paymentInstrumentID;
    }

    public function getPreSerializerData()
    {
        return array(
            'paymentInstrumentID' => $this->getPaymentInstrumentID(),
        );
    }

    /**
     * Validation meta data
     * @return array
     */
    public function getClassValidationData()
    {
        $validationData = array();

        $validationData['paymentInstrumentID'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "paymentInstrumentID is required"
        );

        return $validationData;
    }
}
