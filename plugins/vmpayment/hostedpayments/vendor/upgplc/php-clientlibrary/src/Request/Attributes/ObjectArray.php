<?php

namespace Upg\Library\Request\Attributes;

use Upg\Library\Request\RequestInterface;

/**
 * Class ObjectArray
 * So collections get serialized correctly the objects should use this class
 * As the array types when a request object contains a field with a collection
 * So the serialization works
 * @package Upg\Library\Request\Attributes
 */
class ObjectArray extends \ArrayIterator implements RequestInterface
{

    protected $data = array();


    /**
     * String with the visitor code that should handle serialization etc json,post etc
     * All arrays in the Payco requests are json arrays
     * @return string
     */
    public function getSerialiseType()
    {
        return 'json';
    }

    /**
     * Return array with the data to be serialized
     * @return array
     */
    public function getSerializerData()
    {
        return $this->getArrayCopy();
    }

    /**
     * Return the data in the objects for validation
     * @return array
     */
    public function toArray()
    {
        return $this->getArrayCopy();
    }

    /**
     * Return data on how the class should be validated
     * @return array
     */
    public function getValidationData()
    {
        return array();
    }

    /**
     * Implemented due to abstract class requiring it
     * @return array
     */
    public function customValidation()
    {
        return array();
    }
}
