<?php
namespace Upg\Library\Api;

use Upg\Library\Config;

/**
 * Class GetUser
 * APi stub for the getUserData method
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/getuserstatus
 * @package Upg\Library\Api
 */
class GetUser extends AbstractApi
{
    /**
     * URI for the user path
     */
    const GET_USER_PATH = 'getUser';

    /**
     * Construct the API stub class
     * @param Config $config Merchant config
     * @param \Upg\Library\Request\GetUser $request Request to be sent
     */
    public function __construct(Config $config, \Upg\Library\Request\GetUser $request)
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
        return $this->combineUrlUri($baseUrl, self::GET_USER_PATH);
    }
}
