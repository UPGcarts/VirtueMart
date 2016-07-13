<?php
namespace Upg\Library\Api;

use Upg\Library\Config;

/**
 * Class RegisterUser
 * Stub for the registerUser call
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/registeruser
 * @package Upg\Library\Api
 */
class RegisterUser extends AbstractApi
{
    /**
     * URI to the api end point
     */
    const REGISTER_USER_PATH = 'registerUser';

    /**
     * Constructor
     * @param Config $config Merchant config
     * @param \Upg\Library\Request\RegisterUser $request Request to be sent
     */
    public function __construct(Config $config, \Upg\Library\Request\RegisterUser $request)
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
        return $this->combineUrlUri($baseUrl, self::REGISTER_USER_PATH);
    }
}
