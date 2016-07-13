<?php
namespace Upg\Library\Callback\Exception;

abstract class AbstractException extends \Upg\Library\AbstractException
{
    public function __construct($message = 'Callback Exception')
    {
        parent::__construct($message);
    }
}
