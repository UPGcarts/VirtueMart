<?php

namespace Upg\Library\Request;

/**
 * Interface RequestInterface
 * Interface for the request and json objects to allow for validation, serialization
 * And object type integrity for methods that deal with requests
 * @package Upg\Library\Request
 */
interface RequestInterface
{

    /**
     * String with the visitor code that should handle serilization etc json,post etc
     * @return string
     */
    public function getSerialiseType();

    /**
     * Return array with the data to be serialized
     * @return array
     */
    public function getSerializerData();

    /**
     * Return the data in the objects for validation
     * @return array
     */
    public function toArray();

    /**
     * Return data on how the class should be validated
     * @return array
     */
    public function getValidationData();

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
    public function customValidation();
}
