<?php
namespace Upg\Library\Response\Unserializer;

use Upg\Library\Response\Unserializer\Handler\UnserializerInterface;

class Processor
{
    private $unserializerVisitors = array();

    /**
     * Add an unserializer handler
     * @param UnserializerInterface $unserializer
     * @return $this
     */
    public function addUnserializerHandler(UnserializerInterface $unserializer)
    {
        $key = $unserializer->getAttributeNameHandler();
        if (is_array($key)) {
            foreach ($key as $extraKey) {
                $this->unserializerVisitors[$extraKey] = $unserializer;
            }
        } else {
            $this->unserializerVisitors[$key] = $unserializer;
        }
        return $this;
    }

    /**
     * Method to do top level unserialization of a json object
     * @param array $data
     * @return array
     */
    public function topLevelUnserialize(array $data)
    {
        foreach ($data as $propertyName => $propertyValue) {
            if (is_array($propertyValue) || is_object($propertyValue)) {
                $data[$propertyName] = $this->unSerialize($propertyName, $propertyValue);
            }
        }

        return $data;
    }

    /**
     * Universalize value to an object if a handler exists
     * Otherwise the raw json decode object will be returned
     * However the child values of the object may be given certain objects
     * @param string $propertyName
     * @param array|object $value
     * @return \Upg\Library\Request\RequestInterface|array
     */
    public function unSerialize($propertyName, $value)
    {
        if (is_array($value)) {
            /**
             * Check if serializable
             */
            foreach ($value as $subPropertyName => $subValue) {
                /**
                 * Exclude sub value
                 */
                if (is_array($subValue) && !is_numeric($subPropertyName)) {
                    $value[$subPropertyName] = $this->unSerialize($subPropertyName, $subValue);
                }
            }
        }

        if (array_key_exists($propertyName, $this->unserializerVisitors)) {
            /**
             * @var UnserializerInterface $unserialize
             */
            $unserialize = $this->unserializerVisitors[$propertyName];
            return $unserialize->unserializeProperty($this, $value);
        }

        //just return the json object as is
        return $this->handleDefaultJson($value);
    }

    private function handleDefaultJson($data)
    {
        //ok check if any sub values can be serialized
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->unSerialize($key, $value);
            }
        }

        return $data;
    }
}