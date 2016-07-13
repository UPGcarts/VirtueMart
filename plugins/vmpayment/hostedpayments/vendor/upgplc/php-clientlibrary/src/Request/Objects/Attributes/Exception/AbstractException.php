<?php

namespace Upg\Library\Request\Objects\Attributes\Exception;

abstract class AbstractException extends \Upg\Library\AbstractException
{
    public function __construct($message = 'Attribute Exception')
    {
        parent::__construct($message);
    }
}