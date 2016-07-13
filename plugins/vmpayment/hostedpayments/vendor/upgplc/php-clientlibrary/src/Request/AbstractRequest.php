<?php

namespace Upg\Library\Request;

use Upg\Library\Config;

/**
 * Class AbstractRequest
 * This is the Abstract request class for top level requests
 * @package Upg\Library\Request
 */
abstract class AbstractRequest implements RequestInterface
{
    /**
     * @var Config
     */
    protected $config;

    private $saltValue;

    protected $macValue;

    const SALT_BYTE_LENGTH = 50;

    /**
     * Do the constructor
     * @param Config $config
     */
    public function __construct(Config $config = null)
    {
        if ($config) {
            $this->setConfig($config);
        }
        return $this;
    }

    /**
     * Set the payco config for the request
     * @param Config $config
     * @return $this
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Set the MAC value for the request
     * @param $mac
     */
    public function setMac($mac)
    {
        $this->macValue = $mac;
    }

    /**
     * As most requests objects when serilized must be urlencode
     * Simply urlencode json for all of them unless overwriten
     * @return string
     */
    public function getSerialiseType()
    {
        return 'urlencode';
    }

    /**
     * T
     * @see AbstractRequest::getPreSerializerData
     * @return array
     */
    public function getSerializerData()
    {
        $data = $this->getPreSerializerData();

        $data['merchantID'] = $this->config->getMerchantID();
        $data['storeID'] = $this->config->getStoreID();

        if ($this->config->isSendRequestsWithSalt()) {
            /**
             * Set a salt with varying degrees of cryptographic entirety
             * depending on php environment this code is ran on
             */
            $data['salt'] = $this->getSalt();
        }

        if ($this->macValue) {
            /**
             * Set the mac on the serializer
             */
            $data['mac'] = $this->macValue;
        }

        return $data;
    }

    /**
     * Reset the salt calculation
     */
    public function resetSalt()
    {
        $this->saltValue = null;
    }

    /**
     * Generate and set the salt
     * @return string
     */
    private function getSalt()
    {
        if (empty($this->saltValue)) {
            /**
             * In order of security try the following functions
             * random_bytes - php 7
             * mcrypt_create_iv - php 5.3 and above with mcrypt
             * openssl_random_pseudo_bytes - php 5.3 and upwards with the openssl extension
             * str_shuffle - awful but it works and its available
             */
            if (function_exists('random_bytes')) {
                $bytes = random_bytes(self::SALT_BYTE_LENGTH);
                $this->saltValue = bin2hex($bytes);
            } elseif (function_exists('mcrypt_create_iv')) {
                $bytes = mcrypt_create_iv(self::SALT_BYTE_LENGTH, MCRYPT_DEV_URANDOM);
                $this->saltValue = bin2hex($bytes);
            } elseif (function_exists('openssl_random_pseudo_bytes')) {
                $bytes = openssl_random_pseudo_bytes(self::SALT_BYTE_LENGTH);
                $this->saltValue = bin2hex($bytes);
            } else {
                //todo - Log when this is used because this is less than ideal
                $this->saltValue = substr(
                    str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ$%*-"),
                    0,
                    32
                );
            }
        }
        return $this->saltValue;
    }

    /**
     * In most cases for the validator we want to validate
     * what get serialized any way
     * @return array
     */
    public function toArray()
    {
        return $this->getSerializerData();
    }

    public function getValidationData()
    {
        $validationData = $this->getClassValidationData();

        $validationData['merchantID'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "merchantID is required please provide a config object to the request"
        );

        $validationData['merchantID'][] = array(
            'name' => 'Regex',
            'value' => '/^[0-9]{1,16}$/',
            'message' => "merchantID must be numeric and no more than 16 digits"
        );

        $validationData['storeID'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "storeID is required please provide a config object to the request"
        );

        $validationData['storeID'][] = array(
            'name' => 'Regex',
            'value' => '/^[0-9a-zA-Z ]{1,60}$/',
            'message' => "storeID must be alpha numeric and no more than 60 characters"
        );

        return $validationData;
    }

    /**
     * Must be implemented in classes
     * @return array
     */
    abstract public function getClassValidationData();

    /**
     * Classes must return the data
     * @return array
     */
    abstract public function getPreSerializerData();

    /**
     * Return array with validation errors in the following format
     * Retunr blank for most request objects unless over writen
     * array(
     *  'class Name'=>array
     *      ('value'=> array(
     *                      'message',
     *                      'message'
     *                      )
     *      )
     * )
     * @return array
     */
    public function customValidation()
    {
        return array();
    }

}
