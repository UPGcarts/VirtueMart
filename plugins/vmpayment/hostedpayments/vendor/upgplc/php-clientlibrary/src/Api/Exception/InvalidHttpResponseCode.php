<?php

namespace Upg\Library\Api\Exception;

/**
 * Class InvalidHttpResponseCode
 * Raised if an invalid http status code is received
 * @package Upg\Library\Api\Exception
 */
class InvalidHttpResponseCode extends AbstractException
{
    public function __construct($httpCode, $rawReponse)
    {
        parent::__construct(
            "Non expected http code: ".$httpCode,
            $httpCode,
            $rawReponse,
            null,
            $httpCode
        );
    }
}
