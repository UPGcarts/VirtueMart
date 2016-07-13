<?php
namespace Upg\Library\Request;

use Upg\Library\PaymentMethods\Methods as PaymentMethods;
use Upg\Library\Request\Objects\Company;
use Upg\Library\Request\Objects\Person;
use Upg\Library\Request\Objects\Address;

/**
 * Class RegisterUser
 * This is the request class for any registerUser and updateUser request object
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/registeruser
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/updateuser
 * @package Upg\Library\Request
 */
class RegisterUser extends AbstractRequest
{
    /**
     * The unique user id of the customer.
     * @var string
     */
    private $userID;

    /**
     * This parameter is used to differentiate b2b and b2c customers
     * @see \Upg\Library\User\Type::USER_TYPE_PRIVATE
     * @see \Upg\Library\User\Type::USER_TYPE_BUSINESS
     * @var string
     */
    private $userType;

    /**
     * Defines the risk assessment of a user from merchants perspective. Possible values are: [0,1,2].
     * 0 -> trusted user, 1 -> default risk user, 2 -> high risk user.
     * Either the useRiskClass or the basketItemRiskCass in the basket items has to be set.
     * @see \Upg\Library\Risk\RiskClass
     * @var string
     */
    private $userRiskClass = null;

    /**
     * Contact data of the users company
     * @see Company
     * @var Company
     */
    private $companyData;

    /**
     * contact data of the user. The field “date of birth” is not mandatory.
     * It’s needed for solvency checks and the payment method “bill secure”.
     * An absent “date of birth” could cause less payment methods to be offered to the user.
     * @var Person
     */
    private $userData;

    /**
     * the customers billing address
     * Only required if user was not registered before with this userID
     * @var Address
     */
    private $billingRecipient;

    /**
     * the customers billing address
     * Only required if user was not registered before with this userID
     * @var Address
     */
    private $billingAddress;

    /**
     * Recipient for shipping
     * @var string
     */
    private $shippingRecipient;

    /**
     * the customers shipping address
     * @var Address
     */
    private $shippingAddress;

    /**
     * Locale determines the user’s communication language e.g.
     * for e-mails which will be send to the user or for payment pages.
     * @see \Upg\Library\Locale\Codes
     * @var string
     */
    private $locale;

    /**
     * Set the user ID
     * @see RegisterUser::userID
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
     * @see RegisterUser::userID
     * @return string
     */
    public function getUserId()
    {
        return $this->userID;
    }

    /**
     * Set the userType field
     * @see RegisterUser::userType
     * @param $userType
     * @return $this
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;
        return $this;
    }

    /**
     * Get the userType field
     * @see RegisterUser::userType
     * @return string
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * Set the user risk class
     * @see RegisterUser::userRiskClass
     * @param string $userRiskClass
     * @return $this
     */
    public function setUserRiskClass($userRiskClass)
    {
        $this->userRiskClass = $userRiskClass;
        return $this;
    }

    /**
     * Get the user risk class
     * @see RegisterUser::userRiskClass
     * @return string
     */
    public function getUserRiskClass()
    {
        return $this->userRiskClass;
    }

    /**
     * Set the companyData field
     * @see RegisterUser::companyData
     * @param Company $company
     * @return $this
     */
    public function setCompanyData(Company $company)
    {
        $this->companyData = $company;
        return $this;
    }

    /**
     * Get the companyData field
     * @see RegisterUser::companyData
     * @return Company
     */
    public function getCompanyData()
    {
        return $this->companyData;
    }

    /**
     * Set the userData field
     * @see RegisterUser::userData
     * @param Person $userData
     * @return $this
     */
    public function setUserData(Person $userData)
    {
        $this->userData = $userData;
        return $this;
    }

    /**
     * Get the userData field
     * @see RegisterUser::userData
     * @return Person
     */
    public function getUserData()
    {
        return $this->userData;
    }

    /**
     * Set the billingRecipient field
     * @see RegisterUser::billingRecipient
     * @param $billingRecipient
     * @return $this
     */
    public function setBillingRecipient($billingRecipient)
    {
        $this->billingRecipient = $billingRecipient;
        return $this;
    }

    /**
     * Get the billingRecipient field
     * @see RegisterUser::billingRecipient
     * @return string
     */
    public function getBillingRecipient()
    {
        return $this->billingRecipient;
    }

    /**
     * Set billingAddress field
     * @see RegisterUser::billingAddress
     * @param Address $billingAddress
     * @return $this
     */
    public function setBillingAddress(Address $billingAddress)
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    /**
     * Get billingAddress field
     * @see RegisterUser::billingAddress
     * @return Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * Set the shippingRecipient field
     * @param string $shippingRecipient
     * @see RegisterUser::shippingRecipient
     * @return $this
     */
    public function setShippingRecipient($shippingRecipient)
    {
        $this->shippingRecipient = $shippingRecipient;
        return $this;
    }

    /**
     * Get the shippingRecipient field
     * @see RegisterUser::shippingRecipient
     * @return string
     */
    public function getShippingRecipient()
    {
        return $this->shippingRecipient;
    }

    /**
     * Set the shippingAddress field
     * @see RegisterUser::shippingAddress
     * @param Address $shippingAddress
     * @return $this
     */
    public function setShippingAddress(Address $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }

    /**
     * Get the shippingAddress field
     * @see RegisterUser::shippingAddress
     * @return Address
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * Set the locale field
     * @see CreateTransaction::locale
     * @param $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Get the locale field
     * @see CreateTransaction::locale
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    public function getPreSerializerData()
    {
        $data = array(
            'userID' => $this->getUserId(),
            'userType' => $this->getUserType(),
            'locale' => $this->getLocale(),
        );

        if (!is_null($this->userRiskClass)) {
            $data['userRiskClass'] = $this->getUserRiskClass();
        }

        if (!empty($this->companyData)) {
            $data['companyData'] = $this->getCompanyData();
        }

        if (!empty($this->userData)) {
            $data['userData'] = $this->getUserData();
        }

        if (!empty($this->billingRecipient)) {
            $data['billingRecipient'] = $this->getBillingRecipient();
        }

        if (!empty($this->billingAddress)) {
            $data['billingAddress'] = $this->getBillingAddress();
        }

        if (!empty($this->shippingRecipient)) {
            $data['shippingRecipient'] = $this->getShippingRecipient();
        }

        if (!empty($this->shippingAddress)) {
            $data['shippingAddress'] = $this->getShippingAddress();
        }

        return $data;
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

        $validationData['userType'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "userType is required"
        );

        $validationData['userType'][] = array(
            'name' => 'Callback',
            'value' => 'Upg\Library\User\Type::validate',
            'message' => "userType must be certain values"
        );

        $validationData['userRiskClass'][] = array(
            'name' => 'Callback',
            'value' => 'Upg\Library\Risk\RiskClass::validateRiskClass',
            'message' => "userRiskClass must certain values or be empty"
        );

        $validationData['billingRecipient'][] = array(
            'name' => 'MaxLength',
            'value' => '80',
            'message' => "billingRecipient must be between 1 and 80 characters"
        );

        $validationData['shippingRecipient'][] = array(
            'name' => 'MaxLength',
            'value' => '80',
            'message' => "shippingRecipient must be between 1 and 80 characters"
        );

        $validationData['locale'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "locale must be set for the request"
        );

        $validationData['locale'][] = array(
            'name' => 'Callback',
            'value' => 'Upg\Library\Locale\Codes::validateLocale',
            'message' => "locale must be certain values"
        );

        return $validationData;
    }
}
