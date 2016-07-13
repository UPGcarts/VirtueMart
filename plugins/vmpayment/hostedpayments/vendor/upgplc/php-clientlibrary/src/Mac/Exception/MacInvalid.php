<?php

namespace Upg\Library\Mac\Exception;

/**
 * Class MacInvalid
 * Raised if MAC is invalid
 * @package Upg\Library\Mac\Exception
 */
class MacInvalid extends \Upg\Library\AbstractException
{
    public function __construct($expectedMac, $calculatedMac)
    {
        parent::__construct("Invalid Mac expected {$expectedMac} got {$calculatedMac}");
    }
}
