<?php

namespace Upg\Library\Response\Unserializer\Handler;

use Upg\Library\Request\Objects\Company as CompanyClass;
use Upg\Library\Response\Unserializer\Processor;

class Company implements UnserializerInterface
{
    /**
     * Return the string of the property that the unserializer will handle
     * @return string
     */
    public function getAttributeNameHandler()
    {
        return array(
            'companyData',
        );
    }

    /**
     * @param Processor $processor
     * @param $value
     * @return AmountClass
     */
    public function unserializeProperty(Processor $processor, $value)
    {

        $company = new CompanyClass();
        $company->setUnserializedData($value);

        return $company;
    }
}
