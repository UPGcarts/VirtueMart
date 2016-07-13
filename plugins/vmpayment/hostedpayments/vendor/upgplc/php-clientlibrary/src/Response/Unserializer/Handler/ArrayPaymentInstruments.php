<?php

namespace Upg\Library\Response\Unserializer\Handler;

use Upg\Library\Request\Objects\PaymentInstrument;
use Upg\Library\Response\Unserializer\Processor;

class ArrayPaymentInstruments implements UnserializerInterface
{
    /**
     * Return the string of the property that the unserializer will handle
     * @return string
     */
    public function getAttributeNameHandler()
    {
        return array(
            'allowedPaymentInstruments',
            'paymentInstruments',
        );
    }

    /**
     * @param $value
     * @param Processor $processor
     * @return \Upg\Library\Request\RequestInterface
     */
    public function unserializeProperty(Processor $processor, $value)
    {
        $data = array();

        foreach ($value as $paymentInstrumentData) {
            $paymentInstrument = new PaymentInstrument();
            $paymentInstrument->setUnserializedData($paymentInstrumentData);

            if ($paymentInstrument->getPaymentInstrumentType() == PaymentInstrument::PAYMENT_INSTRUMENT_TYPE_CARD) {
                list($year,$month) = explode('-', $paymentInstrumentData['validity']);
                $dateTime = new \DateTime();
                $dateTime->setDate($year, $month, 1);
                $paymentInstrument->setValidity($dateTime);
            }

            $data[] = $paymentInstrument;
        }

        return $data;
    }
}
