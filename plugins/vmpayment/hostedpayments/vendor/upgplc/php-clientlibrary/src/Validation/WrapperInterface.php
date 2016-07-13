<?php

namespace Upg\Library\Validation;

use Upg\Library\Request\RequestInterface as RequestInterface;

/**
 * Interface WrapperInterface
 * Interface for the validator
 * @package Upg\Library\Validation
 */
interface WrapperInterface
{
    public function getValidator(RequestInterface $request);
}
