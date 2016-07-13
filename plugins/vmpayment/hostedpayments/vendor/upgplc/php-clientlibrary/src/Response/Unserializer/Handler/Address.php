<?php

namespace Upg\Library\Response\Unserializer\Handler;

use Upg\Library\Request\Objects\Address as AddressClass;
use Upg\Library\Response\Unserializer\Processor;

class Address implements UnserializerInterface
{
    /**
     * Return the string of the property that the unserializer will handle
     * @return string
     */
    public function getAttributeNameHandler()
    {
        return array(
            'billingAddress',
            'shippingAddress'
        );
    }

    /**
     * @param Processor $processor
     * @param $value
     * @return AmountClass
     */
    public function unserializeProperty(Processor $processor, $value)
    {

        $address = new AddressClass();
        $address->setUnserializedData($value);

        return $address;
    }
}
