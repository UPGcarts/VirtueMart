<?php
namespace Upg\Library\Error;

/**
 * Class Codes
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/error-codes
 * @package Upg\Library\Error
 */
class Codes
{
    const ERROR_UNKNOWN = 1000;
    const ERROR_MAC = 1001;
    const ERROR_INVALID_REQUEST = 1002;

    const ERROR_PAYMENT_METHOD_UNAVAILABLE = 2000;
    const ERROR_PAYMENT_METHOD_REJECTED = 2001;
    const ERROR_TRANSACTION_METHOD_CALL_NOT_ALLOWED = 2002;
    const ERROR_TRANSACTION_EXPIRED = 2003;
    const ERROR_TRANSACTION_ORDER_NUMBER_NOT_FOUND = 2004;
    const ERROR_TRANSACTION_IN_PROGRESS = 2005;
    const ERROR_TRANSACTION_ALREADY_FINISHED = 2023;
    const ERROR_TRANSACTION_CAPTURE_NOT_ALLOWED_IN_STATE = 2030;

    const ERROR_SHOP_NOT_FOUND = 2006;
    const ERROR_SHOP_NOT_ACTIVE = 2007;

    const ERROR_ORDER_EXISTS = 2008;
    const ERROR_ORDER_ALREADY_CAPTURED = 2028;
    const ERROR_ORDER_ALREADY_CAPTURED_WITH_DIFFERENT_AMOUNT = 2029;

    const ERROR_CREDIT_CARD_EXPIRED = 2010;
    const ERROR_CREDIT_CARD_NUMBER_NOT_MATCH_ISSUER = 2011;
    const ERROR_CREDIT_CARD_ALREADY_STORED_FOR_USER = 2012;
    const ERROR_CREDIT_BANK_ACCOUNT_ALREADY_STORED_FOR_USER = 2013;

    const ERROR_USER_ALREADY_EXISTS = 2014;
    const ERROR_USER_NOT_FOUND = 2015;

    const ERROR_REFUND_MUST_NOT_BE_GREATER_THAN_CAPTURE = 2016;
    const ERROR_PAYMENT_DECLINED_FRAUD = 2017;
    const ERROR_REFUND_FAILED = 2018;

    const ERROR_CAPTUREID_NOT_FOUND = 2019;
    const ERROR_MERCHANT_CREATION_NOT_ALLOWED = 2020;

    const ERROR_MSA_ALREADY_EXISTS = 2021;
    const ERROR_SHOP_ALREADY_EXISTS = 2022;
    const ERROR_INVALID_TOKEN = 2024;

    const ERROR_MERCHANT_NOT_ALLOWED_TO_MAKE_CALL = 2025;
    const ERROR_PAYMENT_INSTRUMENT_UNKNOWN = 2026;

    const ERROR_PAYMENT_METHODS_UNAVAILABLE_CONFIG = 2027;

    const ERROR_CALLBACK_PARAMETER_FORMAT = 6001;
    const ERROR_CALLBACK_UNKNOWN = 6002;
    const ERROR_CALLBACK_TRANSACTION_PAYMENT_METHOD_NOT_ALLOWED = 6003;

    const ERROR_CALLBACK_SYSTEM_TIMEOUT_NORESPONSE = 6004;
    const ERROR_CALLBACK_CANCELED_USER = 6005;
    const ERROR_CALLBACK_AUTHENTICATION_FAILED = 6006;
    const ERROR_CALLBACK_BLOCK_STOLEN_DECLINED_NOT_REASON = 6007;
    const ERROR_CALLBACK_NO_JS_USER_BROWSER = 6008;
    const ERROR_CALLBACK_OVERLOAD_PROCESSING_NOT_AVAILABLE = 6009;
    const ERROR_CALLBACK_INVALID_AMOUNT = 6010;
    const ERROR_CALLBACK_FRAUD = 6011;

    const ERROR_CALLBACK_COMMUNICATION = 6012;
    const ERROR_CALLBACK_RESERVATION_EXPIRED = 6013;

    const ERROR_CALLBACK_CONFIGURATION = 6014;

    const ERROR_CALLBACK_PARTIAL_CAPTURE_NOT_ALLOWED = 6015;

    const ERROR_CALLBACK_THIRD_PARTY = 6016;

    const ERROR_CALLBACK_NO_FUNDS = 6017;
    const ERROR_CALLBACK_PARTIAL_REFUND_FAILED = 6018;

    /**
     * Any code between 0 and 999 is not an error
     */
    const CODE_NON_ERROR_START = 0;
    /**
     * Any code between 0 and 999 is not an error
     */
    const CODE_NON_ERROR_END = 999;

    private static $errorCodes;

    public static function getErrorName($code)
    {
        if (!self::$errorCodes) {
            self::$errorCodes = array(
                self::ERROR_UNKNOWN => 'An unknown error occurred, please contact PayCo support to find out details.',
                self::ERROR_MAC => 'The calculated MAC is invalid.',
                self::ERROR_INVALID_REQUEST => 'The request is invalid. Please refer to the message for further explanation of the cause.',
                self::ERROR_PAYMENT_METHOD_UNAVAILABLE => 'The payment method is not available. Please use another payment method.',
                self::ERROR_PAYMENT_METHOD_REJECTED => 'The payment has been rejected. Please use another payment method.',
                self::ERROR_TRANSACTION_METHOD_CALL_NOT_ALLOWED => 'Method call is not allowed in this state of transaction.',
                self::ERROR_TRANSACTION_EXPIRED => 'The transaction is expired.',
                self::ERROR_TRANSACTION_ORDER_NUMBER_NOT_FOUND => 'The requested order number does not exists.',
                self::ERROR_TRANSACTION_IN_PROGRESS => 'Method call already in process for this transaction.',
                self::ERROR_SHOP_NOT_FOUND => 'No shop found with this shopID.',
                self::ERROR_SHOP_NOT_ACTIVE => 'Shop is not activated.',
                self::ERROR_ORDER_EXISTS => 'Order ID already exist',
                self::ERROR_CREDIT_CARD_EXPIRED => 'The credit card is expired.',
                self::ERROR_CREDIT_CARD_NUMBER_NOT_MATCH_ISSUER => 'The credit card number does not match the issuer.',
                self::ERROR_CREDIT_CARD_ALREADY_STORED_FOR_USER => 'The credit card is already stored for this user.',
                self::ERROR_CREDIT_BANK_ACCOUNT_ALREADY_STORED_FOR_USER => 'The bank account is already stored for this user.',
                self::ERROR_USER_ALREADY_EXISTS => 'The user already exist.',
                self::ERROR_USER_NOT_FOUND => 'The user does not exist.',
                self::ERROR_REFUND_MUST_NOT_BE_GREATER_THAN_CAPTURE => 'Refund amount must not be greater than capture amount.',
                self::ERROR_PAYMENT_DECLINED_FRAUD => 'Payment declined by fraud check. Please use another payment method.',
                self::ERROR_REFUND_FAILED => 'Refund failed.',
                self::ERROR_CAPTUREID_NOT_FOUND => 'The provided captureID is unknown.',
                self::ERROR_MERCHANT_CREATION_NOT_ALLOWED => 'Merchant creation is not allowed.',
                self::ERROR_MSA_ALREADY_EXISTS => 'The MSA user already exists.',
                self::ERROR_SHOP_ALREADY_EXISTS => 'The Shop already exists.',
                self::ERROR_TRANSACTION_ALREADY_FINISHED => 'Transaction is already finished.',
                self::ERROR_INVALID_TOKEN => 'Invalid token.',
                self::ERROR_MERCHANT_NOT_ALLOWED_TO_MAKE_CALL => 'Merchant doesn’t have the right to do this call.',
                self::ERROR_PAYMENT_INSTRUMENT_UNKNOWN => 'The provided paymentInstrumentID is unknown.',
                self::ERROR_PAYMENT_METHODS_UNAVAILABLE_CONFIG => 'No payment method available due to configuration.',
                self::ERROR_ORDER_ALREADY_CAPTURED => 'This order was already captured.',
                self::ERROR_ORDER_ALREADY_CAPTURED_WITH_DIFFERENT_AMOUNT => 'This order was already captured with different Amount.',
                self::ERROR_TRANSACTION_CAPTURE_NOT_ALLOWED_IN_STATE => 'Capture is not allowed in this state of transaction.',
                self::ERROR_CALLBACK_PARAMETER_FORMAT => 'parameter or format error',
                self::ERROR_CALLBACK_UNKNOWN => 'unknown',
                self::ERROR_CALLBACK_TRANSACTION_PAYMENT_METHOD_NOT_ALLOWED => 'transaction or payment method not allowed',
                self::ERROR_CALLBACK_SYSTEM_TIMEOUT_NORESPONSE => 'system error, no response, timeout',
                self::ERROR_CALLBACK_CANCELED_USER => 'canceled by user',
                self::ERROR_CALLBACK_AUTHENTICATION_FAILED => 'authentication failed',
                self::ERROR_CALLBACK_BLOCK_STOLEN_DECLINED_NOT_REASON => 'expired, blocked, stolen, declined with no specific reason.',
                self::ERROR_CALLBACK_NO_JS_USER_BROWSER => 'No Javascript in user’s browser',
                self::ERROR_CALLBACK_OVERLOAD_PROCESSING_NOT_AVAILABLE => 'overload, merchant busy, processing temporarily not possible',
                self::ERROR_CALLBACK_INVALID_AMOUNT => 'invalid amount',
                self::ERROR_CALLBACK_FRAUD => 'fraud',
                self::ERROR_CALLBACK_COMMUNICATION => 'communication error',
                self::ERROR_CALLBACK_RESERVATION_EXPIRED => 'configuration error',
                self::ERROR_CALLBACK_THIRD_PARTY => 'third party gateway returned an error or declined transaction, details will be in the error description',
                self::ERROR_CALLBACK_NO_FUNDS => 'no funds',
                self::ERROR_CALLBACK_PARTIAL_REFUND_FAILED => 'partial refund failed'
            );
        }

        if (array_key_exists($code, self::$errorCodes)) {
            return self::$errorCodes[$code];
        }

        return "Unknown Error Code";
    }

    public static function checkCodeIsError($code)
    {
        if ($code >= self::CODE_NON_ERROR_START && $code <= self::CODE_NON_ERROR_END) {
            return false;
        }

        return true;
    }
}
