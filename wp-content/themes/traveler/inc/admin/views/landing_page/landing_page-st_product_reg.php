<?php
$_username=st()->get_option('envato_username',false);
$_api_key=st()->get_option('envato_apikey',false);
$_purchase_code=st()->get_option('envato_purchasecode',false);
if($_username and $_api_key and $_purchase_code):
    ?>
    <div class="updated" style="padding: 15px 10px 5px 10px !important;">
        <?php _e('Thank you for registration our theme',ST_TEXTDOMAIN)?>
    </div>
<?php endif;?>
<div class="traveler-registration-steps">
	<div class="feature-section col three-col">
		<div>
			<h4><?php echo __("Step 1 - Signup for Support",ST_TEXTDOMAIN) ; ?></h4>
			<p><a href="http://helpdesk.shinetheme.com/" target="_blank">Click here</a> to signup at our support center.&nbsp;View a tutorial&nbsp;<a href="http://shinetheme.com/demosd/documentation/2538-2/" target="_blank"><?php echo __("here ", ST_TEXTDOMAIN ) ; ?>.</a>&nbsp;<?php echo __("This gives you access to our documentation, knowledgebase, video tutorials and ticket system.", ST_TEXTDOMAIN ) ; ?></p>
		</div>
		<div>
			<h4><?php echo __("Step 2 - Generate an API Key",ST_TEXTDOMAIN) ; ?></h4>
			<p><?php echo __('Once you registered at our support center, you need to generate a product API key under the "Licenses" section of your Themeforest account. View a tutorial ',ST_TEXTDOMAIN) ; ?>&nbsp;<a href="http://themeforest.net/forums/thread/where-can-i-find-my-secret-api-key-/137373?page=1&message_id=1300268#1300268" target="_blank"><?php echo __("here ", ST_TEXTDOMAIN ) ; ?></a>.</p>
		</div>
		<div class="last-feature">
			<h4><?php echo __("Step 3 - Purchase Validation", ST_TEXTDOMAIN ) ; ?></h4>
			<p><?php echo __("Enter your ThemeForest username, purchase code and generated API key into the fields below. This will give you access to automatic theme updates. ", ST_TEXTDOMAIN ) ; ?></p>
		</div>
	</div>
</div>
<div class="feature-section">
	<div class="traveler-important-notice registration-form-container">
		<p class="about-description"><?php echo __("After Steps 1-2 are complete, enter your credentials below to complete product registration.", ST_TEXTDOMAIN ) ; ?></p>

		<form id="traveler_product_registration" method="post" action="<?php echo admin_url('admin.php?page=st_product_reg') ?>">
			<div class="traveler-registration-form">
				<input type="hidden" name="st_action" value="save_product_registration">
				<?php wp_nonce_field( 'traveler_update_registration','traveler_update_registration_nonce' ); ?>
				<input type="text" name="tf_username" id="tf_username" placeholder="<?php _e('Themeforest Username',ST_TEXTDOMAIN)?>" value="<?php echo ($_username) ?>">
				<input type="text" name="tf_api" id="tf_api" placeholder="<?php _e('Enter API Key',ST_TEXTDOMAIN)?>" value="<?php echo ($_api_key) ?>">
				<input type="text" name="tf_purchase_code" id="tf_purchase_code" placeholder="<?php _e('Enter Themeforest Purchase Code',ST_TEXTDOMAIN)?>" value="<?php echo ($_purchase_code) ?>">

			</div>
			<button class="button button-large button-primary traveler-large-button traveler-register" type="submit"><?php echo __("Submit", ST_TEXTDOMAIN ) ; ?></button>
			<span class="traveler-loader"><i class="dashicons dashicons-update loader-icon"></i><span></span></span>
		</form>
	</div>
</div>