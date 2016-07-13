<?php

namespace Upg\Library\Mac;

use Upg\Library\Config;
use Upg\Library\Mac\Exception\MacInvalid;

/**
 * Class AbstractCalculator
 * Calculates and validate any MAC for request, responses and calls backs
 * @package Upg\Library
 */
abstract class AbstractCalculator
{
    /**
     * The config object
     * @var Config
     */
    protected $config;

    /**
     * Store the data for the calculation array
     * @var array
     */
    protected $calculationArray;


    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Set the data array used for calculation
     * @param array $data
     */
    protected function setCalculationArray(array $data)
    {
        //sort the array
        if (array_key_exists('mac', $data)) {
            unset($data['mac']);
        }
        ksort($data);
        $this->calculationArray = $data;
    }

    /**
     * Calculate the HMAC
     * @return string;
     */
    public function calculateMac()
    {
        $calculationString = '';

        foreach ($this->calculationArray as $value) {
            $calculationString .= preg_replace('/\s/', '', $value);
        }

        return self::hmac($this->config->getMerchantPassword(), $calculationString);
    }

    /**
     * Validate if calculated mac matches expected mac
     * Please note the implementation is not doing == or === check due to potential of a timing attack
     * @param string $expectedMac Expected HMAC
     * @param bool|true $throwException Should failure throw exception
     * @return bool
     * @throws MacInvalid
     */
    public function validate($expectedMac, $throwException = true)
    {
        $flagError = false;

        $calculated = $this->calculateMac();

        if (!is_string($calculated) || !is_string($expectedMac)) {
            $flagError = true;
        }

        if (strlen($calculated) !== strlen($expectedMac)) {
            $flagError = true;
        }

        if ($throwException && $flagError) {
            throw new MacInvalid($expectedMac, $calculated);
        } elseif ($flagError) {
            return false;
        }

        $status = 0;
        for ($i = 0; $i < strlen($expectedMac); $i++) {
            $status |= ord($calculated[$i]) ^ ord($expectedMac[$i]);
        }

        if ($status === 0) {
            return true;
        }

        if ($throwException && $status !== 0) {
            throw new MacInvalid($expectedMac, $calculated);
        }

        return false;
    }

    /**
     * Calculate the Mac
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/php-example
     * @param string $key The merchant password
     * @param string $data The data
     * @return string
     */
    protected static function hmac($key, $data)
    {
        $data = str_replace(array(" ", "\t", "\r", "\n", ' '), "", $data);
        return hash_hmac('sha1', $data, $key);
    }
}
