<?php

namespace Upg\Library\Industry;

use Upg\Library\Validation\Helper\Constants;

/**
 * Class Codes
 * @package Upg\Library\Industry
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/list-of-branches
 */
class Codes
{
    const INDUSTRY_SERVICE_OTHER = 1;
    const INDUSTRY_BUSINESS_TO_BUSINESS = 2;
    const INDUSTRY_BABY = 3;
    const INDUSTRY_GOVERNMENT_AGENCIES = 4;
    const INDUSTRY_CLOTHES_ACCESSORIES_SHOES = 5;
    const INDUSTRY_EDUCATION = 6;
    const INDUSTRY_BOOKS_MAGAZINES = 7;
    const INDUSTRY_OFFICE_SUPPLIES = 8;
    const INDUSTRY_COMPUTERS_ACCESSORIES_SERVICE = 9;
    const INDUSTRY_PHARMACEUTICALS_MEDICAL_PRODUCTS = 10;
    const INDUSTRY_RETAIL = 11;
    const INDUSTRY_ELECTRONICS_TELECOMMUNICATION = 12;
    const INDUSTRY_VEHICLE_SERVICE_ACCESSORIES = 13;
    const INDUSTRY_VEHICLE_SALES = 14;
    const INDUSTRY_FINANCIAL_SERVICES_PRODUCTS = 15;
    const INDUSTRY_PICTURE_PRINTING_SERVICE = 16;
    const INDUSTRY_GIFTS_FLOWERS = 17;
    const INDUSTRY_HEALTH_CARE_PRODUCTS = 18;
    const INDUSTRY_HOME_GARDEN = 19;
    const INDUSTRY_PETS_ANIMAL = 20;
    const INDUSTRY_COSMETICS_FRAGRANCES = 21;
    const INDUSTRY_FOOD_RETAIL_SERVICES = 23;
    const INDUSTRY_TRAVEL = 24;
    const INDUSTRY_JEWELLERY_WATCHES = 25;
    const INDUSTRY_CHARITABLE_DONATIONS = 26;
    const INDUSTRY_TOYS_HOBBIES = 27;
    const INDUSTRY_SPORTS_OUTDOORS_ITEMS = 28;
    const INDUSTRY_ENTERTAINMENT_MEDIA = 29;

    const VALIDATION_TAG_INDUSTRY = "INDUSTRY";

    public static function validate($value)
    {
        return Constants::validateConstant(__CLASS__, $value, static::VALIDATION_TAG_INDUSTRY);
    }
}
