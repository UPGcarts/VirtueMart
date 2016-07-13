<?php

namespace Upg\Library\Response\Unserializer\Handler;

use Upg\Library\Request\Objects\Person as PersonClass;
use Upg\Library\Response\Unserializer\Processor;

class Person implements UnserializerInterface
{
    /**
     * Return the string of the property that the unserializer will handle
     * @return string
     */
    public function getAttributeNameHandler()
    {
        return 'userData';
    }

    /**
     * @param Processor $processor
     * @param $value
     * @return AmountClass
     */
    public function unserializeProperty(Processor $processor, $value)
    {

        $person = new PersonClass();
        $person->setUnserializedData($value);

        $dob = new \DateTime();

        list($year, $month, $day) = explode('-', $value['dateOfBirth']);

        $dob->setDate($year, $month, $day);

        $person->setDateOfBirth($dob);

        return $person;
    }
}
