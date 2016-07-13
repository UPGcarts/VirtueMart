<?php
$order = $viewData['order'];
?>
<form id="paycoApiActionForm" action="index.php" method="post" name="updateOrderBEPayment" id="updateOrderApiPayment">
    <?php echo JHtml::_( 'form.token' ); ?>
    <input type="hidden" name="type" value="vmpayment"/>
    <input type="hidden" name="name" value="hostedpayments"/>
    <input type="hidden" name="view" value="plugin"/>
    <input type="hidden" name="virtuemart_order_id" value="<?php echo $viewData['order_id']; ?>" />
    <input type="hidden" name="virtuemart_paymentmethod_id" value="<?php echo $viewData['payment_method_id']; ?>" />
    <input type="hidden" name="option" value="com_virtuemart"/>
    <input type="hidden" name="upg_admin_action" value="api"/>
    <select name="upg_api_call_action">
        <option value=""><?php echo vmText::_ ('VMPAYMENT_UPG_PAYCO_API_ACTION_SELECT'); ?></option>
        <option value="FINISH"><?php echo vmText::_ ('VMPAYMENT_UPG_PAYCO_API_ACTION_FINISH'); ?></option>
        <option value="CANCEL"><?php echo vmText::_ ('VMPAYMENT_UPG_PAYCO_API_ACTION_CANCEL'); ?></option>
    </select>
    <input id="upg_api_call_action_submit" type="submit" name="upg_api_call_action_submit" value="<?php echo vmText::_ ('VMPAYMENT_UPG_PAYCO_API_SUBMIT_LABEL'); ?>" />
</form>