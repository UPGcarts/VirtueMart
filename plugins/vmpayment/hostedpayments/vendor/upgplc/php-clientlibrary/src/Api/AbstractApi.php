<?php

namespace Upg\Library\Api;

use Upg\Library\Api\Exception\ApiError;
use Upg\Library\Api\Exception\CurlError;
use Upg\Library\Api\Exception\InvalidHttpResponseCode;
use Upg\Library\Api\Exception\InvalidUrl;
use Upg\Library\Api\Exception\RequestNotSet;
use Upg\Library\Config;
use Upg\Library\Error\Codes;
use Upg\Library\Request\AbstractRequest;
use Upg\Library\Request\MacCalculator;
use Upg\Library\Response\FailureResponse;
use Upg\Library\Response\SuccessResponse;
use Upg\Library\Response\Unserializer\Factory;
use Upg\Library\Serializer\SerializerFactory;
use Upg\Library\Validation\Validation;

/**
 * Class AbstractApi
 * Abstarct class which will implement the call out code for the api classes
 * @package Upg\Library\Api
 */
abstract class AbstractApi
{
    /**
     * The config object
     * @var Config
     */
    protected $config;

    private $url;

    /**
     * Raw Response string
     * @var string
     */
    protected $responseRaw;

    /**
     * The raw header
     * @var string
     */
    protected $headerRaw;

    /**
     * The raw serialized request
     * @var string|array
     */
    protected $requestRaw;

    /**
     * Raw Response http status code
     * @var string
     */
    protected $responseHttpCode;

    /**
     * The request to be sent
     * @var AbstractRequest
     */
    protected $request;

    /**
     * @var string
     */
    protected $submitType;

    /**
     * @var Validation
     */
    protected $validator;

    /**
     * @var MacCalculator
     */
    protected $macCalculator;

    /**
     * @var \Upg\Library\Api\MacCalculator
     */
    protected $macCalculatorResponse;

    /**
     * @var \Upg\Library\Serializer\Serializer
     */
    protected $serializer;

    /**
     * @var \Upg\Library\Response\Unserializer\Processor
     */
    protected $unserializer;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    private $timeLoggerValue;

    const SUBMIT_TYPE_URL_ENCODE = "urlencode";
    const SUBMIT_TYPE_MULTIPART = "multipart";

    /**
     * The API will return certain http codes which the request class
     * will parse out the reponse. These are:
     * 200
     * 400 (Bad Request) If there is a problem with the content of the request
     * 401 (Unauthorized) validation errors
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/genera-rules
     */
    protected $allowedHttpStatusCodes = array(200, 400, 401);

    /**
     * For php 5.5 and above the new CURLFile has to be used
     */
    const CURL_FILE_VERSION = 50500;

    /**
     * This abstract method should return full url to the API endpoint for the request.
     * @return string
     */
    abstract public function getUrl();

    /**
     * Construct the API sender class
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        if (empty($this->submitType)) {
            $this->submitType = self::SUBMIT_TYPE_URL_ENCODE;
        }

        $this->logger = \Upg\Library\Logging\Factory::getLogger($config, $config->getLogLocationRequest());
        $this->timeLoggerValue = md5(time().':'.rand());

        return $this;
    }

    /**
     * Conveience method to get base url for requests
     * @return string
     */
    protected function getBaseUrl()
    {
        return $this->config->getBaseUrl();
    }

    /**
     * This method combines the url with the uri. It also ensure double slashes are avoided
     * when the combine is done
     * @param $baseUrl Base url should be set from the config
     * @param $uri Uri of the api call
     * @return string
     */
    protected function combineUrlUri($baseUrl, $uri)
    {
        $baseUrl = ltrim(rtrim($baseUrl));
        $uri = ltrim(rtrim($uri));

        if (substr($baseUrl, -1) == '/') {
            $baseUrl = rtrim($baseUrl, '/');
        }

        if (substr($uri, 0, 1) == '/') {
            $uri = ltrim($uri, '/');
        }


        return $baseUrl . '/' . $uri;
    }

    /**
     * Send the request to the api end point and get the response
     * @return SuccessResponse
     * @throws ApiError
     * @throws CurlError
     * @throws InvalidUrl
     * @throws Exception\Validation
     * @throws InvalidHttpResponseCode
     * @throws RequestNotSet
     */
    public function sendRequest()
    {
        $timeStart = microtime(true);
        $this->logger->debug("timelog-".$this->timeLoggerValue."--Started request: ".get_class($this->request));
        if (!$this->request instanceof AbstractRequest) {
            $this->logger->error("Request is not set or is not an AbstractRequest it is: " . get_class($this->request));
            throw new RequestNotSet();
        }

        if (!$this->requestRaw) {
            $this->logger->debug("Processing request: " . serialize($this->request));
            $this->processRequest();
        }
        $timeEnd = microtime(true);
        $this->logger->debug("timelog-".$this->timeLoggerValue."--Process Request: ".($timeEnd - $timeStart));
        $timeStart = microtime(true);

        if (!$this->responseRaw) {
            $this->postData();
        }

        $timeEnd = microtime(true);
        $this->logger->debug("timelog-".$this->timeLoggerValue."--Sent Request: ".($timeEnd - $timeStart));
        $timeStart = microtime(true);

        return $this->processResponse();
    }

    /**
     * @return SuccessResponse
     * @throws ApiError
     * @throws InvalidHttpResponseCode
     * @throws Exception\JsonDecode
     * @throws Exception\MacValidation
     */
    private function processResponse()
    {
        $timeStart = microtime(true);
        if (!in_array($this->responseHttpCode, $this->allowedHttpStatusCodes)) {
            throw new InvalidHttpResponseCode($this->responseHttpCode, $this->responseRaw);
        }

        $this->getMacCalculatorResponse()
            ->setResponse($this->responseRaw, $this->headerRaw)
            ->validateResponse();

        $data = json_decode($this->responseRaw, true);
        $data = $this->getUnserializer()->topLevelUnserialize($data);

        $response = null;

        if (!Codes::checkCodeIsError($data['resultCode'])) {
            $response = new SuccessResponse($this->config, $data);
        } else {
            $response = new FailureResponse($this->config, $data);
            throw new ApiError($response, $this->responseRaw, $this->responseHttpCode);
        }

        $timeEnd = microtime(true);
        $this->logger->debug("timelog-".$this->timeLoggerValue."--Processed Request: ".($timeEnd - $timeStart));

        return $response;
    }

    /**
     * Process the request
     * @throws Exception\Validation
     * @throws InvalidUrl
     * @throws \Upg\Library\Serializer\Exception\VisitorCouldNotBeFound
     */
    private function processRequest()
    {
        $this->url = $this->validateUrl($this->getUrl());

        /**
         * Validate and serialize the request
         */
        $validationResult = $this->getValidator()->getValidator($this->request)->performValidation();

        if (!empty($validationResult)) {
            $this->logger->debug("Got validation error on request request :" . serialize($this->request));
            $this->logger->error("Got validation issue" . serialize($validationResult));
            throw new \Upg\Library\Api\Exception\Validation($validationResult);
        }

        /**
         * Serialize the request
         */
        $mac = $this->getMacCalculator()->setConfig($this->config)->setRequest($this->request)->calculateMac();
        $this->request->setMac($mac);
        $this->requestRaw = $this->getSerializer()->serialize($this->request);
    }

    /**
     * Send the curl request
     * @throws CurlError
     */
    private function postData()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        if ($this->submitType == self::SUBMIT_TYPE_MULTIPART) {
            $this->curlSetFileUploadOptions($ch);
        }

        if(is_string($this->requestRaw)){
            $this->logger->debug('Sending following raw request to ' . $this->url . ' : ' . $this->requestRaw);
        }else {
            $this->logger->debug('Sending following raw request to ' . $this->url . ' : ' . serialize($this->requestRaw));
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->requestRaw);

        $result = curl_exec($ch);

        $curlFileTime = curl_getinfo($ch,CURLINFO_FILETIME);
        $curlTotalTime = curl_getinfo($ch,CURLINFO_TOTAL_TIME);

        $this->logger->debug("timelog-".$this->timeLoggerValue."--Curl Time FileTime: ".$curlFileTime);
        $this->logger->debug("timelog-".$this->timeLoggerValue."--Curl Time TotalTime: ".$curlTotalTime);

        if (curl_errno($ch) > 0) {
            $this->logger->error("Got the following curl error: " . curl_error($ch) . ' ' . curl_errno($ch));
            $this->logger->error("Got the following curl error on request: " . serialize($this->requestRaw));
            throw new CurlError(curl_error($ch), curl_errno($ch), $result);
        }

        $this->responseHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $this->headerRaw = substr($result, 0, $headerSize);
        $this->responseRaw = substr($result, $headerSize);

        curl_close($ch);
    }

    /**
     * Set the curl options approriately for multipart encoded forms
     * @param $ch
     */
    private function curlSetFileUploadOptions($ch)
    {
        /**
         * Api says for any multipart request the header must be set to multipart/form-data
         * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/updatetransactiondata
         */
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: multipart/form-data'
            )
        );

        /**
         * For php 5.5 and above use curl_file_create
         */
        if (PHP_VERSION_ID >= self::CURL_FILE_VERSION) {
            $files = array();
            foreach ($this->requestRaw as $key => $value) {
                if (strpos($value, 'FILE::') === 0) {
                    $file = str_replace('FILE::', '', $this->requestRaw[$key]);
                    $this->requestRaw[$key] = curl_file_create($file, '', $key);
                }
            }
        } else {
            foreach ($this->requestRaw as $key => $value) {
                if (strpos($value, 'FILE::') === 0) {
                    $this->requestRaw[$key] = str_replace('FILE::', '@', $this->requestRaw[$key]);
                }
            }
        }
    }


    /**
     * Get the serializer
     * @return \Upg\Library\Serializer\Serializer
     */
    public function getSerializer()
    {
        if (!$this->serializer) {
            $this->serializer = SerializerFactory::getSerializer();
        }

        return $this->serializer;
    }

    /**
     * Get unserializer
     * @return \Upg\Library\Response\Unserializer\Processor
     */
    private function getUnserializer()
    {
        if (!$this->unserializer) {
            $this->unserializer = Factory::getProcessor();
        }

        return $this->unserializer;
    }

    /**
     * Get the Mac calculator for the response
     * @return \Upg\Library\Api\MacCalculator
     */
    private function getMacCalculatorResponse()
    {
        if (!$this->macCalculatorResponse) {
            $this->macCalculatorResponse = new \Upg\Library\Api\MacCalculator($this->config);
        }

        return $this->macCalculatorResponse;
    }

    /**
     * Get the Mac calculator for the request
     * @return MacCalculator
     */
    private function getMacCalculator()
    {
        if (!$this->macCalculator) {
            $this->macCalculator = new MacCalculator();
        }
        return $this->macCalculator;
    }

    /**
     * Get validator
     * @return Validation
     */
    private function getValidator()
    {
        if (!$this->validator) {
            $this->validator = new Validation();
        }

        return $this->validator;
    }

    /**
     * Get any raw responses as string if availible
     * @return string
     */
    public function getResponseRaw()
    {
        return $this->responseRaw;
    }

    /**
     * Please note this method is for the unit tests to pass in mock responses for tests
     * @param $responseRaw
     * @param $httpCode
     * @return $this
     */
    public function setResponseRaw($responseRaw, $httpCode, $header = '')
    {
        $this->responseRaw = $responseRaw;
        $this->responseHttpCode = $httpCode;
        $this->headerRaw = $header;

        return $this;
    }

    /**
     * Validate the url before sending the request
     * @param $url
     * @return mixed
     * @throws InvalidUrl
     */
    private function validateUrl($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
            return $url;
        }

        throw new InvalidUrl($url);
    }
}
