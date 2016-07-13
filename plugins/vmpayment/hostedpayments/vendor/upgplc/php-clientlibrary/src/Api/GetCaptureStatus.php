<?php
namespace Upg\Library\Api;

use Upg\Library\Config;

/**
 * Class GetCaptureStatus
 * The API stub method for the getCaptureStatus call
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/getcapturestatus
 * @package Upg\Library\Api
 */
class GetCaptureStatus extends AbstractApi
{
    /**
     * URI for the call
     */
    const GET_CAPTURE_STATUS_PATH = 'getCaptureStatus';

    /**
     * Construct the stub
     * @param Config $config Merchant config
     * @param \Upg\Library\Request\GetCaptureStatus $request Request to be sent
     */
    public function __construct(Config $config, \Upg\Library\Request\GetCaptureStatus $request)
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
        return $this->combineUrlUri($baseUrl, self::GET_CAPTURE_STATUS_PATH);
    }
}
