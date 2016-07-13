<?php

namespace Upg\Library\Locale;

use Upg\Library\Validation\Helper\Constants;

class Codes
{
    /**
     * Locale : German - Deutsch
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/supported-languages
     */
    const LOCALE_DE = "DE";

    /**
     * Locale : English
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/supported-languages
     */
    const LOCALE_EN = "EN";

    /**
     * Locale : Spanish - Español
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/supported-languages
     */
    const LOCALE_ES = "ES";

    /**
     * Locale : Finnish - Suomi
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/supported-languages
     */
    const LOCALE_FI = "FI";

    /**
     * Locale : French - Français
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/supported-languages
     */
    const LOCALE_FR = "FR";

    /**
     * Locale : Italian - Italiano
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/supported-languages
     */
    const LOCALE_IT = "IT";

    /**
     * Locale : Dutch - Nederlands
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/supported-languages
     */
    const LOCALE_NL = "NL";

    /**
     * Locale : Turkish - Türkçe
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/supported-languages
     */
    const LOCALE_TU = "TU";

    /**
     * Locale : Russian - Русский
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/supported-languages
     */
    const LOCALE_RU = "RU";

    /**
     * Tag for the constraint validator
     */
    const TAG_LOCALE = "LOCALE";

    /**
     * Locale : Portuguese - Português
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/supported-languages
     */
    const LOCALE_PT = "PT";

    public static function validateLocale($value)
    {
        return Constants::validateConstant(__CLASS__, $value, static::TAG_LOCALE);
    }
}
