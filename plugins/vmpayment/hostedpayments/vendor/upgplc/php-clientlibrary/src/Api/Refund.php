<?php
namespace Upg\Library\Api;

use Upg\Library\Config;

/**
 * Class Refund
 * Stub method for the refund call
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/getuserpaymentinstrument
 * @package Upg\Library\Api
 */
class Refund extends AbstractApi
{
    /**
     * URI for the endpoint
     */
    const REFUND_PATH = 'refund';

    /**
     * Construct the API stub
     * @param Config $config Merchant config
     * @param \Upg\Library\Request\Refund $request Request to be sent
     */
    public function __construct(Config $config, \Upg\Library\Request\Refund $request)
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
        return $this->combineUrlUri($baseUrl, self::REFUND_PATH);
    }
}
