<?php

namespace Upg\Library\Response\Unserializer\Handler;

use Upg\Library\Request\Objects\PaymentInstrument;
use Upg\Library\Response\Unserializer\Processor;

class PaymentInstruments implements UnserializerInterface
{
    /**
     * Return the string of the property that the unserializer will handle
     * @return string
     */
    public function getAttributeNameHandler()
    {
        return array(
            'paymentInstrument',
        );
    }

    /**
     * @param $value
     * @param Processor $processor
     * @return \Upg\Library\Request\RequestInterface
     */
    public function unserializeProperty(Processor $processor, $value)
    {

        $paymentInstrument = new PaymentInstrument();
        $paymentInstrument->setUnserializedData($value);

        if ($paymentInstrument->getPaymentInstrumentType() == PaymentInstrument::PAYMENT_INSTRUMENT_TYPE_CARD) {
            list($year,$month) = explode('-', $value['validity']);
            $dateTime = new \DateTime();
            $dateTime->setDate($year, $month, 1);
            $paymentInstrument->setValidity($dateTime);
        }

        return $paymentInstrument;
    }
}