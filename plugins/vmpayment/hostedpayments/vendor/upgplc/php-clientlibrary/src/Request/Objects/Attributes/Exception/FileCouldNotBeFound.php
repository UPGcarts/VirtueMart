<?php

namespace Upg\Library\Request\Objects\Attributes\Exception;

/**
 * Class FileCouldNotBeFound
 * Raised if file can not be found
 * @package Upg\Library\Request\Objects\Attributes\Exception
 */
class FileCouldNotBeFound extends AbstractException
{
    public function __construct($filePath = '')
    {
        parent::__construct("File Could not be found: ".$filePath);
    }
}