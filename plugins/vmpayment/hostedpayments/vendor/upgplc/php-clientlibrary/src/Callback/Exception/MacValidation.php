<?php
namespace Upg\Library\Callback\Exception;

class MacValidation extends AbstractException
{
    public function __construct($calculated, $sentMac, array $data)
    {
        $string = json_encode($data);
        parent::__construct("Mac Validation Error Expected $sentMac got $calculated Data: ".$string);
    }
}
