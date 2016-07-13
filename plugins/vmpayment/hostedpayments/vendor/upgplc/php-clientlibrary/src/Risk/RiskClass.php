<?php

namespace Upg\Library\Risk;

use Upg\Library\Validation\Helper\Constants;

/**
 * Class RiskClass
 * Risk class values that are used in the API
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/json-objects?q=Risk+class
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/gettransactionpaymentmethod
 * @package Upg\Library\Risk
 */
class RiskClass
{
    /**
     * Trusted Risk Class
     */
    const RISK_CLASS_TRUSTED = 0;

    /**
     * Default Risk Class
     */
    const RISK_CLASS_DEFAULT = 1;

    /**
     * High Risk Class
     */
    const RISK_CLASS_HIGH = 2;

    /**
     * Return a ordered array of the risk classes
     * @return array
     */
    public static function getRiskClasses()
    {
        return array(
            "Trusted" => 0,
            "Default" => 1,
            "High" => 2
        );
    }

    public static function validateRiskClass($value)
    {
        return Constants::validateConstant(__CLASS__, $value, 'RISK_CLASS');
    }
}
