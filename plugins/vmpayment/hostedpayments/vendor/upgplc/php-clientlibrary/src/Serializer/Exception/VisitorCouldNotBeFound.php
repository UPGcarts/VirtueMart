<?php

namespace Upg\Library\Serializer\Exception;

use Upg\Library\Request\RequestInterface as RequestInterface;

/**
 * Class VisitorCouldNotBeFound
 * Raised if serializer could not be found
 * @package Upg\Library\Serializer\Exception
 */
class VisitorCouldNotBeFound extends AbstractException
{
    public function __construct($lookupCode, RequestInterface $object)
    {
        parent::__construct("Serializer could not be found: " . $lookupCode);
    }
}
