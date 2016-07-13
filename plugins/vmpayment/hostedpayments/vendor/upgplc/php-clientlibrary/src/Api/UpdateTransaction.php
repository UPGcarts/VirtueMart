<?php
namespace Upg\Library\Api;

use Upg\Library\Config;

/**
 * Class UpdateTransaction
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/updatetransactiondata
 * @package Upg\Library\Api
 */
class UpdateTransaction extends AbstractApi
{
    /**
     * URI for the updateTransaction call
     */
    const UPDATE_TRANSACTION_PATH = 'capture';

    /**
     * Constructor
     * @param Config $config Config for merchant
     * @param \Upg\Library\Request\UpdateTransaction $request Request to be sent
     */
    public function __construct(Config $config, \Upg\Library\Request\UpdateTransaction $request)
    {
        $this->request = $request;
        $this->submitType = self::SUBMIT_TYPE_MULTIPART;
        parent::__construct($config);
    }

    /**
     * Get the url
     * @return string
     */
    public function getUrl()
    {
        $baseUrl = $this->getBaseUrl();
        return $this->combineUrlUri($baseUrl, self::UPDATE_TRANSACTION_PATH);
    }
}
