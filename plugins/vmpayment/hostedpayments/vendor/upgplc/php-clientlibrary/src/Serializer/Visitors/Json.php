<?php

namespace Upg\Library\Serializer\Visitors;

use Upg\Library\Request\Attributes\ObjectArray;
use Upg\Library\Serializer\Visitors\VisitorInterface as VisitorInterface;
use Upg\Library\Request\RequestInterface as RequestInterface;
use Upg\Library\Serializer\Serializer as Serializer;

/**
 * Class Json
 * Json serializer visitor
 * @package Upg\Library\Serializer\Visitors
 */
class Json extends AbstractVisitor
{
    /**
     * The method by which the object is visited and is serialized
     * @param RequestInterface $object
     * @param Serializer $serializer
     * @return string Returns a formatted string such as json, post data from the object
     * @throws \Upg\Library\AbstractException Should throw exception if there is an error
     */
    public function visit(RequestInterface $object, Serializer $serializer)
    {
        $data = $object->getSerializerData();

        $data = $this->checkSerializeArray($data, $serializer);

        return json_encode($data);

    }

    /**
     * Returns the datatype the visitor outputs such as xml,json or post form
     * @return string
     */
    public function getType()
    {
        return 'json';
    }

    /**
     * For json nested json objects we want to run the json serializer down the tree
     * Instead of using the serializer so parent json contains no escaped strings
     * @param $data
     * @param Serializer $serializer
     * @return mixed
     * @throws \Upg\Library\Serializer\Exception\VisitorCouldNotBeFound
     */
    protected function checkSerializeArray($data, Serializer $serializer)
    {
        $replace = array();
        $processed = false;

        foreach ($data as $key => $value) {
            $processed = true;
            if ($this->checkIfWalkerIsNeeded($value)) {
                if ($value instanceof ObjectArray
                    && $value->getSerialiseType() == $this->getType()
                ) {
                    /** Serialize an array value */
                    $serializedArray = array();
                    foreach ($value as $pos => $arrayValue) {
                        if ($arrayValue instanceof RequestInterface
                            && $value->getSerialiseType() == $this->getType()
                        ) {
                            $tmpData = $arrayValue->getSerializerData();
                            $serializedArray[] = $this->checkSerializeArray($tmpData, $serializer);
                        } else {
                            $serializedArray[] = $value;
                        }
                    }
                    $data[$key] = $serializedArray;
                } elseif ($value instanceof RequestInterface
                    && $value->getSerialiseType() == $this->getType()
                ) {
                    /** @var RequestInterface $value */
                    /**
                     * let the json_encode do serialize
                     * So if it object needs to be serilized to json get the raw data recursively
                     * Checking if other serialise is needed
                     **/
                    $tmpData = $value->getSerializerData();

                    foreach ($tmpData as $tmpKey => $tmpValue) {
                        if ($this->checkIfWalkerIsNeeded($tmpValue)) {
                            $amendedValue = $this->checkSerializeArray($tmpValue, $serializer);
                            $tmpData[$tmpKey] = $amendedValue;
                        }
                    }

                    $data[$key] = $tmpData;

                } else {
                    //let the serializer handle this as the child is a non json object
                    $data[$key] = $serializer->serialize($value);
                }
            }
        }

        if ($data instanceof RequestInterface && !$processed) {
            $tmpData = $data->getSerializerData();

            foreach ($tmpData as $tmpKey => $tmpValue) {
                if ($this->checkIfWalkerIsNeeded($tmpValue)) {
                    $amendedValue = $this->checkSerializeArray($tmpValue, $serializer);
                    $tmpData[$tmpKey] = $amendedValue;
                }
            }

            $data = $tmpData;
        }

        return $data;
    }
}
