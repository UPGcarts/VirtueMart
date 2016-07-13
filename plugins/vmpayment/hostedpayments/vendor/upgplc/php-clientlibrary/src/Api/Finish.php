<?php
namespace Upg\Library\Api;

use Upg\Library\Config;

/**
 * Class Finish
 * API stub for the finish call
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/finish
 * @package Upg\Library\Api
 */
class Finish extends AbstractApi
{
    /**
     * URI for the finish method
     */
    const FINISH_PATH = 'finish';

    /**
     * Construct the API stub
     * @param Config $config Merchant config
     * @param \Upg\Library\Request\Finish $request Request to be sent
     */
    public function __construct(Config $config, \Upg\Library\Request\Finish $request)
    {
        $this->request = $request;
        parent::__construct($config);
    }

    /**
     * URL for the request
     * @return string
     */
    public function getUrl()
    {
        $baseUrl = $this->getBaseUrl();
        return $this->combineUrlUri($baseUrl, self::FINISH_PATH);
    }
}
