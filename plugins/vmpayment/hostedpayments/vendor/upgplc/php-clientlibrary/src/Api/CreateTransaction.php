<?php
namespace Upg\Library\Api;

use Upg\Library\Config;

/**
 * Class CreateTransaction
 * API stub for the CreateTransaction call
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/capture
 * @package Upg\Library\Api
 */
class CreateTransaction extends AbstractApi
{
    /**
     * URI for the createTransaction call
     */
    const CREATE_TRANSACTION_PATH = 'createTransaction';

    /**
     * Construct the API stub class
     * @param Config $config Config for store owner
     * @param \Upg\Library\Request\CreateTransaction $request Request to be sent
     */
    public function __construct(Config $config, \Upg\Library\Request\CreateTransaction $request)
    {
        $this->request = $request;
        parent::__construct($config);
    }

    /**
     * Get the url
     * @return string
     */
    public function getUrl()
    {
        $baseUrl = $this->getBaseUrl();
        return $this->combineUrlUri($baseUrl, self::CREATE_TRANSACTION_PATH);
    }
}
