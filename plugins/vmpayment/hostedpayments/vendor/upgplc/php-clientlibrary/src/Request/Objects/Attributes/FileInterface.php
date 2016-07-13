<?php

namespace Upg\Library\Request\Objects\Attributes;

/**
 * Interface FileInterface
 * Interface for the file
 * @package Upg\Library\Request\Objects\Attributes
 */
interface FileInterface
{
    /**
     * Get the base64 encoded file
     * @return string
     */
    public function getFileBase64String();

    /**
     * Get Path of the file
     * @return string
     */
    public function getPath();
}
