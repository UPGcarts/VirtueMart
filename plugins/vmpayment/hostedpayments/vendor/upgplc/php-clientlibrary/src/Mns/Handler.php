<?php
namespace Upg\Library\Mns;

use Upg\Library\Callback\MacCalculator;
use Upg\Library\Config;
use Upg\Library\Mns\Exception\ParamNotProvided;

/**
 * Class Handler
 * Handler for MNS class
 * Even if only one notification can not be delivered successfully, no other notifications are sent until the
 * problem is fixed.
 * If the merchant server answers with HTTP code 500 because the message could not be processed internally,
 * PayCo will block the queue. The merchant should always answer with HTTP code 200 as soon as the message
 * was received successfully.
 * The processing of the message should be implemented asynchronously.
 * IE the processor should save the validated data to an database for processing
 * Although this class will return an exception you should log and flag up a critical error and return an 200
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/notification-call
 * @package Upg\Library\Mns
 */
class Handler
{
    /**
     * Config class for the library
     * @var Config
     */
    private $config;

    /**
     * Data from the MNS call
     * @var array
     */
    private $data;

    /**
     * Class provided by the integrator to be ran once a MNS notification is validated
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * Stores required parameter names
     * @var array
     */
    private $requireFields = array(
        'merchantID',
        'storeID',
        'orderID',
        'paymentReference',
        'userID',
        'amount',
        'currency',
        'transactionStatus',
        'timestamp',
        'version',
        'mac'
    );

    /**
     * Stores required optional names
     * @var array
     */
    private $optionalFields = array(
        'captureID',
        'merchantReference',
        'orderStatus',
        'additionalData'
    );

    /**
     * Instantiate the handler
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/reserve
     * @param Config $config Config class for the library
     * @param array $data Data in the MNS call. Please see API documentation for possible values
     * @param ProcessorInterface $processor Class provided by the Integration to be ran once a MNS call is validated
     * @throws ParamNotProvided
     * @throws \Upg\Library\Callback\Exception\MacValidation
     */
    public function __construct(Config $config, array $data, ProcessorInterface $processor)
    {
        $this->config = $config;
        $this->processor = $processor;

        $missingParams = array();

        foreach ($this->requireFields as $param) {
            if (array_key_exists($param, $data)) {
                $this->data[$param] = $data[$param];
            } else {
                $missingParams[] = $param;
            }
        }

        foreach ($this->optionalFields as $param) {
            if (array_key_exists($param, $data)) {
                $this->data[$param] = $data[$param];
            } else {
                $this->data[$param] = '';
            }
        }

        if (!empty($missingParams)) {
            throw new ParamNotProvided(implode(', ', $missingParams));
        }

        $macCalculator = new MacCalculator($this->config, $this->data);
        $macCalculator->validateResponse();

        $this->processor->sendData(
            $this->data['merchantID'],
            $this->data['storeID'],
            $this->data['orderID'],
            $this->data['captureID'],
            $this->data['merchantReference'],
            $this->data['paymentReference'],
            $this->data['userID'],
            $this->data['amount'],
            $this->data['currency'],
            $this->data['transactionStatus'],
            $this->data['orderStatus'],
            $this->data['additionalData'],
            $this->data['timestamp'],
            $this->data['version']
        );
    }

    /**
     * Run the processor callback
     * Please not the call back should ensure that a 200 status is returned to payco.
     * If there is an error please handle logging and recover.
     * Also please do precessing of MNS calls on a cron
     * @return string json string with url value
     */
    public function run()
    {
        $url = $this->processor->run();
    }
}
