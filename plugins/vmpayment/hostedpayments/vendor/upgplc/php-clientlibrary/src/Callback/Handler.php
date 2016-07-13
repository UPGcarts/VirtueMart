<?php
namespace Upg\Library\Callback;

use Upg\Library\Callback\Exception\ParamNotProvided;
use Upg\Library\Config;

/**
 * Class Handler
 * Handler class for the call backs that validate the request before invoking a callback
 * @package Upg\Library\Callback
 */
class Handler
{
    /**
     * Merchant Config
     * @var Config
     */
    private $config;

    /**
     * Data from the callback
     * @var array
     */
    private $data;

    /**
     * Class to handle callbacks where the MAC and required fields have been validated
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * Array of the requires paramerters for the data in the constructor
     * @var array
     */
    private $validParams = array(
        'notificationType',
        'merchantID',
        'storeID',
        'orderID',
        'paymentMethod',
        'resultCode',
        'salt',
        'mac'
    );

    private $optionalParam = array(
        'merchantReference',
        'additionalInformation',
        'paymentInstrumentID',
        'paymentInstrumentsPageUrl',
        'message',
    );

    /**
     * Construct a instance of the Callback Handler
     * See the linked documentaion under the Callback
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/reserve
     * @param Config $config Payco config
     * @param array $data Array of data in the call back
     * @param ProcessorInterface $processor Processor to handle the call back
     * @throws Exception\MacValidation
     * @throws ParamNotProvided
     */
    public function __construct(Config $config, array $data, ProcessorInterface $processor)
    {
        $this->config = $config;
        $this->processor = $processor;

        //ok validate the request data;
        $missingParams = array();

        foreach ($this->validParams as $param) {
            if (array_key_exists($param, $data)) {
                $this->data[$param] = $data[$param];
            } else {
                $missingParams[] = $param;
            }
        }

        foreach ($this->optionalParam as $param) {
            if (array_key_exists($param, $data)) {
                $this->data[$param] = $data[$param];
            } else {
                $this->data[$param] = '';
            }
        }

        if (!empty($missingParams)) {
            throw new ParamNotProvided(implode(', ', $missingParams));
        }
        /**
         * validate the call back mac
         */
        $macCalculator = new MacCalculator($this->config, $this->data);
        $macCalculator->validateResponse();

        $additionalInfo = array();
        if(!empty($data['additionalInformation'])) {
            $additionalInfo = json_decode($data['additionalInformation'], true);
        }

        /**
         * Send the data to the processor
         */
        $this->processor->sendData(
            $this->data['notificationType'],
            $this->data['merchantID'],
            $this->data['storeID'],
            $this->data['orderID'],
            $this->data['paymentMethod'],
            $this->data['resultCode'],
            $this->data['merchantReference'],
            $this->data['paymentInstrumentID'],
            $this->data['paymentInstrumentsPageUrl'],
            $additionalInfo,
            $this->data['message']
        );

    }

    /**
     * Return url for payco to redirect the user as a json string
     * @return string json string with url value
     */
    public function run()
    {
        $url = $this->processor->run();
        return json_encode(array('url'=>$url));
    }
}
