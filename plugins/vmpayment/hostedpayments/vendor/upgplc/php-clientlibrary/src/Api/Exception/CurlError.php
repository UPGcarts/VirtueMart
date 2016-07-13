<?php

namespace Upg\Library\Api\Exception;

/**
 * Class CurlError
 * Raised if there is a curl error
 * @package Upg\Library\Api\Exception
 */
class CurlError extends AbstractException
{
    public function __construct($curlErrorMessage, $curlErrorCode, $rawResponse)
    {
        parent::__construct($curlErrorMessage, $curlErrorCode, $rawResponse);
    }
}
