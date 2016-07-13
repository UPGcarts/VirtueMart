<?php

namespace Upg\Library\Request\Objects;

use Upg\Library\Validation\Helper\Constants;

/**
 * Class ShopInformation
 * For shopInformation json object
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/json-objects
 * @package Upg\Library\Request\Objects
 */
class ShopInformation extends AbstractObject
{
    /**
     * The unique name that is used by the merchant to reference to this shop
     * @var string
     */
    private $shopName;

    /**
     * The URL of the shop, which sales the products/services
     * @var string
     */
    private $shopUrl;

    /**
     * The shop industry code
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/list-of-branches
     * @see Upg\Library\Industry\Codes
     * @var int
     */
    private $industry;

    /**
     * 2- letter country code of the business location of the shop according ISO 3166
     * @var string
     */
    private $shopCountry;

    /**
     * The shop’s currency. Currency code according to ISO4217
     * @var string
     * @link http://datahub.io/dataset/iso-4217-currency-codes/resource/69ec48a5-4195-4439-92cf-d15096b9b20a
     */
    private $currency;

    /**
     * Short description of the business model
     * @var string
     */
    private $shopDescription;

    /**
     * Shop offers subscription model
     * @var bool
     */
    private $subscriptionOffered;

    /**
     * Shop offers adult specific services, goods or content
     * @var bool
     */
    private $adultContent;

    /**
     * Information about the approx. average shopping cart value
     * Amount in smallest possible denomination (e.g. 12366 cent)
     * @var int
     */
    private $shoppingCartValue;

    /**
     * Information about the approx. average shopping cart value
     * Amount in smallest possible denomination (e.g. 12366 cent)
     * @var int
     */
    private $revenuePerMonth;

    /**
     * Possible values: “1”, “2”, “3”, “4”, “5”: Information about the approx. average delivery time.
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/json-objects
     * @var int
     */
    private $deliveryTime;

    /**
     * name which should be presented on the users billing documents
     * (e.g. credit card statement or reference text within bank account statement
     * @var string
     */
    private $billingName;

    /**
     * Name of the used shop system eg. Magento, Prestashop etc
     * @var string
     */
    private $shopSystem;

    /**
     * Version of shop system
     * @var string
     */
    private $shopVersion;

    /**
     * Programming language when shop system is custom solution
     * @var string
     */
    private $shopSystemLanguage;

    /**
     * PayCo redirects to this URL if the user clicks “Change or delete item” in the item
     * list of the confirmation page.
     * @var string
     */
    private $basketUrl;

    /**
     * The url in production or sandbox that is used as link to the
     * Shops Terms and Conditions on the confirmation page
     * @var string
     */
    private $tacUrl;

    /**
     * The url in production or sandbox that is used as link to the “cancellation rights” on the confirmation page
     * @var string
     */
    private $cancellationRightUrl;

    /**
     * The url within production that is used during the notification
     * process to send the transaction result to the user
     * @var string
     */
    private $redirectUrl;

    /**
     * The url of the logo of the shop
     * @var string
     */
    private $shopLogoUrl;

    /**
     * The shop’s bank account where the revenues should be paid off. Please use payment method “BANKACCOUNT”
     * @var PaymentInstrument
     */
    private $bankAccount;

    /**
     * This is the default language which should be used for the checkout if no other value
     * has been passed within the transaction call.Please refer to Language codes for the Codes
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/supported-languages
     * @see \Upg\Library\Locale\Codes
     * @var int
     */
    private $locale;

    /**
     * The name of the ERP system when used.
     * @var string
     */
    private $erpSystem;

    /**
     * The estimated start date.
     * @var \DateTime
     */
    private $estimatedStart;

    const DELIVERY_TIME_LESS_THAN_2_DAYS = 1;
    const DELIVERY_TIME_2_TO_5_DAYS = 2;
    const DELIVERY_TIME_5_TO_10_DAYS = 3;
    const DELIVERY_TIME_10_TO_20_DAYS = 4;
    const DELIVERY_TIME_20_MORE_DAYS = 5;

    const TAG_DELIVERY_TIME = "DELIVERY_TIME";

    /**
     * Set the shop name
     * @param string $shopName
     * @return $this
     */
    public function setShopName($shopName)
    {
        $this->shopName = $shopName;
        return $this;
    }

    /**
     * Get the shop name
     * @return string
     */
    public function getShopName()
    {
        return $this->shopName;
    }

    /**
     * Set the shop Url
     * @param string $shopUrl
     * @return $this
     */
    public function setShopUrl($shopUrl)
    {
        $this->shopUrl = $shopUrl;
        return $this;
    }

    /**
     * Get the shop url
     * @return string
     */
    public function getShopUrl()
    {
        return $this->shopUrl;
    }

    /**
     * Set the industry
     * @param int $industry
     * @return $this
     */
    public function setIndustry($industry)
    {
        $this->industry = $industry;
        return $this;
    }

    /**
     * Get the industry
     * @return int
     */
    public function getIndustry()
    {
        return $this->industry;
    }

    /**
     * Set the shop country
     * @param string $shopCountry
     * @return $this
     */
    public function setShopCountry($shopCountry)
    {
        $this->shopCountry = $shopCountry;
        return $this;
    }

    /**
     * Get the shop country
     * @return string
     */
    public function getShopCountry()
    {
        return $this->shopCountry;
    }

    /**
     * Set the ISO4217 currency code
     * @param $currency
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Return the ISO4217 currency code
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set the shop description
     * @param string $shopDescription
     * @return $this
     */
    public function setShopDescription($shopDescription)
    {
        $this->shopDescription = $shopDescription;
        return $this;
    }

    /**
     * Get the shop description
     * @return string
     */
    public function getShopDescription()
    {
        return $this->shopDescription;
    }

    /**
     * Set the subscription Offered field
     * @param bool $subscriptionOffered
     * @return $this
     */
    public function setSubscriptionOffered($subscriptionOffered)
    {
        $this->shopDescription = $subscriptionOffered;
        return $this;
    }

    /**
     * Get the subscription Offered field
     * @return bool
     */
    public function getSubscriptionOffered()
    {
        return $this->subscriptionOffered;
    }

    /**
     * Set the adultContent field
     * @param bool $adultContent
     * @return $this
     */
    public function setAdultContent($adultContent)
    {
        $this->adultContent = $adultContent;
        return $this;
    }

    /**
     * Get the adultContent field
     * @return bool
     */
    public function getAdultContent()
    {
        return $this->adultContent;
    }

    /**
     * Set the shopping cart value
     * @param int $shoppingCartValue
     * @return $this
     */
    public function setShoppingCartValue($shoppingCartValue)
    {
        $this->shoppingCartValue = $shoppingCartValue;
        return $this;
    }

    /**
     * Get the shoppingCartValue field
     * @return int
     */
    public function getShoppingCartValue()
    {
        return $this->shoppingCartValue;
    }

    /**
     * Set the revenuePerMonth field
     * @param int $revenuePerMonth
     * @return $this
     */
    public function setRevenuePerMonth($revenuePerMonth)
    {
        $this->revenuePerMonth = $revenuePerMonth;
        return $this;
    }

    /**
     * Get the revenuePerMonth field
     * @return int
     */
    public function getRevenuePerMonth()
    {
        return $this->revenuePerMonth;
    }

    /**
     * Set the deliveryTime field
     * @param int $deliveryTime
     * @return $this
     */
    public function setDeliveryTime($deliveryTime)
    {
        $this->deliveryTime = $deliveryTime;
        return $this;
    }

    /**
     * Get the deliveryTime field
     * @return int
     */
    public function getDeliveryTime()
    {
        return $this->deliveryTime;
    }

    /**
     * Set the billingName field
     * @param string $billingName
     * @return $this
     */
    public function setBillingName($billingName)
    {
        $this->billingName = $billingName;
        return $this;
    }

    /**
     * Get the billingName field
     * @return string
     */
    public function getBillingName()
    {
        return $this->billingName;
    }

    /**
     * Set the shopSystem field
     * @param string $shopSystem
     * @return $this
     */
    public function setShopSystem($shopSystem)
    {
        $this->shopSystem = $shopSystem;
        return $this;
    }

    /**
     * Get the shopSystem
     * @return string
     */
    public function getShopSystem()
    {
        return $this->shopSystem;
    }

    /**
     * Set the shopVersion
     * @param string $shopVersion
     * @return $this
     */
    public function setShopVersion($shopVersion)
    {
        $this->shopVersion = $shopVersion;
        return $this;
    }

    /**
     * Get the shop version
     * @return string
     */
    public function getShopVersion()
    {
        return $this->shopVersion;
    }

    /**
     * Set the shopSystemLanguage field
     * @param string $shopSystemLanguage
     * @return $this
     */
    public function setShopSystemLanguage($shopSystemLanguage)
    {
        $this->shopSystemLanguage = $shopSystemLanguage;
        return $this;
    }

    /**
     * Get the shopSystemLanguage field
     * @return string
     */
    public function getShopSystemLanguage()
    {
        return $this->shopSystemLanguage;
    }

    /**
     * Set the basketUrl field
     * @param string $basketUrl
     * @return $this
     */
    public function setBasketUrl($basketUrl)
    {
        $this->basketUrl = $basketUrl;
        return $this;
    }

    /**
     * Get the basketUrl field
     * @return string
     */
    public function getBasketUrl()
    {
        return $this->basketUrl;
    }

    /**
     * Set the tacUrl field
     * @param string $tacUrl
     * @return $this
     */
    public function setTacUrl($tacUrl)
    {
        $this->tacUrl = $tacUrl;
        return $this;
    }

    /**
     * Return the tacUrl field
     * @return string
     */
    public function getTacUrl()
    {
        return $this->tacUrl;
    }

    /**
     * Set the cancellationRightUrl field
     * @param string $cancellationRightUrl
     * @return $this
     */
    public function setCancellationRightUrl($cancellationRightUrl)
    {
        $this->cancellationRightUrl = $cancellationRightUrl;
        return $this;
    }

    /**
     * Get the cancellationRightUrl field
     * @return string
     */
    public function getCancellationRightUrl()
    {
        return $this->cancellationRightUrl;
    }

    /**
     * Set the redirectUrl field
     * @param string $redirectUrl
     * @return $this
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    /**
     * Get the redirectUrl field
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * Set the shopLogoUrl field
     * @param string $shopLogoUrl
     * @return $this
     */
    public function setShopLogoUrl($shopLogoUrl)
    {
        $this->shopLogoUrl = $shopLogoUrl;
        return $this;
    }

    /**
     * Get the shopLogoUrl field
     * @return string
     */
    public function getShopLogoUrl()
    {
        return $this->shopLogoUrl;
    }

    /**
     * Set the bankAccount field
     * @param PaymentInstrument $bankAccount
     * @return $this
     */
    public function setBankAccount(PaymentInstrument $bankAccount)
    {
        $this->bankAccount = $bankAccount;
        return $this;
    }

    /**
     * Get the bankAccount field
     * @return PaymentInstrument
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * Set the locale option
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/supported-languages
     * @see \Upg\Library\Locale\Codes
     * @param int $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * get the locale field
     * @return int
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set the erpSystem field
     * @param int $erpSystem
     * @return $this
     */
    public function setErpSystem($erpSystem)
    {
        $this->erpSystem = $erpSystem;
        return $this;
    }

    /**
     * Get the erpSystem field
     * @return string
     */
    public function getErpSystem()
    {
        return $this->erpSystem;
    }

    /**
     * Set the estimatedStart field
     * @param \DateTime $estimatedStart
     * @return $this
     */
    public function setEstimatedStart(\DateTime $estimatedStart)
    {
        $this->estimatedStart = $estimatedStart;
        return $this;
    }

    /**
     * Get the estimatedStart field
     * @return \DateTime
     */
    public function getEstimatedStart()
    {
        return $this->estimatedStart;
    }

    /**
     * Return the array for validation
     * @return array
     */
    public function toArray()
    {
        $return = array(
            'shopName' => $this->getShopName(),
            'shopUrl' => $this->getShopUrl(),
            'industry' => $this->getIndustry(),
            'shopCountry' => $this->getShopCountry(),
            'currency' => $this->getCurrency(),
            'shopDescription' => $this->getShopDescription(),
            'subscriptionOffered' => $this->getSubscriptionOffered(),
            'adultContent' => $this->getAdultContent(),
            'shoppingCartValue' => $this->getShoppingCartValue(),
            'revenuePerMonth' => $this->getRevenuePerMonth(),
            'billingName' => $this->getBillingName(),
            'shopSystem' => $this->getShopSystem(),
            'shopLogoUrl' => $this->getShopLogoUrl(),
            'bankAccount' => $this->getBankAccount(),
            'locale' => $this->getLocale(),
        );

        if (!empty($this->deliveryTime)) {
            $return['deliveryTime'] = $this->getDeliveryTime();
        }

        if (!empty($this->shopVersion)) {
            $return['shopVersion'] = $this->getShopVersion();
        }

        if (!empty($this->shopSystemLanguage)) {
            $return['shopSystemLanguage'] = $this->getShopSystemLanguage();
        }

        if (!empty($this->basketUrl)) {
            $return['basketUrl'] = $this->getBasketUrl();
        }

        if (!empty($this->tacUrl)) {
            $return['tacUrl'] = $this->getTacUrl();
        }

        if (!empty($this->cancellationRightUrl)) {
            $return['cancellationRightUrl'] = $this->getCancellationRightUrl();
        }

        if (!empty($this->redirectUrl)) {
            $return['redirectUrl'] = $this->getRedirectUrl();
        }

        if (!empty($this->erpSystem)) {
            $return['erpSystem'] = $this->getErpSystem();
        }

        if (!empty($this->estimatedStart)) {
            $return['estimatedStart'] = $this->getEstimatedStart()->format("Y-m-d");
        }

        return $return;
    }

    public function getValidationData()
    {
        $validationData = array();

        $validationData['shopName'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "shopName is required"
        );

        $validationData['shopName'][] = array(
            'name' => 'MaxLength',
            'value' => '20',
            'message' => "shopName must be between 1 and 20 characters"
        );

        $validationData['shopUrl'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "shopUrl is required"
        );

        $validationData['shopUrl'][] = array(
            'name' => 'MaxLength',
            'value' => '200',
            'message' => "shopName must be between 1 and 200 characters"
        );

        $validationData['industry'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "industry is required"
        );

        $validationData['industry'][] = array(
            'name' => 'Callback',
            'value' => 'Upg\Library\Industry\Codes::validate',
            'message' => "industry is must be certain values"
        );

        $validationData['shopCountry'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "shopCountry is required"
        );

        $validationData['shopCountry'][] = array(
            'name' => 'Regex',
            'value' => '/^[a-zA-Z]{2}/',
            'message' => "shopCountry is must be an ISO 3166 value"
        );

        $validationData['currency'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "shopCountry is required"
        );

        $validationData['currency'][] = array(
            'name' => 'Regex',
            'value' => '/^[a-zA-Z]{3}/',
            'message' => "shopCountry is must be an ISO4217 value"
        );

        $validationData['shopDescription'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "shopDescription is required"
        );

        $validationData['shopDescription'][] = array(
            'name' => 'MaxLength',
            'value' => '1000',
            'message' => "shopDescription must be between 1 and 1000 characters"
        );

        $validationData['subscriptionOffered'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "subscriptionOffered is required"
        );

        $validationData['subscriptionOffered'][] = array(
            'name' => 'Callback',
            'value' => 'is_bool',
            'message' => "subscriptionOffered must be a explicit boolean type"
        );

        $validationData['adultContent'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "adultContent is required"
        );

        $validationData['adultContent'][] = array(
            'name' => 'Callback',
            'value' => 'is_bool',
            'message' => "adultContent must be a explicit boolean type"
        );

        $validationData['shoppingCartValue'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "shoppingCartValue is required"
        );

        $validationData['shoppingCartValue'][] = array(
            'name' => 'Integer',
            'value' => null,
            'message' => "shoppingCartValue must be an int value"
        );

        $validationData['revenuePerMonth'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "revenuePerMonth is required"
        );

        $validationData['revenuePerMonth'][] = array(
            'name' => 'Integer',
            'value' => null,
            'message' => "revenuePerMonth must be an int value"
        );

        $validationData['deliveryTime'][] = array(
            'name' => 'Callback',
            'value' => get_class($this) . '::validateDeliveryTime',
            'message' => "revenuePerMonth must be certain values"
        );

        $validationData['billingName'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "billingName is required"
        );

        $validationData['billingName'][] = array(
            'name' => 'MaxLength',
            'value' => '22',
            'message' => "billingName is must be between 1 and 22 characters"
        );

        $validationData['shopSystem'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "shopSystem is required"
        );

        $validationData['shopSystem'][] = array(
            'name' => 'MaxLength',
            'value' => '200',
            'message' => "shopSystem is must be between 1 and 200 characters"
        );

        $validationData['shopVersion'][] = array(
            'name' => 'MaxLength',
            'value' => '200',
            'message' => "shopVersion is must be between 1 and 200 characters"
        );

        $validationData['shopSystemLanguage'][] = array(
            'name' => 'MaxLength',
            'value' => '200',
            'message' => "shopSystemLanguage is must be between 1 and 200 characters"
        );

        $validationData['basketUrl'][] = array(
            'name' => 'MaxLength',
            'value' => '200',
            'message' => "basketUrl is must be between 1 and 200 characters"
        );

        $validationData['tacUrl'][] = array(
            'name' => 'MaxLength',
            'value' => '200',
            'message' => "tacUrl is must be between 1 and 200 characters"
        );

        $validationData['cancellationRightUrl'][] = array(
            'name' => 'MaxLength',
            'value' => '200',
            'message' => "cancellationRightUrl is must be between 1 and 200 characters"
        );

        $validationData['redirectUrl'][] = array(
            'name' => 'MaxLength',
            'value' => '200',
            'message' => "redirectUrl is must be between 1 and 200 characters"
        );

        $validationData['shopLogoUrl'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "shopLogoUrl is required"
        );

        $validationData['shopLogoUrl'][] = array(
            'name' => 'MaxLength',
            'value' => '200',
            'message' => "shopLogoUrl is must be between 1 and 200 characters"
        );

        $validationData['bankAccount'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "bankAccount is required"
        );

        $validationData['locale'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "Locale is required"
        );

        $validationData['locale'][] = array(
            'name' => 'Callback',
            'value' => 'Upg\Library\Locale\Codes::validateLocale',
            'message' => "Locale must be certain values"
        );

        $validationData['erpSystem'][] = array(
            'name' => 'MaxLength',
            'value' => '200',
            'message' => "erpSystem is must be between 1 and 200 characters"
        );

        return $validationData;
    }

    public static function validateDeliveryTime($value)
    {
        return Constants::validateConstant(__CLASS__, $value, static::TAG_DELIVERY_TIME);
    }
}
