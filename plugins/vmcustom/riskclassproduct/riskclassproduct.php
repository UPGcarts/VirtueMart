<?php
defined('_JEXEC') or 	die( 'Direct Access to ' . basename( __FILE__ ) . ' is not allowed.' ) ;

if (!class_exists('vmCustomPlugin')) require(JPATH_VM_PLUGINS . DS . 'vmcustomplugin.php');

class plgVmCustomRiskclassproduct extends vmCustomPlugin
{

    function __construct(& $subject, $config)
    {

        parent::__construct($subject, $config);

        $varsToPush = array('hostedpayment_riskclass' => array(1,'int'));

        $this->setConfigParameterable('riskclassproduct_params',$varsToPush);

    }

    // get product param for this plugin on edit
    function plgVmOnProductEdit($field, $product_id, &$row,&$retValue)
    {
        if ($field->custom_element != $this->_name) return '';

        $options = array(
            0 =>'VMCUSTOM_RISKCLASSPRODUCT_TRUSTED',
            1 =>'VMCUSTOM_RISKCLASSPRODUCT_DEFAULT',
            2 =>'VMCUSTOM_RISKCLASSPRODUCT_HIGH'
        );

        $html = '
            <fieldset><legend>'.vmText::_('VMCUSTOM_RISKCLASSPRODUCT_FORMLABEL').'</legend>
            <table class="admintable">
        <tr><td>';
        $html .= VmHTML::row('select', 'VMCUSTOM_RISKCLASSPRODUCT_RISKCLASS', 'field['.$row.'][hostedpayment_riskclass]', $options, $field->hostedpayment_riskclass,'','value','text',false);

        $html .= '</td></tr></table></fieldset>';

        $retValue .= $html;
        $row++;
        return true;
    }

    function plgVmOnDisplayEdit($virtuemart_custom_id,&$customPlugin)
    {
        return $this->onDisplayEditBECustom($virtuemart_custom_id,$customPlugin);
    }

    function plgVmGetTablePluginParams($psType, $name, $id, &$xParams, &$varsToPush)
    {
        return $this->getTablePluginParams($psType, $name, $id, $xParams, $varsToPush);
    }

    function plgVmSetOnTablePluginParamsCustom($name, $id, &$table,$xParams)
    {
        return $this->setOnTablePluginParams($name, $id, $table,$xParams);
    }


    function plgVmOnStoreProduct($data,$plugin_param){
        // $this->tableFields = array ( 'id', 'virtuemart_product_id', 'virtuemart_custom_id', 'custom_specification_default1', 'custom_specification_default2' );

        echo 'oo';
    }

    /**
     * We must reimplement this triggers for joomla 1.7
     * vmplugin triggers note by Max Milbers
     */
    public function plgVmOnStoreInstallPluginTable($psType,$name) {
        return $this->onStoreInstallPluginTable($psType,$name);
    }


    function plgVmDeclarePluginParamsCustomVM3(&$data){
        return $this->declarePluginParams('custom', $data);
    }

    function OnStoreProduct ($data, $plugin_param)
    {
        if (key ($plugin_param) !== $this->_name) {
            vmdebug('OnStoreProduct return because key '.key ($plugin_param).'!== '. $this->_name);
            return;
        }
    }

}