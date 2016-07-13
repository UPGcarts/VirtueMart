<?php
namespace Upg\Library\Api;

use Upg\Library\Config;

/**
 * Class DeleteUserPaymentInstrument
 * The API stub method for for the deleteUserPaymentInstrument call
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/deletepaymentinstrumentofuser
 * @package Upg\Library\Api
 */
class DeleteUserPaymentInstrument extends AbstractApi
{
    /**
     * URI for the API method
     */
    const DELETE_USER_PAYMENT_INSTRUMENT_PATH = 'deleteUserPaymentInstrument';

    /**
     * Construct the API stub
     * @param Config $config Merchant Config
     * @param \Upg\Library\Request\DeleteUserPaymentInstrument $request Request to be sent
     */
    public function __construct(Config $config, \Upg\Library\Request\DeleteUserPaymentInstrument $request)
    {
        $this->request = $request;
        parent::__construct($config);
    }

    /**
     * Get the URL
     * @return string
     */
    public function getUrl()
    {
        $baseUrl = $this->getBaseUrl();
        return $this->combineUrlUri($baseUrl, self::DELETE_USER_PAYMENT_INSTRUMENT_PATH);
    }
}
