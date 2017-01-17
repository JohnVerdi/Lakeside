<?php
/**
 * Created by PhpStorm.
 * User: Dungdt
 * Date: 12/16/2015
 * Time: 6:03 PM
 */
?>

<div class="pm-info">
	<p><?php _e('Take payments store via Stripe (redirect method).',ST_TEXTDOMAIN)?></p>
</div>
<div class="pm-info">
	<div class="row">
		<div class="col-sm-6">
			<div class="col-card-info">
				<div class="form-group">
					<label for="st_stripe_card_number"><?php _e('Card number (*)',ST_TEXTDOMAIN) ?></label>
					<div class="controls">
						<input type="text" class="form-control" align="" name="st_stripe_card_number" id="st_stripe_card_number" placeholder="<?php _e('Your card number',ST_TEXTDOMAIN) ?>">
					</div>
				</div>
				<div class="card-code-expiry">
					<div class="form-group expiry-date">
						<label for="st_stripe_card_expiry_month"><?php _e('Expiry date (*)',ST_TEXTDOMAIN) ?></label>
						<div class="controls clearfix">
							<div class="form-control-wrap">
								<select name="st_stripe_card_expiry_month" id="st_stripe_card_expiry_month" class="form-control app required">
									<optgruop label="<?php _e('Month',ST_TEXTDOMAIN)?>">
										<?php
										for($i=1;$i<12;$i++){
											printf('<option value="%s">%s</option>',$i,$i);
										} ?>
									</optgruop>
								</select>
							</div>
							<div class="form-control-wrap">
								<select name="st_stripe_card_expiry_year" id="st_stripe_card_expiry_year" class="form-control app required">
									<optgruop label="<?php _e('Year',ST_TEXTDOMAIN)?>">
										<?php
										$y=date('Y');
										for($i=date('Y');$i<$y+49;$i++){
											printf('<option value="%s">%s</option>',$i,$i);
										} ?>
									</optgruop>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group card-code">
						<label for="st_stripe_card_code"><?php _e('Card code (*)',ST_TEXTDOMAIN) ?></label>
						<div class="controls">
							<input type="text" class="form-control" align="" name="st_stripe_card_code" id="st_stripe_card_code required">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>