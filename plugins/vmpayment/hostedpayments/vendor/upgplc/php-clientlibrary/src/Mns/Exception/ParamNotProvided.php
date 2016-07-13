<?php
namespace Upg\Library\Mns\Exception;

/**
 * Class ParamNotProvided
 * Raised if not all required MNS parameters are not provided
 * @package Upg\Library\Mns\Exception
 */
class ParamNotProvided extends AbstractException
{
    public function __construct($params)
    {
        parent::__construct("The following parameters were not provided or are empty: ".$params);
    }
}
