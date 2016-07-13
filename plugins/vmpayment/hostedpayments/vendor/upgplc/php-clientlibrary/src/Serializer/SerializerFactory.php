<?php

namespace Upg\Library\Serializer;

/**
 * Class SerializerFactory
 * Factory to get the default serializer for the library
 * Is used internally
 * @package Upg\Library\Serializer
 */
class SerializerFactory
{
    /**
     * Get serializer with default visitors
     * @return Serializer
     */
    public static function getSerializer()
    {
        $serializer = new Serializer();

        $serializer->setVisitor(new Visitors\Json())
            ->setVisitor(new Visitors\UrlEncode())
            ->setVisitor(new Visitors\MultipartFormData());

        return $serializer;
    }
}
