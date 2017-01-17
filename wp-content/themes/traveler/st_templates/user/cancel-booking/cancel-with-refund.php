<?php
/**
*@since 1.2.8
*	cancel with refund
**/
?>
<div class="clearfix">
	<strong><?php echo __('Order ID: ', ST_TEXTDOMAIN); ?></strong><span class=""><em><?php echo $cancel_data['order_id']; ?></em></span>
</div>
<div class="mt10 clearfix">
	<strong><?php echo __('Refund Amount: ', ST_TEXTDOMAIN); ?></strong><span class=""><span class="text-danger"><em><strong><?php echo TravelHelper::format_money_raw( $cancel_data['refunded'], $cancel_data['currency'] ); ?></strong></em></span></span>
</div>
<div class="mt10 clearfix">
	<strong><?php echo __('Reason: ', ST_TEXTDOMAIN); ?></strong><span class=""><em><?php echo $cancel_data['detail']; ?></em></span>
</div>
<div class="mt10 clearfix">
	<strong><?php echo __('Refund Status: ', ST_TEXTDOMAIN); ?></strong><button class="btn btn-primary btn-xs"><?php echo $cancel_data['cancel_refund_status']; ?></button>
</div>

<?php 
	if( !empty( $cancel_data['your_paypal'] ) ):
?>
<div class="alert alert-info mt20" role="alert">
	<div class="clearfix">
		<strong><?php echo __('Paypal Email: ', ST_TEXTDOMAIN); ?></strong><span class=""><em><?php echo $cancel_data['your_paypal']['paypal_email']; ?></em></span>
	</div>
</div>
<?php elseif( !empty( $cancel_data['your_bank'] ) ): ?>
<div class="alert alert-info mt20" role="alert">	
	<div class=" clearfix">
		<strong><?php echo __('Acount Name: ', ST_TEXTDOMAIN); ?></strong><span class=""><em><?php echo $cancel_data['your_bank']['account_name']; ?></em></span>
	</div>
	<div class="mt10 clearfix">
		<strong><?php echo __('Acount Number: ', ST_TEXTDOMAIN); ?></strong><span class=""><em><?php echo $cancel_data['your_bank']['account_number']; ?></em></span>
	</div>
	<div class="mt10 clearfix">
		<strong><?php echo __('Bank Name: ', ST_TEXTDOMAIN); ?></strong><span class=""><em><?php echo $cancel_data['your_bank']['bank_name']; ?></em></span>
	</div>
	<div class="mt10 clearfix">
		<strong><?php echo __('Swift Code: ', ST_TEXTDOMAIN); ?></strong><span class=""><em><?php echo $cancel_data['your_bank']['swift_code']; ?></em></span>
	</div>
</div>	
<?php elseif( !empty( $cancel_data['your_stripe'] ) ): ?>
<div class="alert alert-info mt20" role="alert">
	<div class="clearfix">
		<strong><?php echo __('[Stripe] Transaction ID: ', ST_TEXTDOMAIN); ?></strong><span class=""><em><?php echo $cancel_data['your_stripe']['transaction_id']; ?></em></span>
	</div>
</div>
<?php elseif( !empty( $cancel_data['your_payfast'] ) ): ?>
<div class="alert alert-info mt20" role="alert">
	<div class="clearfix">
		<strong><?php echo __('[Payfast] Transaction ID: ', ST_TEXTDOMAIN); ?></strong><span class=""><em><?php echo $cancel_data['your_payfast']['transaction_id']; ?></em></span>
	</div>
</div>
<?php endif; ?>