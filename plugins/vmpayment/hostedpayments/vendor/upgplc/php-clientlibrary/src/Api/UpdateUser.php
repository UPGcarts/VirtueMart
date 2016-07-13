<?php
namespace Upg\Library\Api;

use Upg\Library\Config;

/**
 * Class UpdateUser
 * API stub for updateUser
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/updateuser
 * @package Upg\Library\Api
 */
class UpdateUser extends AbstractApi
{
    /**
     * The URI
     */
    const UPDATE_USER_PATH = 'updateUser';

    /**
     * Constructor
     * @param Config $config Merchant Config
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
        return $this->combineUrlUri($baseUrl, self::UPDATE_USER_PATH);
    }
}
