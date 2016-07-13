<?php

namespace Upg\Library\Request\Objects;

use Upg\Library\Validation\Helper\Regex;

/**
 * Class Address
 * For JSON address objects
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/json-objects
 * @package Upg\Library\Request\Objects
 */
class Address extends AbstractObject
{
    /**
     * @var string Alpha numerical string with the street name no more than 80 character
     */
    private $street;

    /**
     * @var string Alpha numerical string with the house number name no more than 32 character
     */
    private $no = '0000';

    /**
     * @var string Alpha numerical string with the zip/postal code no more than 16 character
     */
    private $zip;

    /**
     * @var string Alpha numerical string with the city no more than 80 character
     */
    private $city;

    /**
     * @var string Alpha numerical string with the state no more than 80 character
     */
    private $state;

    /**
     * @var string Alpha numerical string with the ISO 3166 no more than 2 character
     */
    private $country;

    /**
     * Set the street
     * @param $street
     * @return $this
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * Get the set street
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set the House number
     * @param string $no
     * @return $this
     */
    public function setNo($no)
    {
        $this->no = $no;
        return $this;
    }

    /**
     * Get House number
     * @return string
     */
    public function getNo()
    {
        return $this->no;
    }

    /**
     * Set ZIP/Postal Code
     * @param string $zip
     * @return $this
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * Get ZIP/Postal Code
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set the city
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Return the city
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the state
     * @param string $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Return the state
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set the country
     * @param string $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get the set country
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Convert to array for validator
     * @return array
     */
    public function toArray()
    {
        $return = array(
            'street' => $this->street,
            'no' => $this->no,
            'zip' => $this->zip,
            'city' => $this->city,
            'country' => $this->getCountry(),
        );

        if ($this->state) {
            $return['state'] = $this->state;
        }

        return $return;
    }

    /**
     * Provide validation information to the validator
     * @return array
     */
    public function getValidationData()
    {
        $validationData = array();

        $validationData['street'][] = array('name' => 'required', 'value' => null, 'message' => "Street is required");
        $validationData['street'][] = array(
            'name' => 'Regex',
            'value' => Regex::REGEX_FULL_ALPHANUMERIC,
            'message' => "Street must be alpha numeric"
        );
        $validationData['street'][] = array(
            'name' => 'MaxLength',
            'value' => '80',
            'message' => "Street must be between 1 and 80 characters"
        );

        $validationData['no'][] = array('name' => 'required', 'value' => null, 'message' => "House number is required");
        $validationData['no'][] = array(
            'name' => 'Regex',
            'value' => Regex::REGEX_FULL_ALPHANUMERIC,
            'message' => "House number must be alpha numeric"
        );
        $validationData['no'][] = array(
            'name' => 'MaxLength',
            'value' => '32',
            'message' => "House number must be between 1 and 32 characters"
        );

        $validationData['zip'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "ZIP/Postal Code is required"
        );
        $validationData['zip'][] = array(
            'name' => 'Regex',
            'value' => Regex::REGEX_FULL_ALPHANUMERIC,
            'message' => "ZIP/Postal must be alpha numeric"
        );
        $validationData['zip'][] = array(
            'name' => 'MaxLength',
            'value' => '16',
            'message' => "ZIP/Postal must be between 1 and 16 characters"
        );

        $validationData['city'][] = array('name' => 'required', 'value' => null, 'message' => "City is required");
        $validationData['city'][] = array(
            'name' => 'Regex',
            'value' => Regex::REGEX_FULL_ALPHANUMERIC,
            'message' => "City must be alpha numeric"
        );
        $validationData['city'][] = array(
            'name' => 'MaxLength',
            'value' => '80',
            'message' => "City must be between 1 and 80 characters"
        );

        $validationData['country'][] = array('name' => 'required', 'value' => null, 'message' => "Country is required");
        $validationData['country'][] = array(
            'name' => 'Regex',
            'value' => '/^[A-Zz-z]{2}$/',
            'message' => "Country must be an 2 letter ISO 3166 code"
        );

        $validationData['state'][] = array(
            'name' => 'Regex',
            'value' => Regex::REGEX_FULL_ALPHA,
            'message' => "State must be alpha only"
        );
        $validationData['state'][] = array(
            'name' => 'MaxLength',
            'value' => '80',
            'message' => "State must between 1 and 80 characters"
        );

        return $validationData;

    }
}
