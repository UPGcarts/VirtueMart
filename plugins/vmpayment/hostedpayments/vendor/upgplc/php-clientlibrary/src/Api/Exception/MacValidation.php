<?php

namespace Upg\Library\Api\Exception;

/**
 * Class MacValidation
 * Raised if the MAC in the response is incorect
 * @package Upg\Library\Api\Exception
 */
class MacValidation extends AbstractException
{
    public function __construct($calculatedMac, $expected, $rawJson)
    {
        parent::__construct("Got $calculatedMac and expected $expected: $rawJson");
    }
}