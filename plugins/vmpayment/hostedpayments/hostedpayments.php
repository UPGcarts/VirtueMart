<?php

defined('_JEXEC') or die('Restricted access');
if (!class_exists('vmPSPlugin')) {
    require(JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');
}

require_once(dirname(__FILE__) . '/vendor/autoload.php');

class plgVmPaymentHostedpayments extends vmPSPlugin
{
    const URL_LIVE = 'https://www.pay-co.net/2.0/';
    const URL_SANDBOX = 'https://sandbox.upgplc.com/2.0/';

    function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);

        $jlang = JFactory::getLanguage ();
        $jlang->load ('plg_vmpayment_upg', JPATH_ADMINISTRATOR, NULL, TRUE);

        $this->tableFields = array_keys($this->getTableSQLFields());
        $this->_tablepkey = 'id'; //virtuemart_paypal_id';
        $this->_tableId = 'id'; //'virtuemart_paypal_id';

        $varsToPush = array(
            'merchantid'                => array('', 'char'),
            'password'                => array('', 'char'),
            'storeid'               => array('', 'char'),
            'loglocation'            => array('', 'char'),
            'loglevel'            => array('', 'char'),
            'mode'           => array('', 'char'),
            'riskclass'             => array('', 'int'),
            'autocapture'         => array('', 'int'),
            'defultlocale'             => array('', 'char'),
            'payment_currency'          => array(0, 'int'),
            'countries'                 => array(0, 'char'),
            'min_amount'                => array(0, 'int'),
            'max_amount'                => array(0, 'int'),
            'cost_per_transaction'      => array(0, 'int'),
            'cost_percent_total'        => array(0, 'int'),
            'tax_id'                    => array(0, 'int'),
            'status_return_success'     => array('', 'char'),
            'status_return_failure'     => array('', 'char'),
            'mns_paid_autocapture'     => array('', 'char'),
            'mns_paid_reserve'     => array('', 'char'),
            'mns_paid_pending_reserve'     => array('', 'char'),
            'mns_payment_failed'     => array('', 'char'),
            'mns_fraud_cancelled'     => array('', 'char'),
            'mns_payment_chargeback'  => array('', 'char'),
            'mns_payment_cleared'  => array('', 'char'),
            'mns_in_dunning'  => array('', 'char'),
            'mns_acknowledge_pending'  => array('', 'char'),
            'mns_fraud_pending'  => array('', 'char'),
            'mns_cia_pending'  => array('', 'char'),
            'mns_transaction_merchant_pending'  => array('', 'char'),
            'mns_transaction_in_progress'  => array('', 'char'),
            'mns_transaction_cancelled'  => array('', 'char'),
            'mns_transaction_expired'  => array('', 'char'),
            'mns_transaction_done'  => array('', 'char'),
            //Restrictions
            'countries' => array('', 'char'),
            'currency' => array('', 'int'),
            'min_amount' => array('', 'float'),
            'max_amount' => array('', 'float'),
            'publishup' => array('', 'char'),
            'publishdown' => array('', 'char'),
        );
        $this->setConfigParameterable($this->_configTableFieldName, $varsToPush);

        if (!JFactory::getApplication()->isSite()) {
            JFactory::getDocument()->addStyleSheet(JURI::root(true) . '/plugins/vmpayment/payco/payco/assets/css/admin.css');
        }
    }

    function getTableSQLFields()
    {
        $SQLfields = array(
            'id'                          => 'int(1) UNSIGNED NOT NULL AUTO_INCREMENT',
            'virtuemart_order_id'         => 'int(1) UNSIGNED',
            'order_number'                => 'char(64)',
            'virtuemart_paymentmethod_id' => 'mediumint(1) UNSIGNED',
            'payment_name'                => 'varchar(5000)',
            'payment_order_total'         => 'decimal(15,5) NOT NULL DEFAULT \'0.00000\'',
            'payment_currency'            => 'char(3)',
            'email_currency'              => 'char(3)',
            'cost_per_transaction'        => 'decimal(10,2)',
            'cost_min_transaction'        => 'decimal(10,2)',
            'cost_percent_total'          => 'decimal(10,2)',
            'tax_id'                      => 'smallint(1)',
            'payco_order_id'             => 'int(11) UNSIGNED DEFAULT NULL',
            'payco_transaction_id'       => 'char(32) DEFAULT NULL',
            'payco_status'               => 'char(32) DEFAULT \'NEW\'',
            'autocapture'                => 'smallint(1)',
            'upg_payment_method'         => 'char(50) DEFAULT NULL',
        );

        return $SQLfields;
    }

    function onExtensionAfterInstall($installer, $eid)
    {
        $this->addGenderField();
        $this->addCheckoutB2bField();
        $this->createCaptureAndRefundTables();
    }

    function onExtensionAfterUpdate($installer, $eid)
    {
        $this->addGenderField();
        $this->addCheckoutB2bField();
        $this->createCaptureAndRefundTables();
    }

    public function addGenderField()
    {
        $db = JFactory::getDBO();

        $db->setQuery("SELECT virtuemart_userfield_id FROM #__virtuemart_userfields WHERE `name`='upg_gender' LIMIT 1");
        $checkRow = $db->loadRow();

        if(empty($checkRow)) {
            $addFieldQuery = "INSERT INTO `#__virtuemart_userfields` (`virtuemart_vendor_id`, `userfield_jplugin_id`, `name`, `title`, `description`, `type`, `maxlength`, `size`, `required`, `cols`, `rows`, `value`, `default`, `registration`, `shipment`, `account`, `cart`, `readonly`, `calculated`, `sys`, `userfield_params`, `ordering`, `shared`, `published`) VALUES
        (0, 0, 'upg_gender', 'VMPAYMENT_UPG_PAYCO_SHOPPER_GENDER', '', \"select\", 0, 210, 1, NULL, NULL, NULL, NULL, 1, 0, 1, 0, 0, 0, 1, '', 22, 0, 1)";

            $db->setQuery($addFieldQuery);
            $db->execute();

            $db->setQuery("SELECT virtuemart_userfield_id FROM #__virtuemart_userfields WHERE `name`='upg_gender'");
            $row = $db->loadRow();
            $id = $row[0];

            $addGenderQuery = "INSERT INTO `#__virtuemart_userfield_values` ( `virtuemart_userfield_id`, `fieldtitle`, `fieldvalue`, `sys`, `ordering`, `created_on`, `created_by`, `modified_on`, `modified_by`, `locked_on`, `locked_by`) VALUE
        ( " . $id . ", 'VMPAYMENT_UPG_PAYCO_SHOPPER_GENDER_MALE', 'M', 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
	( " . $id . ", 'VMPAYMENT_UPG_PAYCO_SHOPPER_GENDER_FEMALE', 'F', 0, 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0)";

            $db->setQuery($addGenderQuery);
            $db->execute();
        }
    }

    public function addUpgShopperField()
    {
        $config = JFactory::getConfig();
        $db = JFactory::getDBO();

        $fields = $db->getTableColumns('#__virtuemart_shoppergroups');


        if(!array_key_exists('upg_riskclass', $fields)) {
            $updateTableQuery = "ALTER TABLE `#__virtuemart_shoppergroups` ADD COLUMN `upg_riskclass` CHAR(2) NOT NULL default '';";

            $db->setQuery($updateTableQuery);
            $db->execute();
        }
    }

    public function addCheckoutB2bField()
    {
        $db = JFactory::getDBO();
        $db->setQuery("SELECT virtuemart_userfield_id FROM #__virtuemart_userfields WHERE `name`='upg_company_registration_id' LIMIT 1");
        $checkRow = $db->loadRow();

        if(empty($checkRow)) {
            $addFieldQuery = "INSERT INTO #__virtuemart_userfields ( `virtuemart_vendor_id`, `userfield_jplugin_id`, `name`, `title`, `description`, `type`, `maxlength`, `size`, `required`, `cols`, `rows`, `value`, `default`, `registration`, `shipment`, `account`, `cart`, `readonly`, `calculated`, `sys`, `userfield_params`, `ordering`, `shared`, `published`, `created_on`, `created_by`, `modified_on`, `modified_by`, `locked_on`, `locked_by`) VALUES(1, 0, 'upg_company_tax_id', 'VMPAYMENT_UPG_PAYCO_COMPANY_TAX', '', 'text', 30, 30, 0, 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0, '', 53, 0, 0, '2016-04-27 14:59:23', 510, '2016-04-27 14:59:23', 510, '0000-00-00 00:00:00', 0);";

            $db->setQuery($addFieldQuery);
            $db->execute();
        }

        $db->setQuery("SELECT virtuemart_userfield_id FROM #__virtuemart_userfields WHERE `name`='upg_company_vat_id' LIMIT 1");
        $checkRow = $db->loadRow();

        if(empty($checkRow)) {
            $addFieldQuery = "INSERT INTO #__virtuemart_userfields ( `virtuemart_vendor_id`, `userfield_jplugin_id`, `name`, `title`, `description`, `type`, `maxlength`, `size`, `required`, `cols`, `rows`, `value`, `default`, `registration`, `shipment`, `account`, `cart`, `readonly`, `calculated`, `sys`, `userfield_params`, `ordering`, `shared`, `published`, `created_on`, `created_by`, `modified_on`, `modified_by`, `locked_on`, `locked_by`) VALUES(1, 0, 'upg_company_vat_id', 'VMPAYMENT_UPG_PAYCO_VAT_ID', '', 'text', 30, 30, 0, 0, 0, NULL, '', 0, 0, 1, 0, 0, 0, 0, '', 52, 0, 0, '2016-04-27 14:58:32', 510, '2016-04-27 14:58:32', 510, '0000-00-00 00:00:00', 0);";

            $db->setQuery($addFieldQuery);
            $db->execute();
        }

        $db->setQuery("SELECT virtuemart_userfield_id FROM #__virtuemart_userfields WHERE `name`='upg_company_registration_id' LIMIT 1");
        $checkRow = $db->loadRow();

        if(empty($checkRow)) {
            $addFieldQuery = "INSERT INTO #__virtuemart_userfields ( `virtuemart_vendor_id`, `userfield_jplugin_id`, `name`, `title`, `description`, `type`, `maxlength`, `size`, `required`, `cols`, `rows`, `value`, `default`, `registration`, `shipment`, `account`, `cart`, `readonly`, `calculated`, `sys`, `userfield_params`, `ordering`, `shared`, `published`, `created_on`, `created_by`, `modified_on`, `modified_by`, `locked_on`, `locked_by`) VALUES(1, 0, 'upg_company_registration_id', 'VMPAYMENT_UPG_PAYCO_COMPANY_REG', '', 'text', 30, 30, 0, 0, 0, NULL, '', 0, 0, 1, 0, 0, 0, 0, '', 51, 0, 0, '2016-04-27 14:53:46', 510, '2016-04-27 14:57:30', 510, '0000-00-00 00:00:00', 0);";

            $db->setQuery($addFieldQuery);
            $db->execute();
        }

        $db->setQuery("SELECT virtuemart_userfield_id FROM #__virtuemart_userfields WHERE `name`='upg_company_registration_type' LIMIT 1");
        $checkRow = $db->loadRow();

        if(empty($checkRow)) {
            $addFieldQuery = "INSERT INTO #__virtuemart_userfields (`virtuemart_vendor_id`, `userfield_jplugin_id`, `name`, `title`, `description`, `type`, `maxlength`, `size`, `required`, `cols`, `rows`, `value`, `default`, `registration`, `shipment`, `account`, `cart`, `readonly`, `calculated`, `sys`, `userfield_params`, `ordering`, `shared`, `published`, `created_on`, `created_by`, `modified_on`, `modified_by`, `locked_on`, `locked_by`) VALUES(1, 0, 'upg_company_registration_type', 'VMPAYMENT_UPG_PAYCO_COMPANY_REG_TYPE', '', 'select', 0, 0, 0, 0, 0, NULL, '', 0, 0, 0, 0, 0, 0, 0, '', 54, 0, 0, '2016-04-27 15:08:21', 510, '2016-04-27 15:08:21', 510, '0000-00-00 00:00:00', 0);";

            $db->setQuery($addFieldQuery);
            $db->execute();

            $db->setQuery("SELECT virtuemart_userfield_id FROM #__virtuemart_userfields WHERE `name`='upg_company_registration_type'");
            $row = $db->loadRow();
            $id = $row[0];

            $addOptionsQuery = "INSERT INTO #__virtuemart_userfield_values (`virtuemart_userfield_id`, `fieldtitle`, `fieldvalue`, `sys`, `ordering`, `created_on`, `created_by`, `modified_on`, `modified_by`, `locked_on`, `locked_by`) VALUES
({$id}, 'VMPAYMENT_UPG_PAYCO_COMPANY_REG_TYPE_LUF', 'LUF', 0, 11, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
({$id}, 'VMPAYMENT_UPG_PAYCO_COMPANY_REG_TYPE_LUE', 'LUE', 0, 10, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
({$id}, 'VMPAYMENT_UPG_PAYCO_COMPANY_REG_TYPE_LUD', 'LUD', 0, 9, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
({$id}, 'VMPAYMENT_UPG_PAYCO_COMPANY_REG_TYPE_LUC', 'LUC', 0, 8, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
({$id}, 'VMPAYMENT_UPG_PAYCO_COMPANY_REG_TYPE_LUB', 'LUB', 0, 7, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
({$id}, 'VMPAYMENT_UPG_PAYCO_COMPANY_REG_TYPE_LUA', 'LUA', 0, 6, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
({$id}, 'VMPAYMENT_UPG_PAYCO_COMPANY_REG_TYPE_VERR', 'VERR', 0, 5, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
({$id}, 'VMPAYMENT_UPG_PAYCO_COMPANY_REG_TYPE_GENR', 'GENR', 0, 4, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
({$id}, 'VMPAYMENT_UPG_PAYCO_COMPANY_REG_TYPE_PARTR', 'PARTR', 0, 3, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
({$id}, 'VMPAYMENT_UPG_PAYCO_COMPANY_REG_TYPE_HRB', 'HRB', 0, 2, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
({$id}, 'VMPAYMENT_UPG_PAYCO_COMPANY_REG_TYPE_HRA', 'HRA', 0, 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
({$id}, 'VMPAYMENT_UPG_PAYCO_COMPANY_REG_TYPE_FN', 'FN', 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0);";

            $db->setQuery($addOptionsQuery);
            $db->execute();
        }

    }

    public function createCaptureAndRefundTables()
    {
        $db = JFactory::getDBO();

        $captureTableQuery = "CREATE TABLE IF NOT EXISTS `#__virtuemart_payment_plg_payco_capture` (
            payco_capture_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            virtuemart_order_id INT,
            payco_capture_reference VARCHAR(32),
            amount DECIMAL(12,2),
            PRIMARY KEY `#__virtuemart_payment_plg_payco_capture`(`payco_capture_id`),
            INDEX `payment_plg_capture_reference`(payco_capture_reference),
            INDEX `payment_plg_payco_capture_virtuemart_order_id`(virtuemart_order_id)
        )ENGINE=InnoDB;";

        $db->setQuery($captureTableQuery);
        $db->execute();

        $refundTableQuery = "CREATE TABLE IF NOT EXISTS `#__virtuemart_payment_plg_payco_refund` (
            payco_refund_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            payco_capture_id INT UNSIGNED NOT NULL,
            amount DECIMAL(12,2),
            refund_description VARCHAR(255),
            PRIMARY KEY `payment_plg_payco_refund`(`payco_refund_id`),
            INDEX `payment_plg_payco_refund_capture_id`(payco_capture_id)
        )ENGINE=InnoDB;";

        $db->setQuery($refundTableQuery);
        $db->execute();
    }

    public function createMnsMessagesTable()
    {
        $db = JFactory::getDBO();

        $mnsTableQuery = "CREATE TABLE IF NOT EXISTS `#__virtuemart_payment_plg_payco_mns_messages` (
            id_mns INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            merchantID INT(16) UNSIGNED NOT NULL,
            storeID VARCHAR(255) NOT NULL,
            orderID VARCHAR(255) NOT NULL,
            captureID VARCHAR(255) NOT NULL,
            merchantReference VARCHAR(255) NOT NULL,
            paymentReference VARCHAR(255) NOT NULL,
            userID VARCHAR(255) NOT NULL,
            amount INT(16) UNSIGNED NOT NULL,
            currency VARCHAR(255) NOT NULL,
            transactionStatus VARCHAR(255) NOT NULL,
            orderStatus VARCHAR(255) NOT NULL,
            additionalData TEXT,
            mns_timestamp BIGINT UNSIGNED NOT NULL,
            version VARCHAR(255),
            mns_processed TINYINT(1) DEFAULT 0,
            mns_error_processing TINYINT(1) DEFAULT 0,
            INDEX `payco_mns_mns_processed`(mns_processed),
            INDEX `payco_mns_mns_timestamp`(mns_timestamp),
            INDEX `payco_mns_mns_order_id`(orderID),
            INDEX `payco_mns_mns_error_processing`(mns_error_processing)
        )ENGINE=InnoDB;";

        $db->setQuery($mnsTableQuery);
        $db->execute();
    }

    public function getApiConfigObject($method)
    {
        $data = array();

        $data['merchantID'] = $method->merchantid;
        $data['merchantPassword'] = $method->password;
        $data['storeID'] = $method->storeid;

        $data['defaultLocale'] = $method->defultlocale;

        $url = self::URL_SANDBOX;

        if($method->mode == 'LIVE') {
            $url = self::URL_LIVE;
        }

        $data['baseUrl'] = $url;

        $method->loglocation = trim($method->loglocation);
        if(!empty($method->loglocation)) {
            $data['logLocationMain'] = $method->loglocation;
            $data['logLocationRequest'] = $method->loglocation;
            $data['logEnabled'] = true;
            $data['logLevel'] = $method->loglevel;
        }

        return new \Upg\Library\Config($data);
    }

    public function getUrl(array $params = array())
    {
        $url = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        if(stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ) {
            $url = "https:".$url;
        }else{
            $url = "http:".$url;
        }

        $url = str_replace($_SERVER['QUERY_STRING'],'',$url);
        $url = str_replace('?','',$url);

        if(!empty($params)) {
            $url = $url.'?'.http_build_query($params);
        }

        return $url;
    }

    public function validateCallBackUrl($url)
    {
        if(empty($url)){
            return false;
        }

        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            return false;
        }

        $parsedUrl = parse_url($url);

        return in_array($parsedUrl['scheme'], array('http','https'));
    }


    function plgVmOnPaymentNotification()
    {
        global $vmLogger;

        if(!class_exists('UpgCallbackHandler')) {
            require_once(dirname(__FILE__) . DS .'Model'. DS .'UpgCallbackHandler.php');
        }

        if (!class_exists('VirtueMartModelOrders')) {
            require(VMPATH_ADMIN . DS . 'models' . DS . 'orders.php');
        }

        if (!($virtuemart_order_id = VirtueMartModelOrders::getOrderIdByOrderNumber($_GET['orderID']))) {
            return FALSE;
        }

        if (!($payments = $this->getDatasByOrderNumber($_GET['orderID']))) {
            return FALSE;
        }

        $this->_currentMethod = $this->getVmPluginMethod($payments[0]->virtuemart_paymentmethod_id);
        if (!$this->selectedThisElement($this->_currentMethod->payment_element)) {
            return FALSE;
        }

        //$method = $this->_currentMethod;
        //echo $this->getUrl();exit;
        $errorUrl = $this->getUrl(array(
            'option' => 'com_virtuemart',
            'view' => 'pluginresponse',
            'task' => 'pluginresponsereceived',
            'pm' => $this->_currentMethod->virtuemart_paymentmethod_id,
            'error' => 1,
            'orderID' => $_GET['orderID']
        ));

        //http://desktop.com/virtuemart/index.php?option=com_virtuemart&view=pluginresponse&task=pluginnotification&tmpl=component&pm=3
        $data = array(
            'notificationType' => (array_key_exists('notificationType',$_GET)?$_GET['notificationType']:''),
            'merchantID' => (array_key_exists('merchantID',$_GET)?$_GET['merchantID']:''),
            'storeID' => (array_key_exists('storeID',$_GET)?$_GET['storeID']:''),
            'orderID' => (array_key_exists('orderID',$_GET)?$_GET['orderID']:''),
            'paymentMethod' => (array_key_exists('paymentMethod',$_GET)?$_GET['paymentMethod']:''),
            'resultCode' => (array_key_exists('resultCode',$_GET)?$_GET['resultCode']:''),
            'merchantReference' => (array_key_exists('merchantReference',$_GET)?$_GET['merchantReference']:''),
            'additionalInformation' => (array_key_exists('additionalInformation',$_GET)?$_GET['additionalInformation']:''),
            'paymentInstrumentsPageUrl' => (array_key_exists('paymentInstrumentsPageUrl',$_GET)?$_GET['paymentInstrumentsPageUrl']:''),
            'paymentInstrumentID' => (array_key_exists('paymentInstrumentID',$_GET)?$_GET['paymentInstrumentID']:''),
            'message' => (array_key_exists('message',$_GET)?$_GET['message']:''),
            'salt' => (array_key_exists('salt',$_GET)?$_GET['salt']:''),
            'mac' => (array_key_exists('mac',$_GET)?$_GET['mac']:''),
        );

        $config = $this->getApiConfigObject($this->_currentMethod);

        $processor = new UpgCallbackHandler($config, $this);

        try {
            $handler = new \Upg\Library\Callback\Handler($config, $data, $processor);
            echo $result = $handler->run();
        }catch (\Upg\Library\Callback\Exception\MacValidation $e) {
            vmError("Hmac validation failed for {$data['orderID']}");

            echo json_encode(array('url'=>$errorUrl));

        }catch (Exception $e) {
            vmError("Critical error {$data['orderID']} {$e->getMessage()}");

            echo json_encode(array('url'=>$errorUrl));
        }

        exit;

    }

    /**
     * Order sucess page
     * @param $html
     * @return null|string
     */
    function plgVmOnPaymentResponseReceived(&$html) {
        VmConfig::loadJLang('com_virtuemart_orders', TRUE);
        if (!class_exists('CurrencyDisplay')) {
            require(VMPATH_ADMIN . DS . 'helpers' . DS . 'currencydisplay.php');
        }
        if (!class_exists('VirtueMartCart')) {
            require(VMPATH_SITE . DS . 'helpers' . DS . 'cart.php');
        }
        if (!class_exists('shopFunctionsF')) {
            require(VMPATH_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');
        }
        if (!class_exists('VirtueMartModelOrders')) {
            require(VMPATH_ADMIN . DS . 'models' . DS . 'orders.php');
        }

        $virtuemart_paymentmethod_id = vRequest::getInt('pm', 0);
        $order_number = vRequest::getString('orderID', 0);

        if (!($method = $this->getVmPluginMethod($virtuemart_paymentmethod_id))) {
            return NULL; // Another method was selected, do nothing
        }

        if (!($virtuemart_order_id = VirtueMartModelOrders::getOrderIdByOrderNumber($order_number))) {
            return NULL;
        }
        if (!($paymentTables = $this->getDatasByOrderId($virtuemart_order_id))) {
            // JError::raiseWarning(500, $db->getErrorMsg());
            return '';
        }

        if (!($payments = $this->getDatasByOrderNumber($_GET['orderID']))) {
            return FALSE;
        }

        $this->_currentMethod = $this->getVmPluginMethod($payments[0]->virtuemart_paymentmethod_id);
        if (!$this->selectedThisElement($this->_currentMethod->payment_element)) {
            return FALSE;
        }

        VmConfig::loadJLang('com_virtuemart');
        $orderModel = VmModel::getModel('orders');
        $order = $orderModel->getOrder($virtuemart_order_id);
        $html = '';

        if($_GET['success'] == 1) {
            $paymentCurrency = CurrencyDisplay::getInstance($order['details']['BT']->order_currency);
            $totalInPaymentCurrency = vmPSPlugin::getAmountInCurrency($order['details']['BT']->order_total,
                $method->payment_currency);

            //empty the cart
            $cart = VirtueMartCart::getCart();
            if(!empty($cart)) {
                $cart->emptyCart();
            }

            $db = JFactory::getDBO();

            $paymentMethodQuery = "SELECT upg_payment_method
          FROM #__virtuemart_payment_plg_hostedpayments
          WHERE virtuemart_order_id = ".$virtuemart_order_id;

            $db->setQuery($paymentMethodQuery);
            $db->execute();
            $paymentMethod = $db->loadResult();

            $nb = count($paymentTables);
            $pluginName = $this->renderPluginName($method, 'post_payment');
            $html = $this->renderByLayout('post_payment', array(
                'order' => $order,
                'order_number' => $order['details']['BT']->order_number,
                'order_pass' => $order['details']['BT']->order_pass,
                'payment_name' => vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_'.trim(strtoupper($paymentMethod))),
                'payment_code' => trim(strtoupper($paymentMethod)),
                'pluginName' => $pluginName,
                'displayTotalInPaymentCurrency' => $totalInPaymentCurrency['display']
            ));
            vmdebug('_getPaymentResponseHtml', $paymentTables);
        }else{
            $pluginName = $this->renderPluginName($method, 'post_payment');
            $totalInPaymentCurrency = vmPSPlugin::getAmountInCurrency($order['details']['BT']->order_total,
                $method->payment_currency);
            $nb = count($paymentTables);
            $html = $this->renderByLayout('payment_error', array(
                'order' => $order,
                'order_number' => $order['details']['BT']->order_number,
                'paymentInfos' => $paymentTables[$nb - 1],
                'pluginName' => $pluginName,
                'displayTotalInPaymentCurrency' => $totalInPaymentCurrency['display']
            ));
        }

        if(!empty($this->customerData)) {
            $this->customerData->clear();
        }

        return $html;
    }

    private function formatCurrencyForApi($amount)
    {
        $currencyDisplay = CurrencyDisplay::getInstance();
        $amountInCurrency = $currencyDisplay->roundForDisplay($amount);
        return intval('0'.($amountInCurrency  * 100));
    }

    /**
     * @param $cart
     * @param $order
     * @return bool|null
     */
    public function plgVmConfirmedOrder($cart, $order)
    {
        if (!($this->_currentMethod = $this->getVmPluginMethod($order['details']['BT']->virtuemart_paymentmethod_id))) {
            return NULL; // Another method was selected, do nothing
        }
        if (!$this->selectedThisElement($this->_currentMethod->payment_element)) {
            return FALSE;
        }

        if (!class_exists('VirtueMartModelOrders')) {
            require(VMPATH_ADMIN . DS . 'models' . DS . 'orders.php');
        }
        if (!class_exists('VirtueMartModelCurrency')) {
            require(VMPATH_ADMIN . DS . 'models' . DS . 'currency.php');
        }

        $config = $this->getApiConfigObject($this->_currentMethod);

        try{
            $request = $this->getOrderRequest($order, $config);
            $apiEndPoint = new \Upg\Library\Api\CreateTransaction($config, $request);
            $result = $apiEndPoint->sendRequest();

            $this->saveTransaction($order, $config, $request);

            $data = array(
                'url' => $result->getData('redirectUrl')
            );

            $html = $this->renderByLayout('iframe_page', $data);
            vRequest::setVar('html', $html);
            vRequest::setVar('display_title', false);
            return;

        }catch (Exception $e) {
            $html = $this->renderByLayout('payment_request_error', array());
            vRequest::setVar('html', $html);
        }

    }

    private function saveTransaction(array $order, \Upg\Library\Config $config, \Upg\Library\Request\CreateTransaction $request)
    {
        $method = $this->getVmPluginMethod ($order['details']['BT']->virtuemart_paymentmethod_id);
        $currency_code_3 = shopFunctions::getCurrencyByID($method->payment_currency, 'currency_code_3');
        $email_currency = $this->getEmailCurrency($method);
        $totalInPaymentCurrency = vmPSPlugin::getAmountInCurrency($order['details']['BT']->order_total,$method->payment_currency);
        //save transaction details
        $dbValues['payment_name'] = $this->renderPluginName ($method) . '<br />' . $method->payment_info;
        $dbValues['order_number'] = $order['details']['BT']->order_number;
        $dbValues['virtuemart_paymentmethod_id'] = $order['details']['BT']->virtuemart_paymentmethod_id;
        $dbValues['cost_per_transaction'] = $method->cost_per_transaction;
        $dbValues['cost_min_transaction'] = $method->cost_min_transaction;
        $dbValues['cost_percent_total'] = $method->cost_percent_total;
        $dbValues['payment_currency'] = $currency_code_3;
        $dbValues['email_currency'] = $email_currency;
        $dbValues['payment_order_total'] = $totalInPaymentCurrency['value'];
        $dbValues['tax_id'] = $method->tax_id;
        $dbValues['autocapture'] = ($request->getAutoCapture()?1:0);
        $this->storePSPluginInternalData($dbValues);
    }

    /**
     * Get the request object
     * @param array $order
     * @param \Upg\Library\Config $config
     * @return \Upg\Library\Request\CreateTransaction
     */
    private function getOrderRequest(array $order, \Upg\Library\Config $config)
    {
        $request = new \Upg\Library\Request\CreateTransaction($config);
        $request = $this->populateCustomerObjectForTransaction($order, $request);
        $request = $this->populateBillingAddressObjectForTransaction($order, $request);
        $request = $this->populateDeliveryAddressObjectForTransaction($order, $request);
        $request = $this->populateBasketItemsObjectForTransaction($order, $request);
        $request = $this->populateBusinessForTransaction($order, $request);

        $autoCapture = ($this->_currentMethod->autocapture?true:false);

        //Do the rounding on the amount
        $amount = $this->formatCurrencyForApi($order['details']['BT']->order_total);

        //now set the order fields not covered by the methods above
        $request->setIntegrationType(\Upg\Library\Request\CreateTransaction::INTEGRATION_TYPE_HOSTED_AFTER)
            ->setOrderID($order['details']['BT']->order_number)
            ->setAutoCapture($autoCapture)
            ->setContext(\Upg\Library\Request\CreateTransaction::CONTEXT_ONLINE)
            ->setUserRiskClass(intval($this->_currentMethod->riskclass))
            ->setLocale($this->getLocale())
            ->setAmount(new \Upg\Library\Request\Objects\Amount($amount));

        //switch
        $userId = $order['details']['BT']->virtuemart_user_id;
        if($userId == 0) {
            $userId = 'GUEST:ORDER:'.$order['details']['BT']->virtuemart_order_id;
        }

        $request->setUserID($userId.'-'.$request->getUserType());

        $userModel = VmModel::getModel('user');
        $user = $userModel->getCurrentUser();
        $shopperModel = VmModel::getModel('shoppergroup');

        if(count($user->shopper_groups)>0) {
            $sprgrp = $shopperModel->getShopperGroup($user->shopper_groups[0]);
            if(property_exists($sprgrp, 'upg_riskclass')) {
                if($sprgrp->upg_riskclass === '0' || $sprgrp->upg_riskclass === '1' || $sprgrp->upg_riskclass === '2') {
                    $request->setUserRiskClass(intval($sprgrp->upg_riskclass));
                }
            }
        }

        return $request;
    }

    /**
     * Add customer data to request
     * @param array $order
     * @param \Upg\Library\Request\CreateTransaction $request
     * @return \Upg\Library\Request\CreateTransaction
     */
    private function populateCustomerObjectForTransaction(array $order, \Upg\Library\Request\CreateTransaction $request)
    {
        $user = new \Upg\Library\Request\Objects\Person();
        $user->setSalutation($order['details']['BT']->upg_gender)
            ->setName($order['details']['BT']->first_name)
            ->setSurname($order['details']['BT']->last_name)
            ->setEmail($order['details']['BT']->email);

        $request->setUserData($user)->setUserType(\Upg\Library\Request\CreateTransaction::USER_TYPE_PRIVATE);

        return $request;
    }

    private function populateBusinessForTransaction(array $order, \Upg\Library\Request\CreateTransaction $request)
    {
        $companyName = $this->checkIfOrderPropertyExistsNotEmpty('BT', 'company', $order['details']);
        $companyRegistrationId = $this->checkIfOrderPropertyExistsNotEmpty('BT', 'upg_company_registration_id', $order['details']);
        $companyVatId = $this->checkIfOrderPropertyExistsNotEmpty('BT', 'upg_company_vat_id', $order['details']);
        $companyTaxId = $this->checkIfOrderPropertyExistsNotEmpty('BT', 'upg_company_tax_id', $order['details']);
        $companyRegistrationType = $this->checkIfOrderPropertyExistsNotEmpty('BT', 'upg_company_registration_type', $order['details']);

        if(!empty($companyName) && preg_match('/(\d|[a-z]|[A-Z]){1,30}/', $companyRegistrationId)) {
            $company = new \Upg\Library\Request\Objects\Company();
            $company->setCompanyName($companyName)->setCompanyRegistrationID($companyRegistrationId);

            if (preg_match('/(\d|[a-z]|[A-Z]){1,30}/', $companyVatId)) {
                $company->setCompanyVatID($companyVatId);
            }
            if (preg_match('/(\d|[a-z]|[A-Z]){1,30}/', $companyTaxId)) {
                $company->setCompanyTaxID($companyTaxId);
            }
            if (!empty($companyRegistrationType)) {
                $company->setCompanyRegisterType($companyRegistrationType);
            }

            $request->setUserType(\Upg\Library\Request\CreateTransaction::USER_TYPE_BUSINESS);
            $request->setCompanyData($company);
        }

        return $request;
    }

    private function checkIfOrderPropertyExistsNotEmpty($orderPropertyKey, $propertySectionKey, $order)
    {
        $item = '';

        if(array_key_exists($orderPropertyKey, $order)) {
            $object = $order[$orderPropertyKey];
            if(property_exists($order[$orderPropertyKey], $propertySectionKey)) {
                if(!empty($object->$propertySectionKey)) {
                    return $object->$propertySectionKey;
                }
            }
        }

        return $item;
    }


    /**
     * Populate billing address
     * @param array $order
     * @param \Upg\Library\Request\CreateTransaction $request
     * @return \Upg\Library\Request\CreateTransaction
     */
    private function populateBillingAddressObjectForTransaction(array $order, \Upg\Library\Request\CreateTransaction $request)
    {
        $address = new \Upg\Library\Request\Objects\Address();

        $street = $order['details']['BT']->address_1.' '.$order['details']['BT']->address_2;
        $country = ShopFunctions::getCountryByID($order['details']['BT']->virtuemart_country_id, 'country_2_code');

        $address->setStreet($street)
        ->setZip($order['details']['BT']->zip)
        ->setCity($order['details']['BT']->city)
        ->setCountry(strtoupper($country));

        $request->setBillingAddress($address);
        return $request;
    }

    private function populateDeliveryAddressObjectForTransaction(array $order, \Upg\Library\Request\CreateTransaction $request)
    {
        $address = new \Upg\Library\Request\Objects\Address();

        $customerST = isset($order['details']['ST']) ? $order['details']['ST'] : $order['details']['BT'];
        $street = $customerST->address_1.' '.$customerST->address_2;
        $country = ShopFunctions::getCountryByID($customerST->virtuemart_country_id, 'country_2_code');

        $address->setStreet($street)
            ->setZip($customerST->zip)
            ->setCity($customerST->city)
            ->setCountry(strtoupper($country));

        $request->setShippingAddress($address);
        return $request;
    }

    /**
     * populate basket items
     * @param array $order
     * @param \Upg\Library\Request\CreateTransaction $request
     * @return \Upg\Library\Request\CreateTransaction
     */
    private function populateBasketItemsObjectForTransaction(array $order, \Upg\Library\Request\CreateTransaction $request)
    {
        $calculated = 0;

        foreach($order['items'] as $basketItem) {
            $amountValue = $this->formatCurrencyForApi($basketItem->product_subtotal_with_tax);

            $calculated += $amountValue;
            $item = new \Upg\Library\Request\Objects\BasketItem();

            $item->setBasketItemText($basketItem->order_item_name)
                ->setBasketItemCount($basketItem->product_quantity)
                ->setBasketItemID($basketItem->virtuemart_order_item_id)
                ->setBasketItemAmount(new \Upg\Library\Request\Objects\Amount($amountValue))
                ->setBasketItemType(\Upg\Library\Basket\BasketItemType::BASKET_ITEM_TYPE_DEFAULT);

            //ok now going to the item
            if(property_exists($basketItem, 'customfields')) {
                $customFields = $basketItem->customfields;
                if (!empty($customFields)) {
                    foreach ($customFields as $field) {
                        if ($field->custom_value == 'riskclassproduct') {
                            $item->setBasketItemRiskClass(intval($field->hostedpayment_riskclass));
                        }
                    }
                }
            }

            $request->addBasketItem($item);
        }

        $shipping = $order['details']['BT']->order_shipment + $order['details']['BT']->order_shipment_tax;

        if($shipping > 0) {
            $item = new \Upg\Library\Request\Objects\BasketItem();

            $calculated += $this->formatCurrencyForApi($shipping);

            $item->setBasketItemText('Shipping')
                ->setBasketItemCount(1)
                ->setBasketItemAmount(new \Upg\Library\Request\Objects\Amount($this->formatCurrencyForApi($shipping)))
                ->setBasketItemType(\Upg\Library\Basket\BasketItemType::BASKET_ITEM_TYPE_SHIPPINGCOST);

            $request->addBasketItem($item);
        }

        $orderAmount = $this->formatCurrencyForApi($order['details']['BT']->order_total);

        $discount = $calculated - $orderAmount;

        if($discount > 0) {

            $item = new \Upg\Library\Request\Objects\BasketItem();
            $name = $order['details']['BT']->coupon_code;
            if(empty($name)) {
                $name = 'Discount';
            }

            $item->setBasketItemText($name)
                ->setBasketItemCount(1)
                ->setBasketItemAmount(new \Upg\Library\Request\Objects\Amount($discount))
                ->setBasketItemType(\Upg\Library\Basket\BasketItemType::BASKET_ITEM_TYPE_COUPON);

            $request->addBasketItem($item);

        }


        return $request;
    }

    private function getLocale()
    {
        $language = JFactory::getLanguage();
        $isoCode = strtoupper(substr($language->get('tag'), 0, 2));

        switch($isoCode) {
            case \Upg\Library\Locale\Codes::LOCALE_EN:
            case \Upg\Library\Locale\Codes::LOCALE_DE:
            case \Upg\Library\Locale\Codes::LOCALE_ES:
            case \Upg\Library\Locale\Codes::LOCALE_FI:
            case \Upg\Library\Locale\Codes::LOCALE_FR:
            case \Upg\Library\Locale\Codes::LOCALE_NL:
            case \Upg\Library\Locale\Codes::LOCALE_IT:
            case \Upg\Library\Locale\Codes::LOCALE_RU:
            case \Upg\Library\Locale\Codes::LOCALE_TU:
                return $isoCode;
                break;
            default:
                return $this->_currentMethod->defultlocale;
                break;
        }

        return $isoCode;
    }

    public function checkConditions($cart, $activeMethod, $cart_prices)
    {
        if ($activeMethod->publishup) {
            $nowDate = JFactory::getDate();
            $publish_up = JFactory::getDate($activeMethod->publishup);
            if ($publish_up->toUnix() > $nowDate->toUnix()) {
                return FALSE;
            }
        }
        if ($activeMethod->publishdown) {
            $nowDate = JFactory::getDate();
            $publish_down = JFactory::getDate($activeMethod->publishdown);
            if ($publish_down->toUnix() <= $nowDate->toUnix()) {
                return FALSE;
            }
        }

        $currencyCondition = (!empty($activeMethod->currency) && $activeMethod->currency == $cart->pricesCurrency);

        $amount = $this->getCartAmount($cart_prices);
        $amount_cond = ($amount >= $activeMethod->min_amount AND $amount <= $activeMethod->max_amount
            OR
            ($activeMethod->min_amount <= $amount AND ($activeMethod->max_amount == 0)));

        $address = $cart->getST();

        $countries = array();
        if (!empty($activeMethod->countries)) {
            if (!is_array($activeMethod->countries)) {
                $countries[0] = $activeMethod->countries;
            } else {
                $countries = $activeMethod->countries;
            }
        }

        if (in_array($address['virtuemart_country_id'], $countries) || count($countries) == 0) {
                return $currencyCondition & $amount_cond;
        }

        return false;
    }

    function plgVmOnStoreInstallPaymentPluginTable($jplugin_id)
    {
        $this->addGenderField();
        $this->addCheckoutB2bField();
        $this->createCaptureAndRefundTables();
        $this->createMnsMessagesTable();
        $this->addUpgShopperField();
        return $this->onStoreInstallPluginTable($jplugin_id);
    }
    public function plgVmOnSelectCheckPayment(VirtueMartCart $cart) {
        return $this->OnSelectCheck($cart);
    }
    public function plgVmDisplayListFEPayment(VirtueMartCart $cart, $selected = 0, &$htmlIn)
    {
        return $this->displayListFE($cart, $selected, $htmlIn);
    }
    public function plgVmonSelectedCalculatePricePayment(VirtueMartCart $cart, array &$cart_prices, &$cart_prices_name)
    {
        return $this->onSelectedCalculatePrice($cart, $cart_prices, $cart_prices_name);
    }
    function plgVmOnCheckAutomaticSelectedPayment (VirtueMartCart $cart, array $cart_prices = array(), &$paymentCounter)
    {
        return $this->onCheckAutomaticSelected ($cart, $cart_prices, $paymentCounter);
    }

    /**
     * Show payment on front end
     * @param $virtuemart_order_id
     * @param $virtuemart_paymentmethod_id
     * @param $payment_name
     */
    public function plgVmOnShowOrderFEPayment($virtuemart_order_id, $virtuemart_paymentmethod_id, &$payment_name)
    {
        if (!($this->selectedThisByMethodId($virtuemart_paymentmethod_id))) {
            return NULL;
        }

        if (!($this->_currentMethod = $this->getVmPluginMethod($virtuemart_paymentmethod_id))) {
            return NULL; // Another method was selected, do nothing
        }

        $payments = $this->getDatasByOrderId($virtuemart_order_id);

        try{
            /**
             * @var VirtueMartModelOrders $orderModel
             */
            $orderModel = VmModel::getModel('orders');
            $order = $orderModel->getOrder($virtuemart_order_id);

            $statusRequest = new \Upg\Library\Request\GetTransactionStatus($this->getApiConfigObject($this->_currentMethod));

            $statusRequest->setOrderID($order['details']['BT']->order_number);
            $apiEndPoint = new \Upg\Library\Api\GetTransactionStatus($this->getApiConfigObject($this->_currentMethod), $statusRequest);
            $result = $apiEndPoint->sendRequest();
            $data = $this->getPaymentInfoArray($result);

            $payment_name = $this->renderByLayout('order_fe', array(
                'data' => $data,
            ));


        }catch (Exception $e){
            JLog::add("Could not get order status: {$order['details']['BT']->order_number} ", JLog::WARNING, 'upg');
            $this->onShowOrderFE($virtuemart_order_id, $virtuemart_paymentmethod_id, $payment_name);
            return true;
        }

        return true;
    }

    function plgVmonShowOrderPrintPayment($order_number, $method_id)
    {
        //return $this->onShowOrderPrint($order_number, $method_id);
        if (!$this->selectedThisByMethodId ($method_id)) {
            return NULL; // Another method was selected, do nothing
        }

        VmConfig::loadJLang('com_virtuemart');

        /**
         * @var VirtueMartModelOrders $orderModel
         */
        $orderModel = VmModel::getModel('orders');
        $virtuemart_order_id = $orderModel->getOrderIdByOrderNumber($order_number);
        $order = $orderModel->getOrder($virtuemart_order_id);

        try{
            $statusRequest = new \Upg\Library\Request\GetTransactionStatus($this->getApiConfigObject($this->_currentMethod));
            $statusRequest->setOrderID($order['details']['BT']->order_number);

            $apiEndPoint = new \Upg\Library\Api\GetTransactionStatus($this->getApiConfigObject($this->_currentMethod), $statusRequest);
            $result = $apiEndPoint->sendRequest();
            $data = $this->getPaymentInfoArray($result);

            $html = '<table class="admintable">';
            $html .= '<tr>';

            foreach($data as $name=>$value) {
                $html .= "<td class=\"key\">{$name}</td><td class=\"left\">{$value}</td>";
            }

            $html .= '</tr>';
            $html .= '</table>';

            return $html;
        }catch (Exception $e){
            JLog::add("Could not get order status: {$order['details']['BT']->order_number} ", JLog::WARNING, 'upg');
            return null;
        }

    }

    private function getPaymentInfoArray(\Upg\Library\Response\SuccessResponse $response)
    {
        $additionalData = $response->getData('additionalData');
        $paymentMethod = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_'.strtoupper($additionalData['paymentMethod']));

        $data = array(
            vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_SELECTION_LABEL') => $paymentMethod
        );

        if(array_key_exists('bankname', $additionalData)) {
            $label = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_BANKNAME_LABEL');
            $data[$label] = $additionalData['bankname'];
        }

        if(array_key_exists('bic', $additionalData)) {
            $label = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_BIC_LABEL');
            $data[$label] = $additionalData['bic'];
        }

        if(array_key_exists('iban', $additionalData)) {
            $label = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_IBAN_LABEL');
            $data[$label] = $additionalData['iban'];
        }

        if(array_key_exists('bankAccountHolder', $additionalData)) {
            $label = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_BANKACCOUNTHOLDER_LABEL');
            $data[$label] = $additionalData['bankAccountHolder'];
        }

        if(array_key_exists('paymentReference', $additionalData)) {
            $label = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_PAYMENTREFERENCE_LABEL');
            $data[$label] = $additionalData['paymentReference'];
        }

        if(array_key_exists('sepaMandate', $additionalData)) {
            $label = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_SEPAMANDATE_LABEL');
            $data[$label] = $additionalData['sepaMandate'];
        }

        if(array_key_exists('email', $additionalData)) {
            $label = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_EMAIL_LABEL');
            $data[$label] = $additionalData['email'];
        }

        if(array_key_exists('deliveryAddressCo', $additionalData)) {
            $label = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSCO_LABEL');
            $data[$label] = $additionalData['deliveryAddressCo'];
        }

        if(array_key_exists('deliveryAddressZip', $additionalData)) {
            $label = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSZIP_LABEL');
            $data[$label] = $additionalData['deliveryAddressZip'];
        }

        if(array_key_exists('deliveryAddressNo', $additionalData)) {
            $label = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSNO_LABEL');
            $data[$label] = $additionalData['deliveryAddressNo'];
        }

        if(array_key_exists('deliveryAddressNoAdditional', $additionalData)) {
            $label = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSNOADDITIONAL_LABEL');
            $data[$label] = $additionalData['deliveryAddressNoAdditional'];
        }

        if(array_key_exists('deliveryAddressCity', $additionalData)) {
            $label = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSCITY_LABEL');
            $data[$label] = $additionalData['deliveryAddressCity'];
        }

        if(array_key_exists('deliveryAddressState', $additionalData)) {
            $label = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSSTATE_LABEL');
            $data[$label] = $additionalData['deliveryAddressState'];
        }

        if(array_key_exists('deliveryAddressStreet', $additionalData)) {
            $label = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSSTREET_LABEL');
            $data[$label] = $additionalData['deliveryAddressStreet'];
        }

        if(array_key_exists('deliveryAddressRecipient', $additionalData)) {
            $label = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSRECIPIENT_LABEL');
            $data[$label] = $additionalData['deliveryAddressRecipient'];
        }

        if(array_key_exists('deliveryAddressCountry', $additionalData)) {
            $label = vmText::_('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSCOUNTRY_LABEL');
            $data[$label] = $additionalData['deliveryAddressCountry'];
        }

        return $data;
    }

    function plgVmSetOnTablePluginParamsPayment($name, $id, &$table)
    {
        return $this->setOnTablePluginParams($name, $id, $table);
    }

    function plgVmDeclarePluginParamsPaymentVM3( &$data)
    {
        return $this->declarePluginParams('payment', $data);
    }

    /**
     * Show payment info
     * @param $virtuemart_order_id
     * @param $virtuemart_payment_id
     * @return null|string
     */
    function plgVmOnShowOrderBEPayment($virtuemart_order_id, $virtuemart_payment_id)
    {
        global $vmLogger;

        if (!$this->selectedThisByMethodId ($virtuemart_payment_id)) {
            return NULL; // Another method was selected, do nothing
        }

        if (!($paymentTable = $this->getDataByOrderId ($virtuemart_order_id))) {
            return NULL;
        }

        if (!($method = $this->getVmPluginMethod($virtuemart_payment_id))) {
            return NULL; // Another method was selected, do nothing
        }

        $html = '';

        VmConfig::loadJLang('com_virtuemart');
        $orderModel = VmModel::getModel('orders');
        $order = $orderModel->getOrder($virtuemart_order_id);

        $db = JFactory::getDBO();

        try{
            $statusRequest = new \Upg\Library\Request\GetTransactionStatus($this->getApiConfigObject($method));
            $statusRequest->setOrderID($order['details']['BT']->order_number);

            $apiEndPoint = new \Upg\Library\Api\GetTransactionStatus($this->getApiConfigObject($method), $statusRequest);
            $result = $apiEndPoint->sendRequest();

            $additionalData = $result->getData('additionalData');

            $html = '<table class="adminlist table">' . "\n";
            $html .= $this->getHtmlHeaderBE ();
            $html .= $this->getHtmlRowBE ('COM_VIRTUEMART_PAYMENT_NAME', $paymentTable->payment_name);

            //vmText::_ ('VMPAYMENT_STANDARD_PAYMENT_FRONTEND_ERROR');
            $paymentMethod = 'VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_'.strtoupper($additionalData['paymentMethod']);
            $paymentStatus = 'VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_STATUS_'.$result->getData('transactionStatus');

            $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_STATUS_LABEL', vmText::_ ($paymentStatus));
            $html .= $this->getHtmlRowBE ('COM_VIRTUEMART_PAYMENT_NAME', vmText::_ ($paymentMethod));

            if(array_key_exists('bankname', $additionalData)) {
                $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_BANKNAME_LABEL', $additionalData['bankname']);
            }

            if(array_key_exists('bic', $additionalData)) {
                $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_BIC_LABEL', $additionalData['bic']);
            }

            if(array_key_exists('iban', $additionalData)) {
                $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_IBAN_LABEL', $additionalData['iban']);
            }

            if(array_key_exists('bankAccountHolder', $additionalData)) {
                $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_BANKACCOUNTHOLDER_LABEL', $additionalData['bankAccountHolder']);
            }

            if(array_key_exists('paymentReference', $additionalData)) {
                $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_PAYMENTREFERENCE_LABEL', $additionalData['paymentReference']);
            }

            if(array_key_exists('sepaMandate', $additionalData)) {
                $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_SEPAMANDATE_LABEL', $additionalData['sepaMandate']);
            }

            if(array_key_exists('email', $additionalData)) {
                $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_EMAIL_LABEL', $additionalData['email']);
            }

            if(array_key_exists('deliveryAddressCo', $additionalData)) {
                $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSCO_LABEL', $additionalData['deliveryAddressCo']);
            }

            if(array_key_exists('deliveryAddressZip', $additionalData)) {
                $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSZIP_LABEL', $additionalData['deliveryAddressZip']);
            }

            if(array_key_exists('deliveryAddressNo', $additionalData)) {
                $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSNO_LABEL', $additionalData['deliveryAddressNo']);
            }

            if(array_key_exists('deliveryAddressNoAdditional', $additionalData)) {
                $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSNOADDITIONAL_LABEL', $additionalData['deliveryAddressNoAdditional']);
            }

            if(array_key_exists('deliveryAddressCity', $additionalData)) {
                $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSCITY_LABEL', $additionalData['deliveryAddressCity']);
            }

            if(array_key_exists('deliveryAddressState', $additionalData)) {
                $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSSTATE_LABEL', $additionalData['deliveryAddressState']);
            }

            if(array_key_exists('deliveryAddressStreet', $additionalData)) {
                $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSSTREET_LABEL', $additionalData['deliveryAddressStreet']);
            }

            if(array_key_exists('deliveryAddressRecipient', $additionalData)) {
                $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSRECIPIENT_LABEL', $additionalData['deliveryAddressRecipient']);
            }

            if(array_key_exists('deliveryAddressCountry', $additionalData)) {
                $html .= $this->getHtmlRowBE ('VMPAYMENT_UPG_PAYCO_PAYMENTMETHOD_DELIVERYADDRESSCOUNTRY_LABEL', $additionalData['deliveryAddressCountry']);
            }

            $html .= '</table>' . "\n";

        }catch (Exception $e) {
            $orderNumber = $order['details']['BT']->order_number;
            $vmLogger->debug("For {$orderNumber} when looking up status got {$e->getMessage()}");
        }

        $autocaptureQuery = "SELECT autocapture
          FROM #__virtuemart_payment_plg_hostedpayments
          WHERE virtuemart_order_id = ".$virtuemart_order_id;

        $db->setQuery($autocaptureQuery);
        $autocaptureData = $db->loadObject();



        //fetch the captures for capture display and the refund
        $capturesQuery = "SELECT *
          FROM #__virtuemart_payment_plg_payco_capture
          WHERE virtuemart_order_id = ".$virtuemart_order_id;

        $db->setQuery($capturesQuery);
        $captures = $db->loadAssocList();

        //ok now add the capture form
        $html .= $this->renderByLayout('admin_capture', array(
            'order' => $order,
            'order_id' => $virtuemart_order_id,
            'payment_method_id' => $virtuemart_payment_id,
            'capture_amount_left' => $this->calculateCaptureAmountLeft($order, $virtuemart_order_id),
            'captures' => $captures,
            'module' => $this,
            'autocapture' => $autocaptureData->autocapture,
        ));

        $refundQuery = "SELECT payco_refund_table.*, payco_capture_table.payco_capture_reference
          FROM #__virtuemart_payment_plg_payco_refund AS payco_refund_table
          INNER JOIN #__virtuemart_payment_plg_payco_capture AS payco_capture_table
          ON payco_capture_table.payco_capture_id = payco_refund_table.payco_capture_id
          WHERE payco_capture_table.virtuemart_order_id = ".$virtuemart_order_id;

        $db->setQuery($refundQuery);
        $db->execute();
        $refunds = $db->loadAssocList();

        $html .= $this->renderByLayout('admin_refund', array(
            'order' => $order,
            'order_id' => $virtuemart_order_id,
            'payment_method_id' => $virtuemart_payment_id,
            'captures' => $captures,
            'refunds' => $refunds,
            'canRefundWhenNoCapture' => $this->canSendRefundIfNocaptureIsAvailible($order, $virtuemart_order_id),
        ));

        $html .= $this->renderByLayout('admin_api_function', array(
            'order' => $order,
            'order_id' => $virtuemart_order_id,
            'payment_method_id' => $virtuemart_payment_id,
        ));

        return $html;
    }

    private function canSendRefundIfNocaptureIsAvailible(array $order, $virtuemart_order_id)
    {
        $db = JFactory::getDBO();

        $paymentMethodQuery = "SELECT upg_payment_method
                          FROM #__virtuemart_payment_plg_hostedpayments
                          WHERE virtuemart_order_id = {$virtuemart_order_id}";

        $db->setQuery($paymentMethodQuery);
        $paymentMethodResult = $db->loadRow();

        $paymentMethod = (is_array($paymentMethodResult)?current($paymentMethodResult):'');

        switch($paymentMethod){
            case 'BILL':
            case 'BILL_SECURE':
            case 'DD':
                //ok now check if autocapture is enabled
                $autocaptureSql = "SELECT autocapture
                  FROM #__virtuemart_payment_plg_hostedpayments
                  WHERE virtuemart_order_id = ".$virtuemart_order_id;

                $db->setQuery($autocaptureSql);
                $autocaptureData = $db->loadObject();

                if($autocaptureData->autocapture) {
                    return true;
                }

                break;
            default:
                break;
        }

        return false;
    }

    private function calculateCaptureAmountLeft(array $order, $virtuemart_order_id)
    {
        $db = JFactory::getDBO();

        $paymentMethodQuery = "SELECT upg_payment_method
                          FROM #__virtuemart_payment_plg_hostedpayments
                          WHERE virtuemart_order_id = {$virtuemart_order_id}";

        $db->setQuery($paymentMethodQuery);
        $paymentMethodResult = $db->loadRow();

        $paymentMethod = (is_array($paymentMethodResult)?current($paymentMethodResult):'');

        $capturedAmountQuery = "SELECT SUM(amount) AS captured
              FROM #__virtuemart_payment_plg_payco_capture
              WHERE virtuemart_order_id = {$virtuemart_order_id}";

        $db->setQuery($capturedAmountQuery);
        $captureAmountResult = $db->loadRow();

        $capturedAmount = (is_array($captureAmountResult)?current($captureAmountResult):0);

        switch($paymentMethod){
            case 'BILL':
            case 'BILL_SECURE':
                //ok get the refund amount
                $refundQuery = "SELECT SUM(payco_refund_table.amount) AS refunded
                  FROM #__virtuemart_payment_plg_payco_refund AS payco_refund_table
                  INNER JOIN #__virtuemart_payment_plg_payco_capture AS payco_capture_table
                  ON payco_capture_table.payco_capture_id = payco_refund_table.payco_capture_id
                  WHERE payco_capture_table.virtuemart_order_id = {$virtuemart_order_id}";

                $db->setQuery($refundQuery);
                $refundAmountResult = $db->loadRow();

                $refundAmount = (is_array($refundAmountResult)?current($refundAmountResult):0);
                $capturedAmount = $capturedAmount - $refundAmount;
                break;
            default:
                break;
        }


        $captureAmountLeft = $order['details']['BT']->order_total - $capturedAmount;
        $amountFormated = vmPSPlugin::getAmountInCurrency($captureAmountLeft, $order['details']['BT']->user_currency_id);

        return $amountFormated['display'];
    }

    function plgVmOnSelfCallFE($type, $name, &$render)
    {
        //the url is /index.php?option=com_virtuemart&view=plugin&type=vmpayment&name=upg
        if ($name != $this->_name || $type != 'vmpayment') {
            return FALSE;
        }

        $action = vRequest::getCmd('action');

        switch($action){
            case 'mnssave':
                $this->doMnsSaveEvent();
                break;
            case 'mnsprocess':
                $this->doMnsProcess();
                break;
            case 'payment_recovery':
                return $this->doPaymentRecovery();
                break;
            default:
                return false;
            break;
        }

        return false;

        $order_number = vRequest::getString('orderID');
        $order_number = trim($order_number);

        if(empty($order_number)) {
            return FALSE;
        }

    }

    public function doPaymentRecovery()
    {
        $url = vRequest::getString('url');
        $html = $this->renderByLayout('payment_request_error', array());
        if($this->validateCallBackUrl($url)) {
            $data = array(
                'url' => $url,
            );
            vmJsApi::css( 'upg','plugins/vmpayment/hostedcallbackurls/hostedcallbackurls/assets/css/');
            $html = $this->renderByLayout('iframe_page', $data);

            vRequest::setVar('html', $html);
            vRequest::setVar('display_title', false);
        }
        echo $html;
        return true;
    }

    public function doMnsProcess()
    {
        if(!class_exists('UpgMnsProcessor')) {
            require_once(dirname(__FILE__) . DS .'Model'. DS .'UpgMnsProcessor.php');
        }

        $db = JFactory::getDBO();

        $mnsTasks = "SELECT mnstable.*, paymenttable.virtuemart_paymentmethod_id
                      FROM #__virtuemart_payment_plg_payco_mns_messages AS mnstable
                      INNER JOIN #__virtuemart_payment_plg_hostedpayments AS paymenttable
                      ON paymenttable.order_number = mnstable.orderID
                      WHERE mnstable.mns_processed=0
                      AND mnstable.mns_error_processing=0
                      ORDER BY mnstable.mns_timestamp ASC";

        $db->setQuery($mnsTasks);
        $db->execute();
        $tasks = $db->loadAssocList();


        foreach($tasks as $task) {
            //get the order and get the payment id
            $method = $this->getVmPluginMethod($task['virtuemart_paymentmethod_id']);
            $updateQuery = '';

            if(UpgMnsProcessor::processMessage($method, $task)){
                $updateQuery = 'UPDATE #__virtuemart_payment_plg_payco_mns_messages
                          SET mns_processed = 1
                          WHERE id_mns = '.$task['id_mns'];
            }else{
                JLog::add("Got error for mns for order: {$task['orderID']} ", JLog::WARNING, 'paycomns');
                $updateQuery = 'UPDATE #__virtuemart_payment_plg_payco_mns_messages
                          SET mns_error_processing = 1
                          WHERE id_mns = '.$task['id_mns'];
            }
            $db->setQuery($updateQuery);
            $db->execute();
        }
        exit;
    }

    public function doMnsSaveEvent()
    {
        if(!class_exists('UpgCallbackHandler')) {
            require_once(dirname(__FILE__) . DS .'Model'. DS .'UpgMnsHandler.php');
        }

        $db = JFactory::getDBO();

        $orderNumber = vRequest::getString('orderID');

        $virtuemartPaymentmethodIdQuery = "SELECT
            virtuemart_paymentmethod_id
          FROM #__virtuemart_payment_plg_hostedpayments
          WHERE order_number = ".$db->quote($orderNumber);

        $db->setQuery($virtuemartPaymentmethodIdQuery);
        $db->execute();
        $virtuemart_paymentmethod_id = $db->loadResult();

        if (!($this->_currentMethod = $this->getVmPluginMethod($virtuemart_paymentmethod_id))) {
            return NULL; // Another method was selected, do nothing
        }

        $config = $this->getApiConfigObject($this->_currentMethod);

        $data = array(
            'merchantID' => (array_key_exists('merchantID',$_POST)?$_POST['merchantID']:''),
            'storeID' => (array_key_exists('storeID',$_POST)?$_POST['storeID']:''),
            'orderID' => (array_key_exists('orderID',$_POST)?$_POST['orderID']:''),
            'captureID' => (array_key_exists('captureID',$_POST)?$_POST['captureID']:''),
            'merchantReference' => (array_key_exists('merchantReference',$_POST)?$_POST['merchantReference']:''),
            'paymentReference' => (array_key_exists('paymentReference',$_POST)?$_POST['paymentReference']:''),
            'userID' => (array_key_exists('userID',$_POST)?$_POST['userID']:''),
            'amount' => (array_key_exists('amount',$_POST)?$_POST['amount']:''),
            'currency' => (array_key_exists('currency',$_POST)?$_POST['currency']:''),
            'transactionStatus' => (array_key_exists('transactionStatus',$_POST)?$_POST['transactionStatus']:''),
            'orderStatus' => (array_key_exists('orderStatus',$_POST)?$_POST['orderStatus']:''),
            'additionalData' => (array_key_exists('additionalData',$_POST)?$_POST['additionalData']:''),
            'timestamp' => (array_key_exists('timestamp',$_POST)?$_POST['timestamp']:''),
            'version' => (array_key_exists('version',$_POST)?$_POST['version']:''),
            'mac' => (array_key_exists('mac',$_POST)?$_POST['mac']:''),
        );

        $processor = new UpgMnsHandler($db);

        try{
            $handler = new \Upg\Library\Mns\Handler($config, $data, $processor);
            $handler->run();
            header("HTTP/1.1 200 OK");
            exit;
        }catch (Exception $e) {
            header('HTTP/1.1 200 OK');
            exit;
        }
        exit;

    }

    /**
     * Handle admin actions
     * @param $type
     * @param $name
     * @param $render
     * @return null
     */
    public function plgVmOnSelfCallBE ($type, $name, &$render)
    {
        if ($name != $this->_name || $type != 'vmpayment') {
            return FALSE;
        }

        $virtuemart_paymentmethod_id = vRequest::getInt('virtuemart_paymentmethod_id');
        if (!($this->_currentMethod = $this->getVmPluginMethod($virtuemart_paymentmethod_id))) {
            return NULL; // Another method was selected, do nothing
        }

        if(!JSession::checkToken()) {
            return NULL; //check csrf token
        }

        $action = vRequest::getString('upg_admin_action');
        switch($action)
        {
            case 'capture':
                return $this->processCapture();
                break;
            case 'refund':
                return  $this->processRefund();
                break;
            case 'api':
                return  $this->processApiAction();
                break;
            default:
                break;
        }

    }

    private function processCapture()
    {
        $db = JFactory::getDBO();

        $method = $this->_currentMethod;
        $amount = vRequest::getString('payco_admin_capture_amount');
        $amount = str_replace(',','.',$amount);
        $virtuemart_order_id = vRequest::getInt('virtuemart_order_id');

        if (!($payments = $this->getDatasByOrderId($virtuemart_order_id))) {
            vmError(vmText::_('VMPAYMENT_UPG_PAYCO_CAPTURE_REFUND_ERROR_NON_PAYCO_ORDER'));
            return null;
        }

        if(!$this->validateCurrencyAmount($amount)) {
            vmError(vmText::_('VMPAYMENT_UPG_PAYCO_CAPTURE_REFUND_ERROR_AMOUNT'));
            $this->doOrderPageRedirect($virtuemart_order_id);
            return null;
        }

        $orderModel = VmModel::getModel('orders');
        $order = $orderModel->getOrder($virtuemart_order_id);

        $captureCountQuery = "SELECT
            COUNT(payco_capture_id) AS capture_count
          FROM #__virtuemart_payment_plg_payco_capture
          WHERE virtuemart_order_id = ".$virtuemart_order_id;

        $db->setQuery($captureCountQuery);
        $db->execute();
        $captureCount = $db->loadResult();

        $captureId = $order['details']['BT']->order_number.':'.++$captureCount;

        //now do the capture
        try{
            $amountValue = intval('0'.($amount  * 100));
            $captureRequest = new \Upg\Library\Request\Capture($this->getApiConfigObject($method));
            $captureRequest->setOrderID($order['details']['BT']->order_number)
                ->setCaptureID($captureId)
                ->setAmount(new \Upg\Library\Request\Objects\Amount($amountValue));

            $captureApi = new \Upg\Library\Api\Capture($this->getApiConfigObject($method), $captureRequest);
            $result = $captureApi->sendRequest();

            $query = "INSERT INTO #__virtuemart_payment_plg_payco_capture
                      (virtuemart_order_id, payco_capture_reference, amount)
                      VALUES ({$virtuemart_order_id},'{$captureId}', {$amount})
                  ";


            $db->setQuery($query);
            $db->execute();

        } catch (Exception $e) {
            vmError("Got an error with the capture for {$captureId} ".$e->getMessage());
            JLog::add("Got an error with the capture {$captureId} ".$e->getMessage(), JLog::WARNING, 'payco');
        }

        $this->doOrderPageRedirect($virtuemart_order_id);
    }

    private function processApiAction()
    {
        $db = JFactory::getDBO();

        $method = $this->_currentMethod;

        $virtuemart_order_id = vRequest::getInt('virtuemart_order_id');
        $orderModel = VmModel::getModel('orders');
        $order = $orderModel->getOrder($virtuemart_order_id);

        $action = vRequest::getString('upg_api_call_action');

        switch($action)
        {
            case 'FINISH':
                try {
                    $request = new \Upg\Library\Request\Finish($this->getApiConfigObject($method));
                    $request->setOrderID($order['details']['BT']->order_number);

                    $api = new \Upg\Library\Api\Finish($this->getApiConfigObject($method), $request);
                    $api->sendRequest();
                    vmInfo("Finish call was sent");
                }catch (Exception $e) {
                    vmError("Got an error with the finish call: ".$e->getMessage());
                }
                break;
            case 'CANCEL':
                try {
                    $request = new \Upg\Library\Request\Cancel($this->getApiConfigObject($method));
                    $request->setOrderID($order['details']['BT']->order_number);

                    $api = new \Upg\Library\Api\Cancel($this->getApiConfigObject($method), $request);
                    $api->sendRequest();
                    vmInfo("Cancel call was sent");
                }catch (Exception $e) {
                    vmError("Got an error with the cancel call: ".$e->getMessage());
                }
                break;
            default:
                break;
        }

        $this->doOrderPageRedirect($virtuemart_order_id);
    }

    private function processRefund()
    {
        $db = JFactory::getDBO();

        $method = $this->_currentMethod;
        $amount = vRequest::getString('payco_refund_amount');
        $amount = str_replace(',','.',$amount);
        $description = vRequest::getString('payco_refund_description');
        $description = trim($description);
        $captureId = vRequest::getInt('payco_refund_capture_id');
        $virtuemart_order_id = vRequest::getInt('virtuemart_order_id');

        $orderModel = VmModel::getModel('orders');
        $order = $orderModel->getOrder($virtuemart_order_id);

        if (!($payments = $this->getDatasByOrderId($virtuemart_order_id))) {
            return null;
        }

        if(!$this->validateCurrencyAmount($amount)) {
            vmError(vmText::_('VMPAYMENT_UPG_PAYCO_CAPTURE_REFUND_ERROR_AMOUNT'));
            $this->doOrderPageRedirect($virtuemart_order_id);
            return null;
        }

        if(empty($description)) {
            vmError(vmText::_('VMPAYMENT_UPG_PAYCO_CAPTURE_REFUND_ERROR_DESCRIPTION'));
            $this->doOrderPageRedirect($virtuemart_order_id);
            return null;
        }

        if(empty($captureId)) {
            $captureId = vRequest::getString('payco_refund_capture_id');
            if($captureId == 'ATTEMPT') {
                //ok create a capture record
                $autocaptureInsertSql = "INSERT INTO #__virtuemart_payment_plg_payco_capture
                      (virtuemart_order_id, payco_capture_reference, amount)
                      VALUES (
                      {$order['details']['BT']->virtuemart_order_id},
                      '{$order['details']['BT']->order_number}',
                      {$order['details']['BT']->order_total}
                      );
                  ";

                $db->setQuery($autocaptureInsertSql);
                $db->execute();

                $captureId = $db->insertid();
            }
        }

        if(empty($captureId)) {
            vmError(vmText::_('VMPAYMENT_UPG_PAYCO_REFUND_ERROR_CAPTURE'));
            $this->doOrderPageRedirect($virtuemart_order_id);
            return null;
        }

        //fetch the capture reference
        $captureQuery = "SELECT
            payco_capture_reference
          FROM #__virtuemart_payment_plg_payco_capture
          WHERE payco_capture_id = ".$captureId;

        $db->setQuery($captureQuery);
        $captureReference = $db->loadResult();

        try{
            $amountValue = intval('0'.($amount  * 100));
            $refundRequest = new \Upg\Library\Request\Refund($this->getApiConfigObject($method));
            $refundRequest->setCaptureID($captureReference)
                ->setOrderID($order['details']['BT']->order_number)
                ->setAmount(new \Upg\Library\Request\Objects\Amount($amountValue))
                ->setRefundDescription($description);

            $refundApi = new \Upg\Library\Api\Refund($this->getApiConfigObject($method), $refundRequest);
            $refundApi->sendRequest();

            $description = $db->escape($description);
            $description = $db->quote($description);

            $query = "INSERT INTO #__virtuemart_payment_plg_payco_refund
                      (payco_capture_id, amount, refund_description)
                      VALUES ({$captureId},$amount, {$description})
                  ";

            $db->setQuery($query);
            $db->execute();

        }catch (Exception $e) {
            vmError("There was an error with the refund for {$captureReference} ".$e->getMessage());
            JLog::add("Got an error with the refund for capture {$captureReference} ".$e->getMessage(), JLog::WARNING, 'upg');
        }

        $this->doOrderPageRedirect($virtuemart_order_id);
    }

    private function validateCurrencyAmount($amount)
    {
        if(empty($amount)){
            return false;
        }

        if(preg_match('/[1-9]{1}[0-9]*\.?[0-9]{0,2}/', $amount)) {
            return true;
        }

        return false;
    }

    private function doOrderPageRedirect($virtuemart_order_id)
    {
        $app = JFactory::getApplication();
        $link = 'index.php?option=com_virtuemart&view=orders&task=edit&virtuemart_order_id=' . $virtuemart_order_id;
        $app->redirect(JRoute::_($link, FALSE));
    }

}