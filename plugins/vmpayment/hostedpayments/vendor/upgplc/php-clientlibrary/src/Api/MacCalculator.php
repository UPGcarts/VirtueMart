<?php
namespace Upg\Library\Api;

use Upg\Library\Api\Exception\JsonDecode;
use Upg\Library\Api\Exception\MacValidation;
use Upg\Library\Config;
use Upg\Library\Logging\Factory;
use Upg\Library\Mac\AbstractCalculator;

class MacCalculator extends AbstractCalculator
{
    private $rawRequest;
    private $mac;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Constructor for the validator
     * @param Config $config Merchant config
     */
    public function __construct(Config $config)
    {
        $this->logger = Factory::getLogger($config, $config->getLogLocationRequest());
        $this->setConfig($config);
    }

    /**
     * Set the response to validate
     * @param string $response The response
     * @param string $headers The headers
     * @return $this
     * @throws JsonDecode
     */
    public function setResponse($response, $headers)
    {
        $this->rawRequest = $response;

        $responseData = json_decode($response, true);

        if (!is_array($responseData)) {
            $code = json_last_error();
            throw new JsonDecode($code, $response);
        }

        if (!empty($headers)) {
            $this->mac = $this->lookUpMacInRawHeader($headers);
        } else {
            $this->mac = array_key_exists('mac', $responseData) ? $responseData['mac'] : '';
        }

        $this->setCalculationArray(array('reponse'=>$response));
        return $this;
    }

    private function lookUpMacInRawHeader($header)
    {
        $headerArray = explode("\r\n", $header);

        foreach ($headerArray as $value) {
            if (stripos($value, 'X-Payco-HMAC:') === 0) {
                return trim(str_replace('X-Payco-HMAC:', '', $value));
            }
        }

        return '';
    }

    /**
     * Validate the Response
     * @return bool
     * @throws MacValidation
     * @throws \Upg\Library\Mac\Exception\MacInvalid
     */
    public function validateResponse()
    {
        if (!parent::validate($this->mac, false)) {
            $this->logger->error("Invalid mac from api response expected: " .
                $this->mac . " got " . $this->calculateMac());
            $this->logger->debug("Invalid mac from api for request :" .
                $this->rawRequest . " expected: " . $this->mac . " got " . $this->calculateMac());
            throw new MacValidation($this->calculateMac(), $this->mac, $this->rawRequest);

        }

        return true;
    }
}
