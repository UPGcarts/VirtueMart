<?php
namespace Upg\Library\Api;

use Upg\Library\Config;

/**
 * Class Reserve
 * API stub for the reserve method
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/reserve
 * @package Upg\Library\Api
 */
class Reserve extends AbstractApi
{
    /**
     * The URI for the api endpoint
     */
    const RESERVE_PATH = 'reserve';

    /**
     * Constructor
     * @param Config $config Config for merchant
     * @param \Upg\Library\Request\Reserve $request Request to be sent
     */
    public function __construct(Config $config, \Upg\Library\Request\Reserve $request)
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
        return $this->combineUrlUri($baseUrl, self::RESERVE_PATH);
    }
}
