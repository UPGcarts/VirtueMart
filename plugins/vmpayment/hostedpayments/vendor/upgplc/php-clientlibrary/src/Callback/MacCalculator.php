<?php
namespace Upg\Library\Callback;

use Upg\Library\Callback\Exception\MacValidation;
use Upg\Library\Config;
use Upg\Library\Mac\AbstractCalculator;

/**
 * Class MacCalculator
 * Mac calculator for call backs and MNS notifications
 * @package Upg\Library\Callback
 */
class MacCalculator extends AbstractCalculator
{
    private $mac;
    private $rawResponse;

    public function __construct(Config $config, array $response)
    {
        $this->setConfig($config);
        $this->setResponse($response);
    }

    public function setResponse(array $response)
    {
        $this->setCalculationArray($response);

        $this->rawResponse = $response;

        if (array_key_exists('mac', $response)) {
            $this->mac = $response['mac'];
        }
        return $this;
    }

    public function validateResponse()
    {
        if (!parent::validate($this->mac, false)) {
            throw new MacValidation($this->calculateMac(), $this->mac, $this->rawResponse);
        }

        return true;
    }
}

