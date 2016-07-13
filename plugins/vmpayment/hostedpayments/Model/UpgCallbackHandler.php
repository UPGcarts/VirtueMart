<?php

require_once(dirname(__FILE__) . '/../vendor/autoload.php');

class UpgCallbackHandler implements \Upg\Library\Callback\ProcessorInterface
{
    const NOTIFICATION_TYPE_PAYMENT_STATUS= 'PAYMENT_STATUS';

    private $notificationType;
    private $merchantID;
    private $storeID;
    private $orderID;
    private $paymentMethod;
    private $resultCode;
    private $merchantReference;
    private $paymentInstrumentID;
    private $paymentInstrumentsPageUrl;
    private $additionalInformation = array();
    private $message;

    /**
     * @var \Upg\Library\Config
     */
    private $config;

    /**
     * @var plgVmPaymentUpg
     */
    private $module;

    public function __construct(\Upg\Library\Config $config, plgVmPaymentHostedpayments $module)
    {
        $this->config = $config;
        $this->module = $module;
    }

    /**
     * Send data to the processor that will be used in the run method
     * Unless specified most parameters will not be blank
     *
     * @param $notificationType This is the notification type which can be PAYMENT_STATUS, PAYMENT_INSTRUMENT_SELECTION
     * @param $merchantID This is the merchantID assigned by PayCo.
     * @param $storeID This is the store ID of a merchant assigned by PayCo as a merchant can have more than one store.
     * @param $orderID This is the order number of the shop.
     * @param $paymentMethod This is the selected payment method
     * @param $resultCode 0 means OK, any other code means error
     * @param $merchantReference Reference that was set by the merchant during the createTransaction call. Optional
     * @param $paymentInstrumentID This is the payment instrument Id that was used
     * @param $paymentInstrumentsPageUrl This is the payment instruments page url.
     * Which may or may not be given depending on user flow and integration mode
     * @param array $additionalInformation Optional additional info in an associative array
     * @param $message Details about an error, otherwise not present. Optional
     */
    public function sendData(
        $notificationType,
        $merchantID,
        $storeID,
        $orderID,
        $paymentMethod,
        $resultCode,
        $merchantReference,
        $paymentInstrumentID,
        $paymentInstrumentsPageUrl,
        array $additionalInformation,
        $message
    )
    {
        $this->notificationType = $notificationType;
        $this->merchantID = $merchantID;
        $this->storeID = $storeID;
        $this->orderID = $orderID;
        $this->paymentMethod = trim($paymentMethod);
        $this->resultCode = $resultCode;
        $this->merchantReference = $merchantReference;
        $this->paymentInstrumentID = $paymentInstrumentID;
        $this->paymentInstrumentsPageUrl = $paymentInstrumentsPageUrl;
        $this->additionalInformation = $additionalInformation;
        $this->message = $message;
    }

    public function run()
    {
        if($this->module->validateCallBackUrl($this->paymentInstrumentsPageUrl)) {
            return $this->module->getUrl(array(
                'option' => 'com_virtuemart',
                'view' => 'plugin',
                'type' => 'vmpayment',
                'name' => 'hostedpayments',
                'action' => 'payment_recovery',
                'url' => $this->paymentInstrumentsPageUrl
            ));
        }else if ($this->notificationType == self::NOTIFICATION_TYPE_PAYMENT_STATUS && $this->resultCode == 0) {
            $db = JFactory::getDBO();
            /**
             * @var VirtueMartModelOrders $orderModel
             */
            $orderModel = VmModel::getModel('orders');
            $orderId = $orderModel::getOrderIdByOrderNumber($this->orderID);
            $order = $orderModel->getOrder($orderId);

            $orderData = array(
                'order_status' => $this->module->_currentMethod->status_return_success,
                'customer_notified' => 1,
                'comments' => ''
            );

            $paymentName = 'VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_'.$this->paymentMethod;
            $paymentNameHTML = '<span class="vmpayment_name">'.vmText::_($paymentName).'</span><br />';

            $orderModel->updateStatusForOneOrder($order['details']['BT']->virtuemart_order_id, $orderData, TRUE);
            $paymentMethod = $db->quote($this->paymentMethod);
            $paymentNameHTML = $db->quote($paymentNameHTML);
            $updateQuery = "UPDATE #__virtuemart_payment_plg_hostedpayments
                          SET upg_payment_method = {$paymentMethod}, payment_name = {$paymentNameHTML}
                          WHERE virtuemart_order_id = ".$order['details']['BT']->virtuemart_order_id;
            $db->setQuery($updateQuery);
            $db->execute();

            return $this->module->getUrl(array(
                'option' => 'com_virtuemart',
                'view' => 'pluginresponse',
                'task' => 'pluginresponsereceived',
                'pm' => $this->module->_currentMethod->virtuemart_paymentmethod_id,
                'success' => 1,
                'orderID' => $this->orderID
            ));
        } else {

            $orderModel = VmModel::getModel('orders');
            $orderId = $orderModel::getOrderIdByOrderNumber($this->orderID);
            $order = $orderModel->getOrder($orderId);

            $orderData = array(
                'order_status' => $this->module->_currentMethod->status_return_failure,
                'customer_notified' => 1,
                'comments' => ''
            );

            $orderModel->updateStatusForOneOrder($order['details']['BT']->virtuemart_order_id, $orderData, TRUE);

            return $this->module->getUrl(array(
                'option' => 'com_virtuemart',
                'view' => 'pluginresponse',
                'task' => 'pluginresponsereceived',
                'pm' => $this->module->_currentMethod->virtuemart_paymentmethod_id,
                'error' => 1,
                'orderID' => $this->orderID
            ));
        }
    }


}