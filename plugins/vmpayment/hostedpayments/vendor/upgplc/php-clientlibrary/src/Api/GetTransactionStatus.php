<?php
namespace Upg\Library\Api;

use Upg\Library\Config;

/**
 * Class GetTransactionStatus
 * Api stub for getTransactionStatus
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/getstatus
 * @package Upg\Library\Api
 */
class GetTransactionStatus extends AbstractApi
{
    const UPDATE_TRANSACTION_STATUS_PATH = 'getTransactionStatus';

    public function __construct(Config $config, \Upg\Library\Request\GetTransactionStatus $request)
    {
        $this->request = $request;
        parent::__construct($config);
    }

    public function getUrl()
    {
        $baseUrl = $this->getBaseUrl();
        return $this->combineUrlUri($baseUrl, self::UPDATE_TRANSACTION_STATUS_PATH);
    }
}
