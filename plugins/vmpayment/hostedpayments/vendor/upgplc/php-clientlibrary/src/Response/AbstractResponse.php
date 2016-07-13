<?php
namespace Upg\Library\Response;

use Upg\Library\Config;

/**
 * Class AbstractResponse
 * Abstract response do object type can be checked
 * Also implements the main setter when instantiated along with the property getters
 * @package Upg\Library\Response
 */
abstract class AbstractResponse
{
    /**
     * Stores the config for the API
     * @var Config
     */
    protected $config;

    /**
     * 0 means OK, any other code means error
     * @var int
     */
    protected $resultCode;

    /**
     * Details about an error, otherwise not present
     * @var string
     */
    protected $message;

    /**
     * Contains extra data from the requested
     * @var array
     */
    protected $responseData;

    /**
     * A random number to guarantee the uniqueness of the message
     * @var string
     */
    protected $salt;

    /**
     * Returned mac of the class
     * @var string
     */
    protected $mac;

    public function __construct(Config $config, array $data = array())
    {
        $this->config = $config;
        $this->responseData = array();

        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $this->setData($key, $value);
            }
        }
    }

    /**
     * Set data on the response object
     * @param string $key
     * @param mixed $value
     */
    protected function setData($key, $value)
    {
        //ok check if class has property
        if (property_exists($this, $key)) {
            //ok its a property
            $class = new \ReflectionClass($this);
            $property = $class->getProperty($key);
            /**
             * @var \ReflectionProperty $property
             */
            $property->setAccessible(true);
            $property->setValue($this, $value);
            $property->setAccessible(false);

        } else {
            $this->responseData[$key] = $value;
        }
    }

    /**
     * Get response parameter with a key
     * @param $key
     * @return mixed
     */
    public function getData($key)
    {
        if (property_exists($this, $key)) {
            $class = new \ReflectionClass($this);
            $property = $class->getProperty($key);
            /**
             * @var \ReflectionProperty $property
             */
            $property->setAccessible(true);
            return $property->getValue($this);
            $property->setAccessible(false);
        }

        return $this->responseData[$key];
    }

    /**
     * Returns all data as an assotive array
     * Method can be specified to return data in the responseData property
     * Or all values
     * @param bool|true $extendedValuesOnly if true only return values in responseData other wise return all data
     * @return array
     */
    public function getAllData($extendedValuesOnly = true)
    {
        if ($extendedValuesOnly) {
            return $this->responseData;
        }

        return array_merge(
            $this->responseData,
            array(
                'config' => $this->config,
                'resultCode' => $this->resultCode,
                'message' => $this->message,
                'salt' => $this->salt,
                'mac' => $this->mac,
                )
        );
    }

    /**
     * Get attribute wrapper
     *
     * @param   string $method
     * @param   array $args
     * @return  mixed
     */
    public function __call($method, $args)
    {
        if (substr($method, 0, 3) == 'get') {
            $key = lcfirst(substr($method, 3));
            return $this->getData($key);
        }

        return false;
    }
}
