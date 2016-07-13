<?php

namespace Upg\Library\Response\Unserializer\Handler;

use Upg\Library\Response\Unserializer\Processor;

interface UnserializerInterface
{
    /**
     * Return the string of the property that the unserializer will handle
     * Please note the method can return an array of strings
     * @return string | array
     */
    public function getAttributeNameHandler();

    /**
     * @param $value
     * @param Processor $processor
     * @return \Upg\Library\Request\RequestInterface
     */
    public function unserializeProperty(Processor $processor, $value);
}
