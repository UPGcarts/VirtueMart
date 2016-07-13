<?php

namespace Upg\Library\Api\Exception;

/**
 * Class RequestNotSet
 * Raised if the request is not sent
 * @package Upg\Library\Api\Exception
 */
class RequestNotSet extends AbstractException
{
    public function __construct()
    {
        parent::__construct("Request Object has not been set or is invalid");
    }
}
