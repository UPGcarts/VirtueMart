<?php
namespace Upg\Library\Response\Unserializer;

class Factory
{
    /**
     * Get the processor with all default unserializer handlers in the API
     * @return Processor
     */
    public static function getProcessor()
    {
        $instance = new Processor();
        $instance->addUnserializerHandler(new Handler\Address());
        $instance->addUnserializerHandler(new Handler\Amount());
        $instance->addUnserializerHandler(new Handler\ArrayPaymentInstruments());
        $instance->addUnserializerHandler(new Handler\Company());
        $instance->addUnserializerHandler(new Handler\PaymentInstruments());
        $instance->addUnserializerHandler(new Handler\Person());

        return $instance;
    }
}
