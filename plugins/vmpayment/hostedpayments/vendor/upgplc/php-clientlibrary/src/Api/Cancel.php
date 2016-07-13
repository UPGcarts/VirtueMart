<?php
namespace Upg\Library\Api;

use Upg\Library\Config;

/**
 * Class Cancel
 * ApiStub for the cancel call
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/cancel
 * @package Upg\Library\Api
 */
class Cancel extends AbstractApi
{
    /**
     * The URI for the cancel request
     */
    const CANCEL_PATH = 'cancel';

    /**
     * Construct the call
     * @param Config $config Config for the merchant
     * @param \Upg\Library\Request\Cancel $request Request to send
     */
    public function __construct(Config $config, \Upg\Library\Request\Cancel $request)
    {
        $this->request = $request;
        parent::__construct($config);
    }

    /**
     * Get the full url
     * @return string
     */
    public function getUrl()
    {
        $baseUrl = $this->getBaseUrl();
        return $this->combineUrlUri($baseUrl, self::CANCEL_PATH);
    }
}
