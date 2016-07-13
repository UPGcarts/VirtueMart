<?php
$order = $viewData['order'];
?>
<form id="paycoRefundForm" action="index.php" method="post" name="updateOrderBEPayment" id="updateOrderBEPayment">
    <?php echo JHtml::_( 'form.token' ); ?>
    <input type="hidden" name="type" value="vmpayment"/>
    <input type="hidden" name="name" value="hostedpayments"/>
    <input type="hidden" name="view" value="plugin"/>
    <input type="hidden" name="virtuemart_order_id" value="<?php echo $viewData['order_id']; ?>" />
    <input type="hidden" name="virtuemart_paymentmethod_id" value="<?php echo $viewData['payment_method_id']; ?>" />
    <input type="hidden" name="option" value="com_virtuemart"/>
    <input type="hidden" name="upg_admin_action" value="refund"/>
    <table class="adminlist table">
        <thead>
        <tr>
            <th><?php echo vmText::_ ('VMPAYMENT_UPG_PAYCO_CAPTURE_COLUMN_CAPTURE_REFERENCE_LABEL'); ?></th>
            <th><?php echo vmText::_ ('VMPAYMENT_UPG_PAYCO_CAPTURE_COLUMN_REFUND_DESCRIPTION_LABEL'); ?></th>
            <th><?php echo vmText::_ ('VMPAYMENT_UPG_PAYCO_CAPTURE_COLUMN_REFUND_AMOUNT_LABEL'); ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            <?php foreach($viewData['refunds'] as $refund): ?>
                <tr data-refund-capture-reference="<?php echo $refund['payco_capture_reference']; ?>" data-refund-amount="<?php echo $refund['amount']; ?>">
                    <?php $amountDisplay = vmPSPlugin::getAmountInCurrency($refund['amount'], $order['details']['BT']->user_currency_id); ?>
                    <td data-refund-field="capture-reference"><?php echo $refund['payco_capture_reference']; ?></td>
                    <td data-refund-field="refund-description"><?php echo $refund['refund_description']; ?></td>
                    <td data-refund-field="amount"><?php echo $amountDisplay['display']; ?></td>
                    <td></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td>
                    <?php if($viewData['canRefundWhenNoCapture']): ?>
                        <p><?php echo vmText::_ ('VMPAYMENT_UPG_PAYCO_REFUND_CAN_ATTEMPT'); ?></p>
                        <input name="payco_refund_capture_id" type="hidden" value="ATTEMPT" />
                    <?php else: ?>
                    <select name="payco_refund_capture_id">
                        <option value=""><?php echo vmText::_ ('VMPAYMENT_UPG_PAYCO_REFUND_SELECT_CAPTURE'); ?></option>
                        <?php foreach($viewData['captures'] as $capture): ?>
                            <option value="<?php echo $capture['payco_capture_id']; ?>" data-is-valid-capture="1"><?php echo $capture['payco_capture_reference'].' : '.$capture['amount']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php endif; ?>
                </td>
                <td><input type="text" name="payco_refund_description" /></td>
                <td><input type="text" name="payco_refund_amount" /></td>
                <td>
                    <input id="payco_admin_refund_submit" type="submit" name="payco_admin_refund_submit" value="<?php echo vmText::_ ('VMPAYMENT_UPG_PAYCO_REFUND_SUBMIT_LABEL'); ?>" />
                </td>
            </tr>
        </tbody>
    </table>
</form>