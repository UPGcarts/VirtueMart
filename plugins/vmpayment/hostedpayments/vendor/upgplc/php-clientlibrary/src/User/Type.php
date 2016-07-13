<?php
namespace Upg\Library\User;

use Upg\Library\Validation\Helper\Constants;

/**
 * Class PaymentMethods
 * Contains the userType values used in many requests
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/gettransactionpaymentmethod
 * @package Upg\Library\User
 */
class Type
{
    /**
     * Private user type
     */
    const USER_TYPE_PRIVATE = "PRIVATE";

    /**
     * Business user
     */
    const USER_TYPE_BUSINESS = "BUSINESS";

    /**
     * Tag for the validator
     */
    const VALIDATION_TAG_USER_TYPE = "USER_TYPE";

    /**
     * Validate if value is a valid user type
     * @param $value
     * @return bool
     */
    public static function validate($value)
    {
        return Constants::validateConstant(__CLASS__, $value, static::VALIDATION_TAG_USER_TYPE);
    }
}
