<?php

namespace Upg\Library\Request\Objects;

use Upg\Library\Request\RequestInterface as RequestInterface;

/**
 * Class AbstractObject
 * Abstract class for the json objects used in the requests
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/json-objects
 * @package Upg\Library\Request\Objects
 */
abstract class AbstractObject implements RequestInterface
{
    /**
     * Set data on the object for the unserializer
     * @param array $data
     * @return $this
     */
    public function setUnserializedData(array $data)
    {
        $reflector = new \ReflectionClass($this);

        $properties = $reflector->getProperties();

        foreach ($properties as $property) {
            $key = $property->getName();
            if (array_key_exists($key, $data)) {
                $property->setAccessible(true);
                $property->setValue($this, $data[$key]);
                $property->setAccessible(false);
            }
        }

        return $this;
    }

    /**
     * As all payco objects are of json when inside of a request
     * Simply retun json for all of them
     * @return string
     */
    public function getSerialiseType()
    {
        return 'json';
    }

    /**
     * By default validator data will be same as serilized data
     * However this can be different and classes where it is different shall implement both
     * getSerializerData and toArray separately
     *
     * @return mixed
     */
    public function getSerializerData()
    {
        return $this->toArray();
    }

    /**
     * Return array with validation errors in the following format
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
