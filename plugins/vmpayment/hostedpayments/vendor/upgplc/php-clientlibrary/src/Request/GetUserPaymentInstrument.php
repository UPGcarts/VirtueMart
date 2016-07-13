<?php
namespace Upg\Library\Request;

/**
 * Class GetUserPaymentInstrument
 * The getUserPaymentInstrument call adds the functionality to get a user‘s payment instruments.
 * Input data consist of the following:
 * User information (existing user-id or complete user data)
 * Payment information (existing payment instrument-id or payment instrument data)
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/getuserpaymentinstrument
 * @package Upg\Library\Request
 */
class GetUserPaymentInstrument extends AbstractRequest
{
    /**
     * The unique user id of the customer.
     * @var string
     */
    private $userID;

    /**
     * Set the userID field
     * @see GetUser::userID
     * @param $userID
     * @return $this
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
        return $this;
    }

    /**
     * @see GetUser::userID
     * @return string
     */
    public function getUserID()
    {
        return $this->userID;
    }

    public function getPreSerializerData()
    {
        return array(
            'userID' => $this->getUserId(),
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

        return $validationData;
    }
}
