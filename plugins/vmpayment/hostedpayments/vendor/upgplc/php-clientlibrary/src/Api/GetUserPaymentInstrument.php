<?php
namespace Upg\Library\Api;

use Upg\Library\Config;

/**
 * Class GetUserPaymentInstrument
 * Api stub for getUserPaymentInstrument call
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/getuserpaymentinstrument
 * @package Upg\Library\Api
 */
class GetUserPaymentInstrument extends AbstractApi
{
    /**
     * URI of the API destination
     */
    const GET_USER_PAYMENT_INSTRUMENT_PATH = 'getUserPaymentInstrument';

    /**
     * Construct the API stub
     * @param Config $config Config for the merchant
     * @param \Upg\Library\Request\GetUserPaymentInstrument $request Request to be sent
     */
    public function __construct(Config $config, \Upg\Library\Request\GetUserPaymentInstrument $request)
    {
        $this->request = $request;
        parent::__construct($config);
    }

    /**
     * Get the url
     * @return string
     */
    public function getUrl()
    {
        $baseUrl = $this->getBaseUrl();
        return $this->combineUrlUri($baseUrl, self::GET_USER_PAYMENT_INSTRUMENT_PATH);
    }
}
