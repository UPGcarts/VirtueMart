<?php

namespace Upg\Library\Request\Objects\Attributes;

use Upg\Library\Request\Objects\Attributes\Exception\FileCouldNotBeFound;

/**
 * Class File
 * For file parameters in the request
 * @package Upg\Library\Request\Objects\Attributes
 */
class File implements FileInterface
{
    private $path;

    /**
     * Set the file path
     * @param $path
     * @return $this
     * @throws FileCouldNotBeFound
     */
    public function setPath($path)
    {
        if (!file_exists($path)) {
            throw new FileCouldNotBeFound($path);
        }

        $this->path = $path;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    /**
     * Return base 64 encoded string with the file
     * @return string
     */
    public function getFileBase64String()
    {
        $data = file_get_contents($this->path);
        if (empty($data)) {
            return '';
        }

        return base64_encode($data);
    }
}
