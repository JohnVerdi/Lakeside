<div class="wrap">
  <h2><?php _e('StreamlineCore Settings', 'streamline-core') ?></h2>
  <?php if ( isset( $transients_cleared ) ) : ?>
    <?php if ( $transients_cleared ) : ?>
      <div class="notice notice-success is-dismissible"><p><?php _e( 'Transients successfully cleared.', 'streamline-core' ) ?></p></div>
    <?php else : ?>
      <div class="notice notice-error is-dismissible"><p><?php _e( 'There was an error clearing transients.', 'streamline-core' ) ?></p></div>
    <?php endif; ?>
  <?php endif; ?>
  <form action="options.php" method="POST">
    <?php settings_fields( StreamlineCore_Settings::get_option_group() ) ?>
    <input type="submit" name="submit" id="submit" class="button button-primary pull-right" value="<?php _e( 'Save Settings', 'streamline-core' ) ?>" />

    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab"><?php _e( 'General', 'streamline-core' ) ?></a></li>
      <li role="presentation"><a href="#search" aria-controls="search" role="tab" data-toggle="tab"><?php _e( 'Search', 'streamline-core' ) ?></a></li>
      <li role="presentation"><a href="#unit-details" aria-controls="unit-details" role="tab" data-toggle="tab"><?php _e( 'Unit Details', 'streamline-core' ) ?></a></li>
      <li role="presentation"><a href="#checkout" aria-controls="checkout" role="tab" data-toggle="tab"><?php _e( 'Checkout', 'streamline-core' ) ?></a></li>
      <li role="presentation"><a href="#booking" aria-controls="booking" role="tab" data-toggle="tab"><?php _e( 'Booking', 'streamline-core' ) ?></a></li>
      <li role="presentation"><a href="#shortcodes" aria-controls="shortcodes" role="tab" data-toggle="tab"><?php _e( 'Shortcodes', 'streamline-core' ) ?></a></li>
    </ul>

    <div class="tab-content">
      <div role="tabpanel" class="tab-pane fade in active" id="general" style="padding: 20px 0px">
        <?php require_once( __DIR__ . '/settings-page-general.php' ) ?>
      </div>
      <div role="tabpanel" class="tab-pane fade" id="search" style="padding: 20px 0px">
        <?php require_once( __DIR__ . '/settings-page-search.php' ) ?>
      </div>
      <div role="tabpanel" class="tab-pane fade" id="unit-details" style="padding: 20px 0px">
        <?php require_once( __DIR__ . '/settings-page-unit-details.php' ) ?>
      </div>
      <div role="tabpanel" class="tab-pane fade" id="checkout" style="padding: 20px 0px">
        <?php require_once( __DIR__ . '/settings-page-checkout.php' ) ?>
      </div>
      <div role="tabpanel" class="tab-pane fade" id="booking" style="padding: 20px 0px">
        <?php require_once( __DIR__ . '/settings-page-booking.php' ) ?>
      </div>
      <div role="tabpanel" class="tab-pane fade" id="shortcodes" style="padding: 20px 0px">
        <?php require_once( __DIR__ . '/settings-page-shortcodes.php' ) ?>
      </div>
    </div>
  </form>

  <form action="<?php echo admin_url( 'options-general.php?page=' . StreamlineCore_Settings::get_settings_page() ) ?>" method="POST">
    <input type="hidden" name="transients" value="clear" />
    <input type="submit" name="submit" class="button button-secondary" value="<?php _e( 'Clear Transients', 'streamline-core' ) ?>" />
  </form>
</div>
