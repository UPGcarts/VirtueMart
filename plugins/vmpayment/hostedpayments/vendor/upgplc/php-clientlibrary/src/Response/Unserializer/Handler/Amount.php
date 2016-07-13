<?php

namespace Upg\Library\Response\Unserializer\Handler;

use Upg\Library\Request\Objects\Amount as AmountClass;
use Upg\Library\Response\Unserializer\Processor;

class Amount implements UnserializerInterface
{
    /**
     * Return the string of the property that the unserializer will handle
     * @return string
     */
    public function getAttributeNameHandler()
    {
        return array(
            'amount',
        );
    }

    /**
     * @param Processor $processor
     * @param $value
     * @return AmountClass
     */
    public function unserializeProperty(Processor $processor, $value)
    {

        $amount = new AmountClass();
        $amount->setUnserializedData($value);

        return $amount;
    }
}
