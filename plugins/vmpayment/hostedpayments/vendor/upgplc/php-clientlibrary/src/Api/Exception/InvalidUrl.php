<?php

namespace Upg\Library\Api\Exception;

/**
 * Class InvalidUrl
 * Raised if the URL for the API is invalid
 * @package Upg\Library\Api\Exception
 */
class InvalidUrl extends AbstractException
{
    public function __construct($url)
    {
        parent::__construct("URL is invalid: ".$url);
    }
}
