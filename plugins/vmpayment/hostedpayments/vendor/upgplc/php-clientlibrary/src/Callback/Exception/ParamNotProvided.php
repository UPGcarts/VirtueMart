<?php
namespace Upg\Library\Callback\Exception;

class ParamNotProvided extends AbstractException
{
    public function __construct($params)
    {
        parent::__construct("The following parameters were not provided or are empty: ".$params);
    }
}
