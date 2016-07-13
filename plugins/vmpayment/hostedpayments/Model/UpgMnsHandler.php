<?php

require_once(dirname(__FILE__) . '/../vendor/autoload.php');

class UpgMnsHandler implements \Upg\Library\Mns\ProcessorInterface
{
    /**
     * @var JDatabase
     */
    private $db;

    private $merchantID;
    private $storeID;
    private $orderID;
    private $captureID;
    private $merchantReference;
    private $paymentReference;
    private $userID;
    private $amount;
    private $currency;
    private $transactionStatus;
    private $orderStatus;
    private $additionalData;
    private $timestamp;
    private $version;

    public function __construct(JDatabase $db)
    {
        $this->db = $db;
    }

    /**
     * @param $merchantID This is the merchantID assigned by PayCo.
     * @param $storeID This is the store ID of a merchant assigned by PayCo as a merchant can have more than one store.
     * @param $orderID This is the order number tyhat the shop has assigned
     * @param $captureID The confirmation ID of the capture. Only sent for Notifications that belong to captures
     * @param $merchantReference Reference that can be set by the merchant during the createTransaction call.
     * @param $paymentReference The reference number of the
     * @param $userID The unique user id of the customer.
     * @param $amount This is either the amount of an incoming payment or “0” in case of some status changes
     * @param $currency  Currency code according to ISO4217.
     * @param $transactionStatus Current status of the transaction. Same values as resultCode
     * @param $orderStatus Possible values: PAID PAYPENDING PAYMENTFAILED CHARGEBACK CLEARED. Status of order
     * @param $additionalData Json string with aditional data
     * @param $timestamp Unix timestamp, Notification timestamp
     * @param $version notification version (currently 1.5)
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/notification-call
     */
    public function sendData(
        $merchantID,
        $storeID,
        $orderID,
        $captureID,
        $merchantReference,
        $paymentReference,
        $userID,
        $amount,
        $currency,
        $transactionStatus,
        $orderStatus,
        $additionalData,
        $timestamp,
        $version
    ) {
        $this->merchantID = $merchantID;
        $this->storeID = $storeID;
        $this->orderID = $orderID;
        $this->captureID = $captureID;
        $this->merchantReference = $merchantReference;
        $this->paymentReference = $paymentReference;
        $this->userID = $userID;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->transactionStatus = $transactionStatus;
        $this->orderStatus = $orderStatus;
        $this->additionalData = $additionalData;
        $this->timestamp = $timestamp;
        $this->version = $version;
    }

    /**
     * The run method used by the processor to run successfuly validated MNS notifications.
     * This should not return anything
     */
    public function run()
    {
        $fields = $this->getInsertObject();
        $this->db->insertObject('#__virtuemart_payment_plg_payco_mns_messages', $fields);
    }

    private function getInsertObject()
    {
        $object = new stdClass();
        $object->merchantID = $this->merchantID;
        $object->storeID = $this->storeID;
        $object->orderID = $this->orderID;
        $object->captureID = $this->captureID;
        $object->merchantReference = $this->merchantReference;
        $object->paymentReference = $this->paymentReference;
        $object->userID = $this->userID;
        $object->amount = $this->amount;
        $object->currency = $this->currency;
        $object->transactionStatus = $this->transactionStatus;
        $object->orderStatus = $this->orderStatus;
        $object->additionalData = $this->additionalData;
        $object->mns_timestamp = $this->timestamp;
        $object->version = $this->version;

        return $object;
    }
}