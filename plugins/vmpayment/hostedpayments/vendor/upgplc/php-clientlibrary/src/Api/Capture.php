<?php
namespace Upg\Library\Api;

use Upg\Library\Config;

/**
 * Class Capture
 * The API stub for the capture call
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/capture
 * @package Upg\Library\Api
 */
class Capture extends AbstractApi
{
    /**
     * URI for the capture call
     */
    const CAPTURE_PATH = 'capture';

    /**
     * Construct the API stub
     * @param Config $config Config for the merchant
     * @param \Upg\Library\Request\Capture $request Request for the capture that is to be sent
     */
    public function __construct(Config $config, \Upg\Library\Request\Capture $request)
    {
        $this->request = $request;
        parent::__construct($config);
    }

    /**
     * Return the full url using base url in the config
     * @return string
     */
    public function getUrl()
    {
        $baseUrl = $this->getBaseUrl();
        return $this->combineUrlUri($baseUrl, self::CAPTURE_PATH);
    }
}
