<?php

namespace Upg\Library\Serializer\Visitors;

use Upg\Library\Serializer\Visitors\VisitorInterface as VisitorInterface;
use Upg\Library\Request\RequestInterface as RequestInterface;
use Upg\Library\Serializer\Serializer as Serializer;

/**
 * Class AbstractVisitor
 * Abstract class for victors
 * @package Upg\Library\Serializer\Visitors
 */
abstract class AbstractVisitor implements VisitorInterface
{

    protected function checkIfWalkerIsNeeded($value)
    {
        if ($value instanceof RequestInterface) {
            return true;
        }

        return false;
    }

    protected function checkSerializeArray($data, Serializer $serializer)
    {
        $replace = array();
        foreach ($data as $key => $value) {
            if ($this->checkIfWalkerIsNeeded($value)) {
                $data[$key] = $serializer->serialize($value);
            }
        }

        return $data;
    }
}
