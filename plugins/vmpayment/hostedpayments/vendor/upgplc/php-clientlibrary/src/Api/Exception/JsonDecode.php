<?php

namespace Upg\Library\Api\Exception;

/**
 * Class JsonDecode
 * Raised if the JSON response can not be decoded
 * @package Upg\Library\Api\Exception
 */
class JsonDecode extends AbstractException
{
    private $rawString;

    public function __construct($code, $jsonResponse)
    {
        $jsonError = "Unknown";

        switch ($code) {
            case JSON_ERROR_NONE:
                $jsonError = 'No errors';
                break;
            case JSON_ERROR_DEPTH:
                $jsonError = 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $jsonError = 'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $jsonError = 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $jsonError = 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $jsonError = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
        }

        $this->rawString = $jsonResponse;
        parent::__construct("Json Decode Error ($jsonError) For : $jsonResponse", $code);
    }
}
