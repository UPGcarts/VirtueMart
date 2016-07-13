<?php

namespace Upg\Library\Serializer\Visitors;

use Upg\Library\Request\Objects\Attributes\FileInterface;
use Upg\Library\Serializer\Visitors\VisitorInterface as VisitorInterface;
use Upg\Library\Request\RequestInterface as RequestInterface;
use Upg\Library\Serializer\Serializer as Serializer;

/**
 * Class MultipartFormData
 * This class will not serialize the top most part of an request but ensure that the serializer will
 * serilize child objects.
 * As the intention is to feed the array from this in to curl directly
 * @package Upg\Library\Serializer\Visitors
 */
class MultipartFormData extends AbstractVisitor
{

    public function __construct()
    {
        $this->fileFields = array();
    }

    /**
     * The method by which the object is visited and is serialized
     * @param RequestInterface $object
     * @param Serializer $serializer
     * @return array Returns a formatted string such as json, post data from the object
     * @throws \Upg\Library\AbstractException Should throw exception if there is an error
     */
    public function visit(RequestInterface $object, Serializer $serializer)
    {
        $data = $object->getSerializerData();

        $data = $this->checkSerializeArray($data, $serializer);

        /**
         * Do not serialize the whole data instead return it as an array
         */
        return $data;
    }

    /**
     * Returns the datatype the visitor outputs such as xml,json or post form
     * @return string
     */
    public function getType()
    {
        return 'multipart';
    }

    protected function checkSerializeArray($data, Serializer $serializer)
    {
        $replace = array();
        foreach ($data as $key => $value) {
            if ($value instanceof FileInterface) {
                $data[$key] = 'FILE::'.$value->getPath();
                $this->fileFields[] = $key;
            } elseif ($this->checkIfWalkerIsNeeded($value)) {
                $data[$key] = $serializer->serialize($value);
            }
        }

        return $data;
    }
}
