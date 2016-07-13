<?php

require_once(dirname(__FILE__) . '/../vendor/autoload.php');

class UpgMnsProcessor
{
    /**
     * Process mns job
     * @param JDatabase $db Database
     * @param $paymentMethod Virtuemart Payment Method
     * @param array $mns The MNS message
     */
    public static function processMessage($paymentMethod, array $mns)
    {
        if (!class_exists('VirtueMartModelOrders')) {
            require(VMPATH_ADMIN . DS . 'models' . DS . 'orders.php');
        }

        if (!($virtuemart_order_id = VirtueMartModelOrders::getOrderIdByOrderNumber($mns['orderID']))) {
            JLog::add("Could not find for mns message {$mns['orderID']} ", JLog::WARNING, 'paycomns');
            return FALSE;
        }

        $orderModel = VmModel::getModel('orders');
        $order = $orderModel->getOrder($virtuemart_order_id);

        $mnsOrderStatus = trim(strtoupper($mns['orderStatus']));
        $mnsTransactionStatus = trim(strtoupper($mns['transactionStatus']));


        $processed = false;
        $orderStatusProcess = false;

        if(!empty($mnsTransactionStatus)) {
            switch($mnsTransactionStatus) {
                case 'FRAUDCANCELLED':
                    self::mnsTransactionStatusFraudCancel($order, $paymentMethod);
                    $processed = true;
                    break;
                case 'CANCELLED':
                case 'EXPIRED':
                    self::mnsTransactionStatusCancel($order, $paymentMethod);
                    $processed = true;
                    break;
                case 'NEW':
                    $processed = true;
                case 'ACKNOWLEDGEPENDING':
                    self::mnsTransactionStatusAcknowledgePending($order, $paymentMethod);
                    $processed = true;
                    break;
                case 'FRAUDPENDING':
                    self::mnsTransactionStatusFraudPending($order, $paymentMethod);
                    $processed = true;
                    break;
                case 'CIAPENDING':
                    self::mnsTransactionStatusCiaPending($order, $paymentMethod);
                    $processed = true;
                    break;
                case 'MERCHANTPENDING':
                    self::mnsTransactionStatusMerchantPending($order, $paymentMethod);
                    $processed = true;
                    break;
                case 'INPROGRESS':
                    self::mnsTransactionStatusInProgress($order, $paymentMethod);
                    $orderStatusProcess = true;
                    $processed = true;
                    break;
                case 'DONE':
                    self::mnsTransactionStatusDone($order, $paymentMethod);
                    $orderStatusProcess = true;
                    $processed = true;
                    break;
                default:
                    $processed = false;
                    break;
            }
        }

        if(!empty($mnsOrderStatus) && ($orderStatusProcess || !$processed)) {
            switch ($mnsOrderStatus) {
                case 'PAID':
                    self::mnsOrderStatusPaid($order, $paymentMethod);
                    $processed = true;
                    break;
                case 'PAYPENDING':
                    self::mnsOrderStatusPayPending($order, $paymentMethod);
                    $processed = true;
                    break;
                case 'PAYMENTFAILED':
                    self::mnsOrderStatusPaymentFailed($order, $paymentMethod);
                    $processed = true;
                    break;
                case 'CHARGEBACK':
                    self::mnsOrderStatusChargeBack($order, $paymentMethod);
                    $processed = true;
                    break;
                case 'CLEARED':
                    self::mnsOrderStatusCleared($order, $paymentMethod);
                    $processed = true;
                    break;
                case 'CPM_MANAGED':
                case 'INDUNNING':
                    self::mnsOrderInDunning($order, $paymentMethod);
                    break;
                default:
                    $processed = false;
                    break;
            }
        }



        if($processed) {
            return true;
        }else{
            return false;
        }
    }

    private static function updateStatus(array $order, array $statusData)
    {
        $orderModel = VmModel::getModel('orders');
        $orderModel->updateStatusForOneOrder($order['details']['BT']->virtuemart_order_id, $statusData, TRUE);
    }

    //now the code to do status changes
    public static function mnsTransactionStatusCancel(array $order, $paymentMethodConfig)
    {
        $orderData = array(
            'order_status' => $paymentMethodConfig->mns_transaction_cancelled,
            'customer_notified' => 0,
            'comments' => ''
        );

        self::updateStatus($order, $orderData);
    }

    public static function mnsTransactionStatusFraudCancel(array $order, $paymentMethodConfig)
    {
        $orderData = array(
            'order_status' => $paymentMethodConfig->mns_fraud_cancelled,
            'customer_notified' => 0,
            'comments' => ''
        );

        self::updateStatus($order, $orderData);
    }

    public static function mnsTransactionStatusAcknowledgePending(array $order, $paymentMethodConfig)
    {
        $orderData = array(
            'order_status' => $paymentMethodConfig->mns_acknowledge_pending,
            'customer_notified' => 0,
            'comments' => ''
        );

        self::updateStatus($order, $orderData);
    }

    public static function mnsTransactionStatusFraudPending(array $order, $paymentMethodConfig)
    {
        $orderData = array(
            'order_status' => $paymentMethodConfig->mns_fraud_pending,
            'customer_notified' => 0,
            'comments' => ''
        );

        self::updateStatus($order, $orderData);
    }

    public static function mnsTransactionStatusCiaPending(array $order, $paymentMethodConfig)
    {
        $orderData = array(
            'order_status' => $paymentMethodConfig->mns_cia_pending,
            'customer_notified' => 0,
            'comments' => ''
        );

        self::updateStatus($order, $orderData);
    }

    public static function mnsTransactionStatusMerchantPending(array $order, $paymentMethodConfig)
    {
        $orderData = array(
            'order_status' => $paymentMethodConfig->mns_transaction_merchant_pending,
            'customer_notified' => 0,
            'comments' => ''
        );

        self::updateStatus($order, $orderData);
    }

    public static function mnsTransactionStatusInProgress(array $order, $paymentMethodConfig)
    {
        $orderData = array(
            'order_status' => $paymentMethodConfig->mns_transaction_in_progress,
            'customer_notified' => 0,
            'comments' => ''
        );

        self::updateStatus($order, $orderData);
    }

    public static function mnsTransactionStatusDone(array $order, $paymentMethodConfig)
    {
        $orderData = array(
            'order_status' => $paymentMethodConfig->mns_transaction_done,
            'customer_notified' => 0,
            'comments' => ''
        );

        self::updateStatus($order, $orderData);
    }

    public static function mnsOrderStatusPaid(array $order, $paymentMethodConfig)
    {
        //check if the order
        $db = JFactory::getDBO();

        $autocaptureSql = "SELECT autocapture, upg_payment_method
              FROM #__virtuemart_payment_plg_hostedpayments
              WHERE virtuemart_order_id = ".$order['details']['BT']->virtuemart_order_id;

        $db->setQuery($autocaptureSql);
        $autocaptureData = $db->loadObject();

        $orderState = $paymentMethodConfig->mns_paid_reserve;

        if($autocaptureData->autocapture) {
            //insert an autocapture
            $orderId = $order['details']['BT']->virtuemart_order_id;
            $captureId = $order['details']['BT']->order_number;
            $amount = $order['details']['BT']->order_total;

            $autoCaptureCleanUp = "UPDATE #__virtuemart_payment_plg_payco_capture SET amount = 0 WHERE virtuemart_order_id = {$orderId}";

            $db->setQuery($autoCaptureCleanUp);
            $db->execute();

            $autocaptureInsertSql = "INSERT INTO #__virtuemart_payment_plg_payco_capture
                      (virtuemart_order_id, payco_capture_reference, amount)
                      VALUES (
                      {$orderId},
                      '{$captureId}',
                      {$amount}
                      );
                  ";

            $db->setQuery($autocaptureInsertSql);
            $db->execute();
            $orderState = $paymentMethodConfig->mns_paid_autocapture;
        }

        $orderData = array(
            'order_status' => $orderState,
            'customer_notified' => 0,
            'comments' => ''
        );

        self::updateStatus($order, $orderData);
    }

    public static function mnsOrderStatusPayPending(array $order, $paymentMethodConfig)
    {
        $orderData = array(
            'order_status' => $paymentMethodConfig->mns_paid_pending_reserve,
            'customer_notified' => 0,
            'comments' => ''
        );

        self::updateStatus($order, $orderData);
    }

    public static function mnsOrderStatusPaymentFailed(array $order, $paymentMethodConfig)
    {
        $orderData = array(
            'order_status' => $paymentMethodConfig->mns_payment_failed,
            'customer_notified' => 0,
            'comments' => ''
        );

        self::updateStatus($order, $orderData);
    }

    public static function mnsOrderStatusChargeBack(array $order, $paymentMethodConfig)
    {
        $orderData = array(
            'order_status' => $paymentMethodConfig->mns_payment_chargeback,
            'customer_notified' => 0,
            'comments' => ''
        );

        self::updateStatus($order, $orderData);
    }

    public static function mnsOrderStatusCleared(array $order, $paymentMethodConfig)
    {
        $orderData = array(
            'order_status' => $paymentMethodConfig->mns_payment_cleared,
            'customer_notified' => 0,
            'comments' => ''
        );

        self::updateStatus($order, $orderData);
    }

    public static function mnsOrderInDunning(array $order, $paymentMethodConfig)
    {
        $orderData = array(
            'order_status' => $paymentMethodConfig->mns_in_dunning,
            'customer_notified' => 0,
            'comments' => ''
        );

        self::updateStatus($order, $orderData);
    }

    private static function canPaymentBeAutocaptured($method)
    {
        $method = trim($method);
        switch($method) {
            case 'DD':
            case 'BILL':
            case 'BILL_SECURE':
                return false;
                break;
        }

        return true;
    }
}