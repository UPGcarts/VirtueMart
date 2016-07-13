<?php
namespace Upg\Library\Mns\Exception;

/**
 * Class AbstractException
 * For MNS exceptions
 * @package Upg\Library\Mns\Exception
 */
abstract class AbstractException extends \Upg\Library\AbstractException
{
    public function __construct($message = 'MNS Exception')
    {
        parent::__construct($message);
    }
}
