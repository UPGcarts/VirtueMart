<?php

namespace Upg\Library\Validation\Helper;

use Upg\Library\Request\RequestInterface as RequestInterface;

/**
 * Class Constants
 * Uses refletion to validate if a given value is equal to specified constants
 * @package Upg\Library\Validation\Helper
 */
class Constants
{
    /**
     * Validate an class constant value using reflection
     * @param string $request String to a request object to be validated
     * @param string $value Value you want to validate
     * @param string $tag String with that the constants begin with
     * @return bool
     */
    public static function validateConstant($request, $value, $tag)
    {
        $reflector = new \ReflectionClass($request);

        $constants = $reflector->getConstants();

        foreach ($constants as $constantName => $constantValue) {
            /**
             * The constant name must start with the tag
             */
            if (stripos($constantName, $tag) === 0) {
                if ($value === $constantValue) {
                    return true;
                }
            }
        }

        return false;

    }

}