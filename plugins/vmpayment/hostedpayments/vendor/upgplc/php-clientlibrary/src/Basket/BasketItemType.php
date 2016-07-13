<?php

namespace Upg\Library\Basket;

use Upg\Library\Validation\Helper\Constants;

/**
 * Class BasketItemType
 * @package Upg\Library\Basket
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/json-objects
 * Basket Item Type class definitions
 */
class BasketItemType
{
    /**
     * Default basket item type
     */
    const BASKET_ITEM_TYPE_DEFAULT = 'DEFAULT';

    /**
     * Type for basket item which represents the shipping cost
     */
    const BASKET_ITEM_TYPE_SHIPPINGCOST = 'SHIPPINGCOSTS';

    /**
     * Type for an basket item which represents any coupons applied to the order
     */
    const BASKET_ITEM_TYPE_COUPON = 'COUPON';

    public static function validateBasketItemType($value)
    {
        return Constants::validateConstant(__CLASS__, $value, 'BASKET_ITEM_TYPE');
    }
}