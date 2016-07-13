<?php

namespace Upg\Library\Api\Exception;


use Upg\Library\Response\FailureResponse;

/**
 * Class ApiError
 * Raised when API responds with an error for a call
 * @package Upg\Library\Api\Exception
 */
class ApiError extends AbstractException
{
    /**
     * @var FailureResponse
     */
    private $response;

    public function __construct(FailureResponse $response, $rawResponse, $httpCode)
    {
        $this->response = $response;
        parent::__construct(
            $response->getData('message'),
            $response->getData('resultCode'),
            $rawResponse,
            $response,
            $httpCode
        );
    }
}
