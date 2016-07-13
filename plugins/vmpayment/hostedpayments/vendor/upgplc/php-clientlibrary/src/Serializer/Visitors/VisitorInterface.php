<?php

namespace Upg\Library\Serializer\Visitors;

use Upg\Library\Request\RequestInterface as RequestInterface;
use Upg\Library\Serializer\Serializer as Serializer;

/**
 * Interface VisitorInterface
 * Interface for visitors to use
 * @package Upg\Library\Serializer\Visitors
 */
interface VisitorInterface
{
    /**
     * The method by which the object is visited and is serialized
     * @param RequestInterface $object
     * @param Serializer $serializer
     * @return string|array Returns a formatted string such as json, post data from the object
     * @throws \Upg\Library\AbstractException Should throw exception if there is an error
     */
    public function visit(RequestInterface $object, Serializer $serializer);

    /**
     * Returns the datatype the visitor outputs such as xml,json or post form
     * @return string
     */
    public function getType();
}
