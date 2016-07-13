<?php

namespace Upg\Library\Serializer\Exception;

/**
 * Class AbstractException
 * Abstract exception for serializer errors
 * @package Upg\Library\Serializer\Exception
 */
abstract class AbstractException extends \Upg\Library\AbstractException
{
    public function __construct($message = 'Serializer Exception')
    {
        parent::__construct($message);
    }
}
