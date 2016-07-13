<?php

namespace Upg\Library;

/**
 * Class config
 * Stores configuration for the API and is used in most all requests
 * @package Upg\Library
 */
class Config
{
    /**
     * @link https://github.com/Seldaek/monolog/blob/master/src/Monolog/Logger.php
     */
    const LOG_LEVEL_INFO = 200;
    /**
     * @link https://github.com/Seldaek/monolog/blob/master/src/Monolog/Logger.php
     */
    const LOG_LEVEL_WARNING = 300;
    /**
     * @link https://github.com/Seldaek/monolog/blob/master/src/Monolog/Logger.php
     */
    const LOG_LEVEL_ERROR = 400;
    /**
     * @link https://github.com/Seldaek/monolog/blob/master/src/Monolog/Logger.php
     */
    const LOG_LEVEL_DEBUG = 100;

    /**
     * This is the merchant password for the Mac Calculation
     * @var string
     */
    private $merchantPassword;

    /**
     * This is the merchantID assigned by PayCo.
     * @var string
     */
    private $merchantID;

    /**
     * This is the store ID of a merchant assigned by PayCo as a merchant can have more than one store.
     * @var string
     */
    private $storeID;

    /**
     * Is logging enabled
     * @var bool
     */
    private $logEnabled = false;

    /**
     * The log level please see
     * @link https://github.com/Seldaek/monolog/blob/master/src/Monolog/Logger.php
     * @var int
     */
    private $logLevel;

    /**
     * Main Log Location
     * @var
     */
    private $logLocationMain;

    /**
     * File path tp location for request logging
     * @var string
     */
    private $logLocationRequest;

    /**
     * File path tp location for MNS logging
     * @var string
     */
    private $logLocationMNS;

    /**
     * File path to location for Callback logging
     * @var string
     */
    private $logLocationCallbacks;

    /**
     * Default risk class for all requests
     * @var int
     */
    private $defaultRiskClass = Risk\RiskClass::RISK_CLASS_DEFAULT;

    /**
     * Default locale for transactions
     * @see Locale\Codes
     * @link http://www.manula.com/manuals/payco/payment-api/2.0/en/topic/supported-languages
     * @var string
     */
    private $defaultLocale;

    /**
     * Automatically add salt to requests
     * @var bool
     */
    private $sendRequestsWithSalt = true;

    /**
     * Base url for the API such as:
     * https://www.payco-sandbox.de/2.0/
     * https://www.pay-co.net/2.0/f
     * @var string
     */
    private $baseUrl;

    /**
     * Constructor can pass in a assertive array with config
     * @see config::setData
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        if (!empty($config)) {
            $this->setData($config);
        }

        return $this;
    }

    /**
     * Mass set configuration option using an assertive array
     * array
     *  ['merchantPassword'] string This is the merchant password for mac calculation
     *  ['merchantID'] string This is the merchantID assigned by PayCo.
     *  ['storeID'] string This is the store ID of a merchant.
     *  ['logEnabled'] bool Should logging be enabled
     *  ['logLevel'] int Log level
     *  ['logLocationMain'] string Main log Location
     *  ['logLocationRequest'] string Log location for API requests
     *  ['logLocationMNS'] string Log for MNS asynchronous callbacks
     *  ['logLocationCallbacks'] string Log location for synchronous callbacks
     *  ['defaultRiskClass'] string Default risk class
     *  ['defaultLocale'] string Default locale
     *  ['sendRequestsWithSalt'] bool Automatically add salt to requests
     *  ['baseUrl'] string Base URL of requests
     * @param array $config
     * @return $this
     */
    public function setData(array $config)
    {
        foreach($config as $key=>$value)
        {
            if(property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }

        return $this;
    }

    public function getConfigData()
    {
        $reflector = new \ReflectionClass($this);
        $properties = $reflector->getProperties();

        $configData = array();

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $configData[$property->getName()] = $property->getValue($this);
            $property->setAccessible(false);
        }

        return $configData;
    }

    /**
     * Get the merchant password
     * @return string
     */
    public function getMerchantPassword()
    {
        return $this->merchantPassword;
    }

    /**
     * Get the merchant Id
     * @return string
     */
    public function getMerchantID()
    {
        return $this->merchantID;
    }

    /**
     * Return the store Id
     * @return string
     */
    public function getStoreID()
    {
        return $this->storeID;
    }

    /**
     * Check if log is enabled
     * @return bool
     */
    public function getLogEnabled()
    {
        return $this->logEnabled;
    }

    /**
     * Get the log level
     * @return int
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * Get the main log location
     * @return string
     */
    public function getLogLocationMain()
    {
        return $this->logLocationMain;
    }

    /**
     * Get the request log Location
     * @return string
     */
    public function getLogLocationRequest()
    {
        return $this->logLocationRequest;
    }

    /**
     * Get the MNS logger location path
     * @return string
     */
    public function getLogLocationMNS()
    {
        return $this->logLocationMNS;
    }

    /**
     * Return the log location
     * @return string
     */
    public function getLogLocationCallbacks()
    {
        return $this->logLocationCallbacks;
    }

    /**
     * Get default risk class
     * @return int
     */
    public function getDefaultRiskClass()
    {
        return $this->defaultRiskClass;
    }

    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * Should requests be salted
     * @see Config::sendRequestsWithSalt
     * @return bool
     */
    public function isSendRequestsWithSalt()
    {
        //normalise to a bool
        return ($this->sendRequestsWithSalt?true:false);
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
}
