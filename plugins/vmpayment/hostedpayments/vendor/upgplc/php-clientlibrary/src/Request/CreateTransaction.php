<?php

namespace Upg\Library\Request;

use Upg\Library\Request\Objects\Company;
use Upg\Library\Request\Objects\Person;
use Upg\Library\Request\Objects\Address;
use Upg\Library\Request\Objects\Amount;
use Upg\Library\Request\Objects\BasketItem;
use Upg\Library\Request\Objects\HostedPagesText;

use Upg\Library\Request\Attributes\ObjectArray;
use Upg\Library\Validation\Helper\Constants;

/**
 * Class CreateTransaction
 * This is the request class for any CreateTransaction request
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/gettransactionpaymentmethod
 * @package Upg\Library\Request
 */
class CreateTransaction extends AbstractRequest
{
    /**
     * Integration type: API
     * PayCo is integrated as pure API solution. No hosted pages are used. To transfer credit card data the merchant
     * has to be PCI compliant or he hasto use the PayCoBridge to transfer the data.
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/gettransactionpaymentmethod
     */
    const INTEGRATION_TYPE_API = "API";

    /**
     * Integration type: Hosted Pages Before
     * The hosted payment method selection page is integrated before the confirmation page of the merchant is shown.
     * The hosted page allows the user the select a payment method and enter payment instruments like credit card and
     * bank account.
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/gettransactionpaymentmethod
     */
    const INTEGRATION_TYPE_HOSTED_BEFORE = "HostedPageBefore";

    /**
     * Integration type: Hosted Pages After
     * The hosted payment method selection page is integrated after the confirmation page of the merchant is shown.
     * The hosted page allows the user the select a payment method and enter payment instruments like credit card and
     * bank account.
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/gettransactionpaymentmethod
     */
    const INTEGRATION_TYPE_HOSTED_AFTER = "HostedPageAfter";

    /**
     * Determines if the transaction was initiated during online-checkout process.
     */
    const CONTEXT_ONLINE = "ONLINE";

    /**
     * Determines if the transaction was initiated during offline where no direct user interaction happens.
     */
    const CONTEXT_OFFLINE = "OFFLINE";

    /**
     * Value for the userType field for b2c transactions
     */
    const USER_TYPE_PRIVATE = "PRIVATE";

    /**
     * Value for the userType field for b2b transactions
     */
    const USER_TYPE_BUSINESS = "BUSINESS";

    /**
     * Tag for the validator method for integration type
     */
    const TAG_INTEGRATION_TYPE = "INTEGRATION_TYPE";

    /**
     * Tag for the context validation
     */
    const TAG_CONTEXT = "CONTEXT";

    /**
     * Tag for the userType validation
     */
    const TAG_USER_TYPE = "USER_TYPE";

    /**
     * This is the order number of the shop. This id is created by the shop and
     * is used as identifier for this transaction
     * @var string
     */
    private $orderID;

    /**
     * The unique user id of the customer.
     * @var string
     */
    private $userID;

    /**
     * The type of integration in the web shop.
     * @see CreateTransaction::INTEGRATION_TYPE_API
     * @see CreateTransaction::INTEGRATION_TYPE_HOSTED_BEFORE
     * @see CreateTransaction::INTEGRATION_TYPE_HOSTED_AFTER
     * @var string
     */
    private $integrationType;

    /**
     * Enable automatic capture directly after the successful reservation,
     * if the parameter is not set, the default is “false”
     * @var bool
     */
    private $autoCapture;

    /**
     * Reference that can be set by the merchant. This parameter is sent back with
     * every call from PayCo to the merchant
     * @var string
     */
    private $merchantReference;

    /**
     * Determines if the transaction was initiated during online-checkout process or offline
     * where no direct user interaction happens.
     * @see CreateTransaction::CONTEXT_ONLINE
     * @see CreateTransaction::CONTEXT_OFFLINE
     * @var string
     */
    private $context;

    /**
     * This parameter is used to differentiate b2b and b2c customers
     * @see CreateTransaction::USER_TYPE_PRIVATE
     * @see CreateTransaction::USER_TYPE_BUSINESS
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
     * The IP address of the customer
     * @var string
     */
    private $userIpAddress;

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
     * Recipient for the Bill.
     * @var string
     */
    private $billingRecipient;

    /**
     * Additional information like c/o. (in Development)
     * @var string
     */
    private $billingRecipientAddition;

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
     * Additional information like c/o or “PostNummer”. (in Development)
     * @var string
     */
    private $shippingRecipientAddition;

    /**
     * the customers shipping address
     * @var Address
     */
    private $shippingAddress;

    /**
     * The amount of the basket
     * @var Amount
     */
    private $amount;

    /**
     * A detailed list of all basket items
     * @var ObjectArray
     */
    private $basketItems;

    /**
     * m -> minutes, h -> hours, d -> days; Example: one hour: 1h, ten minutes: 10m, two days: 2d
     * @var string
     */
    private $basketValidity;

    /**
     * A list of texts for the hosted page in for different languages for different payment methods
     * @var ObjectArray
     */
    private $hostedPagesTexts;

    /**
     * Locale determines the user’s communication language e.g.
     * for e-mails which will be send to the user or for payment pages.
     * @see \Upg\Library\Locale\Codes
     * @var string
     */
    private $locale;

    /**
     * Set the Order ID
     * @see CreateTransaction::orderID
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
     * @see CreateTransaction::orderID
     * @return string
     */
    public function getOrderID()
    {
        return $this->orderID;
    }

    /**
     * Set the user ID
     * @see CreateTransaction::userID
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
     * @see CreateTransaction::userID
     * @return string
     */
    public function getUserId()
    {
        return $this->userID;
    }

    /**
     * Set the Integration type
     * @see CreateTransaction::integrationType
     * @param string $integrationType
     * @return $this
     */
    public function setIntegrationType($integrationType)
    {
        $this->integrationType = $integrationType;
        return $this;
    }

    /**
     * Get the Integration type
     * @see CreateTransaction::integrationType
     * @return string
     */
    public function getIntegrationType()
    {
        return $this->integrationType;
    }

    /**
     * Set the Auto capture
     * @see CreateTransaction::autoCapture
     * @param bool $autoCapture
     * @return $this
     */
    public function setAutoCapture($autoCapture)
    {
        $this->autoCapture = $autoCapture ? true : false;
        return $this;
    }

    /**
     * Return the autoCapture field
     * @see CreateTransaction::autoCapture
     * @return bool
     */
    public function getAutoCapture()
    {
        return $this->autoCapture;
    }

    /**
     * Set the merchantReference
     * @see CreateTransaction::merchantReference
     * @param $merchantReference
     * @return $this
     */
    public function setMerchantReference($merchantReference)
    {
        $this->merchantReference = $merchantReference;
        return $this;
    }

    /**
     * Get the merchantReference
     * @see CreateTransaction::merchantReference
     * @return string
     */
    public function getMerchantReference()
    {
        return $this->merchantReference;
    }

    /**
     * Set the context field
     * @see CreateTransaction::context
     * @param string $context
     * @return $this
     */
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * Get the context field
     * @see CreateTransaction::context
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set the userType field
     * @see CreateTransaction::userType
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
     * @see CreateTransaction::userType
     * @return string
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * Set the user risk class
     * @see CreateTransaction::userRiskClass
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
     * @see CreateTransaction::userRiskClass
     * @return string
     */
    public function getUserRiskClass()
    {
        return $this->userRiskClass;
    }

    /**
     * Set the userIpAddress field
     * @see CreateTransaction::userIpAddress
     * @param $userIpAddress
     * @return $this
     */
    public function setUserIpAddress($userIpAddress)
    {
        $this->userIpAddress = $userIpAddress;
        return $this;
    }

    /**
     * Get the userIpAddress field
     * @see CreateTransaction::userIpAddress
     * @return string
     */
    public function getUserIpAddress()
    {
        return $this->userIpAddress;
    }

    /**
     * Set the companyData field
     * @see CreateTransaction::companyData
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
     * @see CreateTransaction::companyData
     * @return Company
     */
    public function getCompanyData()
    {
        return $this->companyData;
    }

    /**
     * Set the userData field
     * @see CreateTransaction::userData
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
     * @see CreateTransaction::userData
     * @return Person
     */
    public function getUserData()
    {
        return $this->userData;
    }

    /**
     * Set the billingRecipient field
     * @see CreateTransaction::billingRecipient
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
     * @see CreateTransaction::billingRecipient
     * @return string
     */
    public function getBillingRecipient()
    {
        return $this->billingRecipient;
    }

    /**
     * Set the billingRecipientAddition field
     * @see CreateTransaction::billingRecipientAddition
     * @param $billingRecipientAddition
     * @return $this
     */
    public function setBillingRecipientAddition($billingRecipientAddition)
    {
        $this->billingRecipientAddition = $billingRecipientAddition;
        return $this;
    }

    /**
     * Set billingAddress field
     * @see CreateTransaction::billingAddress
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
     * @see CreateTransaction::billingAddress
     * @return Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * Set the shippingRecipient field
     * @param string $shippingRecipient
     * @see CreateTransaction::shippingRecipient
     * @return $this
     */
    public function setShippingRecipient($shippingRecipient)
    {
        $this->shippingRecipient = $shippingRecipient;
        return $this;
    }

    /**
     * Get the shippingRecipient field
     * @see CreateTransaction::shippingRecipient
     * @return string
     */
    public function getShippingRecipient()
    {
        return $this->shippingRecipient;
    }

    /**
     * Get the shippingRecipientAddition field
     * @see CreateTransaction::shippingRecipientAddition
     * @param $shippingRecipientAddition
     * @return $this
     */
    public function setShippingRecipientAddition($shippingRecipientAddition)
    {
        $this->shippingRecipientAddition = $shippingRecipientAddition;
        return $this;
    }

    /**
     * Get the shippingRecipientAddition field
     * @see CreateTransaction::shippingRecipientAddition
     * @return string
     */
    public function getShippingRecipientAddition()
    {
        return $this->shippingRecipientAddition;
    }

    /**
     * Get the billingRecipientAddition field
     * @see CreateTransaction::billingRecipientAddition
     * @return string
     */
    public function getBillingRecipientAddition()
    {
        return $this->billingRecipientAddition;
    }

    /**
     * Set the shippingAddress field
     * @see CreateTransaction::shippingAddress
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
     * @see CreateTransaction::shippingAddress
     * @return Address
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * Set the amount field
     * @see CreateTransaction::amount
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
     * @see CreateTransaction::amount
     * @return Amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Add Basket Item
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
     * Get the basket items which is an array object with BasketItems objects
     * @see CreateTransaction::basketItems
     * @see Basket
     * @return ObjectArray
     */
    public function getBasketItems()
    {
        return $this->basketItems;
    }

    /**
     * Set the basketValidity field
     * @see CreateTransaction::basketValidity
     * @param $basketValidity
     * @return $this
     */
    public function setBasketValidity($basketValidity)
    {
        $this->basketValidity = $basketValidity;
        return $this;
    }

    /**
     * Get the basketValidity field
     * @see CreateTransaction::basketValidity
     * @return string
     */
    public function getBasketValidity()
    {
        return $this->basketValidity;
    }

    /**
     * Add hosted Page Tex object to the request
     * @see CreateTransaction::hostedPagesTexts
     * @see HostedPagesText
     * @param HostedPagesText $hostedPagesText
     * @return $this
     */
    public function setHostedPagesTexts(HostedPagesText $hostedPagesText)
    {
        if (empty($this->hostedPagesTexts)) {
            $this->hostedPagesTexts = new ObjectArray();
        }

        $this->hostedPagesTexts->append($hostedPagesText);
        return $this;
    }

    /**
     * Return array of HostedPagesText objects that have been set
     * @see CreateTransaction::hostedPagesTexts
     * @see HostedPagesText
     * @return ObjectArray
     */
    public function getHostedPagesTexts()
    {
        return $this->hostedPagesTexts;
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
            'orderID' => $this->getOrderID(),
            'userID' => $this->getUserId(),
            'context' => $this->getContext(),
            'userType' => $this->getUserType(),
            'amount' => $this->getAmount(),
            'basketItems' => $this->getBasketItems(),
            'locale' => $this->getLocale(),
        );

        if (!empty($this->integrationType)) {
            $data['integrationType'] = $this->getIntegrationType();
        }

        if (!empty($this->autoCapture)) {
            $data['autoCapture'] = $this->getAutoCapture();
        }

        if (!empty($this->merchantReference)) {
            $data['merchantReference'] = $this->getMerchantReference();
        }

        if (is_numeric($this->userRiskClass)) {
            $data['userRiskClass'] = $this->getUserRiskClass();
        }

        if (!empty($this->userIpAddress)) {
            $data['userIpAddress'] = $this->getUserIpAddress();
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

        if (!empty($this->billingRecipientAddition)) {
            $data['billingRecipientAddition'] = $this->getBillingRecipientAddition();
        }

        if (!empty($this->billingAddress)) {
            $data['billingAddress'] = $this->getBillingAddress();
        }

        if (!empty($this->shippingRecipient)) {
            $data['shippingRecipient'] = $this->getShippingRecipient();
        }

        if (!empty($this->shippingRecipientAddition)) {
            $data['shippingRecipientAddition'] = $this->getShippingRecipientAddition();
        }

        if (!empty($this->shippingAddress)) {
            $data['shippingAddress'] = $this->getShippingAddress();
        }

        if (!empty($this->basketValidity)) {
            $data['basketValidity'] = $this->getBasketValidity();
        }

        if (!empty($this->hostedPagesTexts)) {
            $data['hostedPagesTexts'] = $this->getHostedPagesTexts();
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

        /**
         * $validationData['integrationType'][] = array(
         * 'name' => 'Callback',
         * 'value' => get_class($this) . '::validateIntegrationType',
         * 'message' => "integrationType must be certain values"
         * );
         * **/
        $validationData['integrationType'][] = array(
            'name' => 'Callback',
            'value' => get_class($this) . '::validateIntegrationType',
            'message' => "integrationType must be certain values"
        );

        $validationData['merchantReference'][] = array(
            'name' => 'MaxLength',
            'value' => '255',
            'message' => "merchantReference must be between 1 and 255 characters"
        );

        $validationData['context'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "context is required"
        );

        $validationData['context'][] = array(
            'name' => 'Callback',
            'value' => get_class($this) . '::validateContext',
            'message' => "context must be certain values"
        );


        $validationData['userType'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "userType must be certain values"
        );

        $validationData['userType'][] = array(
            'name' => 'Callback',
            'value' => get_class($this) . '::validateUserType',
            'message' => "userType must be certain values"
        );

        $validationData['userRiskClass'][] = array(
            'name' => 'Callback',
            'value' => 'Upg\Library\Risk\RiskClass::validateRiskClass',
            'message' => "userRiskClass must contain certain values or be empty"
        );

        $validationData['userIpAddress'][] = array(
            'name' => 'MaxLength',
            'value' => '15',
            'message' => "userIpAddress must be between 1 and 15 characters"
        );

        $validationData['billingRecipient'][] = array(
            'name' => 'MaxLength',
            'value' => '80',
            'message' => "billingRecipient must be between 1 and 80 characters"
        );

        $validationData['billingRecipientAddition'][] = array(
            'name' => 'MaxLength',
            'value' => '80',
            'message' => "billingRecipientAddition must be between 1 and 80 characters"
        );

        $validationData['shippingRecipient'][] = array(
            'name' => 'MaxLength',
            'value' => '80',
            'message' => "shippingRecipient must be between 1 and 80 characters"
        );

        $validationData['shippingRecipientAddition'][] = array(
            'name' => 'MaxLength',
            'value' => '80',
            'message' => "shippingRecipientAddition must be between 1 and 80 characters"
        );

        $validationData['amount'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "amount must be set for the transaction"
        );

        $validationData['basketItems'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "basketItems must be added to the transaction"
        );

        $validationData['locale'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "locale must be set for the transaction"
        );

        $validationData['locale'][] = array(
            'name' => 'Callback',
            'value' => 'Upg\Library\Locale\Codes::validateLocale',
            'message' => "locale must be certain values"
        );

        return $validationData;
    }

    /**
     * Validate the integration type
     * @param $value
     * @return mixed
     */
    public static function validateIntegrationType($value)
    {
        return Constants::validateConstant(__CLASS__, $value, static::TAG_INTEGRATION_TYPE);
    }

    /**
     * Validate the context
     * @param $value
     * @return mixed
     */
    public static function validateContext($value)
    {
        return Constants::validateConstant(__CLASS__, $value, static::TAG_CONTEXT);
    }

    /**
     * Validate the user type
     * @param $value
     * @return mixed
     */
    public static function validateUserType($value)
    {
        return Constants::validateConstant(__CLASS__, $value, static::TAG_USER_TYPE);
    }
}
