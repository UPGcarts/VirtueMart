<?php

namespace Upg\Library\Api\Exception;

use Upg\Library\Response\FailureResponse;

/**
 * Class AbstractException
 * Abstract Exception for the API
 * @package Upg\Library\Api\Exception
 */
abstract class AbstractException extends \Exception
{
    private $rawResponse;
    private $parsedResponse;
    private $httpCode;

    public function __construct($message, $code = 0, $rawResponse = '', $parsedResponse = '', $httpCode = 0)
    {
        $this->rawResponse = $rawResponse;
        $this->parsedResponse = $parsedResponse;
        $this->httpCode = $httpCode;

        parent::__construct($message, $code);
    }

    /**
     * @return string
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * @return FailureResponse
     */
    public function getParsedResponse()
    {
        return $this->parsedResponse;
    }

    public function getHttpCode()
    {
        return $this->httpCode;
    }
}
