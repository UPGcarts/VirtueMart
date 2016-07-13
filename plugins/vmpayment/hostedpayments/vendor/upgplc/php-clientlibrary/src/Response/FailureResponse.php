<?php
namespace Upg\Library\Response;

use Upg\Library\Config;
use Upg\Library\Error\Codes;

/**
 * Class SuccessResponse
 * Used for failure and error responses
 * @see AbstractResponse
 * @package Upg\Library\Response
 */
class FailureResponse extends AbstractResponse
{
    private $errorStatusMessage;

    public function __construct(Config $config, array $data = array())
    {
        if (array_key_exists('resultCode', $data)) {
            $this->errorStatusMessage = Codes::getErrorName($data['resultCode']);
        }
        parent::__construct($config, $data);
    }

    /**
     * Return the error status message from the library
     * Please note the message field will contain the error from payco
     * @see AbstractResponse::message
     * @return string
     */
    public function getErrorStatusMessage()
    {
        return $this->errorStatusMessage;
    }
}
