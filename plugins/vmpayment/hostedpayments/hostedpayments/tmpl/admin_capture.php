<?php
/**
 * @var plgVmPaymentPayco $module
 */
$module = $viewData['module'];
$order = $viewData['order'];
?>
<table class="adminlist table paycoCaptureTable">
    <thead>
        <tr>
            <th><?php echo vmText::_ ('VMPAYMENT_UPG_PAYCO_CAPTURE_COLUMN_CAPTURE_REFERENCE_LABEL'); ?></th>
            <th><?php echo vmText::_ ('VMPAYMENT_UPG_PAYCO_CAPTURE_COLUMN_CAPTURE_AMOUNT_LABEL'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($viewData['captures'] as $capture): ?>
            <tr data-capture-ref="<?php echo $capture['payco_capture_reference']; ?>" data-capture-amount="<?php echo $capture['amount']; ?>">
                <?php $amountDisplay = vmPSPlugin::getAmountInCurrency($capture['amount'], $order['details']['BT']->user_currency_id); ?>
                <td><?php echo $capture['payco_capture_reference']; ?></td>
                <td><?php echo $amountDisplay['display']; ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td><strong><?php echo vmText::_ ('VMPAYMENT_UPG_PAYCO_CAPTURE_AMOUNT_LEFT'); ?></strong></td>
            <td><?php echo $viewData['capture_amount_left']; ?></td>
        </tr>
        <tr>
            <?php if($viewData['autocapture'] && count($viewData['captures']) == 0): ?>
            <td class="paycoCaptureFormSectionDisabled" rowspan="2"><?php echo vmText::_ ('VMPAYMENT_UPG_PAYCO_CAPTURE_AUTOCAPTURE_ENABLED'); ?></td>
            <?php else: ?>
            <td class="paycoCaptureFormSection" rowspan="2">
                <form id="paycoCaptureForm" action="index.php" method="post" name="updateOrderBEPayment" id="updateOrderBEPayment">
                    <?php echo JHtml::_( 'form.token' ); ?>
                    <input type="hidden" name="type" value="vmpayment"/>
                    <input type="hidden" name="name" value="hostedpayments"/>
                    <input type="hidden" name="view" value="plugin"/>
                    <input type="hidden" name="virtuemart_order_id" value="<?php echo $viewData['order_id']; ?>" />
                    <input type="hidden" name="virtuemart_paymentmethod_id" value="<?php echo $viewData['payment_method_id']; ?>" />
                    <input type="hidden" name="option" value="com_virtuemart"/>
                    <input type="hidden" name="upg_admin_action" value="capture"/>

                    <label name="payco_admin_capture_amount"><?php echo vmText::_ ('VMPAYMENT_UPG_PAYCO_CAPTURE_AMOUNT_LABEL'); ?></label>
                    <input type="text" name="payco_admin_capture_amount" />
                    <br />
                    <input id="payco_admin_capture_submit" type="submit" name="payco_admin_capture_submit" value="<?php echo vmText::_ ('VMPAYMENT_UPG_PAYCO_CAPTURE_SUBMIT_LABEL'); ?>" />
                </form>
            </td>
            <?php endif; ?>
        </tr>
    </tbody>
</table>
<?php vmJsApi::addJScript('vmPayco.updateOrderBEPayment',"
		jQuery(document).ready( function($) {
			jQuery('.updateOrderBEPayment').click(function() {
				document.updateOrderBEPayment.submit();
				return false;

	});
});
"); ?>