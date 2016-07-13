<?php
namespace Upg\Library\Api;

use Upg\Library\Config;

/**
 * Class RegisterUserPaymentInstrument
 * APi stub for registerUserPaymentInstrument calls
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/registerpaymentinstrumenttouser
 * @package Upg\Library\Api
 */
class RegisterUserPaymentInstrument extends AbstractApi
{
    /**
     * URI for end point
     */
    const REGISTER_USER_PAYMENT_INSTRUMENT_PATH = 'registerUserPaymentInstrument';

    /**
     * Constructor
     * @param Config $config Merchant Config
     * @param \Upg\Library\Request\RegisterUserPaymentInstrument $request Request to be sent
     */
    public function __construct(Config $config, \Upg\Library\Request\RegisterUserPaymentInstrument $request)
    {
        $this->request = $request;
        parent::__construct($config);
    }

    /**
     * Get the URL for the API call
     * @return string
     */
    public function getUrl()
    {
        $baseUrl = $this->getBaseUrl();
        return $this->combineUrlUri($baseUrl, self::REGISTER_USER_PAYMENT_INSTRUMENT_PATH);
    }
}
