<?php

namespace Upg\Library\Serializer;

use Upg\Library\Request\RequestInterface as RequestInterface;
use Upg\Library\Serializer\Exception\VisitorCouldNotBeFound;
use Upg\Library\Serializer\Visitors\VisitorInterface as VisitorInterface;

/**
 * Class Serializer
 * Main serializer invoker
 * @package Upg\Library\Serializer
 */
class Serializer
{
    /**
     * Array of vistor classes for serialisation
     * @var array
     */
    private $visitors = array();

    /**
     * Serialize an object with the appropriate visitor
     * @param RequestInterface $object
     * @return string
     * @throws \Upg\Library\AbstractException Should throw exception if there is an error
     */
    public function serialize(RequestInterface $object)
    {
        if (array_key_exists($object->getSerialiseType(), $this->visitors)) {
            $visitor = $this->visitors[$object->getSerialiseType()];
            return $visitor->visit($object, $this);
        } else {
            throw new VisitorCouldNotBeFound($object->getSerialiseType(), $object);
        }

    }

    /**
     * Set visitor
     * @param VisitorInterface $visitor
     * @return $this
     */
    public function setVisitor(VisitorInterface $visitor)
    {
        $this->visitors[$visitor->getType()] = $visitor;
        return $this;
    }
}
