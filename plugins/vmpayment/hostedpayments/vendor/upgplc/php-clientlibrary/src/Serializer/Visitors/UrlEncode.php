<?php

namespace Upg\Library\Serializer\Visitors;

use Upg\Library\Serializer\Visitors\VisitorInterface as VisitorInterface;
use Upg\Library\Request\RequestInterface as RequestInterface;
use Upg\Library\Serializer\Serializer as Serializer;

/**
 * Class UrlEncode
 * Visitor for serializing to UrlEcoded forms
 * @package Upg\Library\Serializer\Visitors
 */
class UrlEncode extends AbstractVisitor
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

        return http_build_query($data);

    }

    /**
     * Returns the datatype the visitor outputs such as xml,json or post form
     * @return string
     */
    public function getType()
    {
        return 'urlencode';
    }
}
