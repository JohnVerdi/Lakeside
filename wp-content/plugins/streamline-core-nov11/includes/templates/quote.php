<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all resortpro listings by default.
 * You can override this template by creating your own "my_theme/template/page-resortpro-listings.php" file
 *
 * @package    ResortPro
 * @since      v1.0
 */

?>
<div id="customQuote">
	<div class="customQuote-top">
		<div class="well sticky" data-top-spacing="0" data-bottom-spacing="0">
			<div class="row">
				<div class="col-md-12">
					<h3><?php _e('Vacation Quote Details', 'streamline-core'); ?>: <span><?php echo $reservations_quote['data']['confirmation_id'] ?></span></h3>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<strong><?php _e('Name', 'streamline-core'); ?>:</strong> <?php echo "{$reservations_quote['data']['first_name']} {$reservations_quote['data']['last_name']}" ?>
				</div>
				<div class="col-md-8">
					<strong><?php _e('Travel Dates', 'streamline-core'); ?>:</strong> <?php echo "{$reservations_quote['data']['startdate']} - {$reservations_quote['data']['enddate']}" ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<strong><?php _e('Adults', 'streamline-core'); ?>:</strong> <?php echo "{$reservations_quote['data']['occupants']}" ?>
					<?php if($reservations_quote['data']['occupants_small'] > 0): ?>
						| <strong><?php _e('Children', 'streamline-core'); ?>:</strong> <?php echo "{$reservations_quote['data']['occupants_small']}" ?>
					<?php endif; ?>
				</div>
				<div class="col-md-8">
					<strong><?php _e('Total Nights', 'streamline-core'); ?>:</strong> <?php echo "{$reservations_quote['data']['days_number']}" ?>
				</div>
			</div>
		</div>
	</div>
<?php
	foreach ($reservations['resrvation'] as $key => $reservation) {

        $hash = ($key == 0) ? $reservation_hash : $reservation['hash'];


        $property_info = StreamlineCore_Wrapper::get_property_info( $reservation['unit_id'] );

        $res_info = StreamlineCore_Wrapper::get_reservation_price( $reservation['confirmation_id'], $hash );

        $room_rent = (float) $res_info['data']['price'];
        $total = (float) $res_info['data']['total'];
        $taxes = $total - $room_rent;

        $link_to_property = StreamlineCore_Wrapper::get_unit_permalink( $property_info['data']['seo_page_name'] );
?>

    <!-- Modal -->
    <div class="modal fade" id="modal-<?php echo $reservation['id']; ?>" tabindex="-1" role="dialog"
         aria-labelledby="modalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?php _e( 'Close', 'streamline-core' ) ?>"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php _e('Reservation Details', 'streamline-core'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="<?php echo $reservation["default_thumbnail_path"]; ?>" style="width:100%"/>

                                <div class="row">
                                    <div class="col-md-12">
                                        <h3><?php echo $reservation['location_name']; ?></h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php _e('Bedrooms', 'streamline-core'); ?>: <?php echo $reservation['bedrooms_number']; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php _e('Bathrooms', 'streamline-core'); ?>: <?php echo $reservation['bathrooms_number']; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered table-hover table-striped">
                                    <tbody>
                                    <tr>
                                        <td><?php _e('Room Rate', 'streamline-core'); ?>:</td>
                                        <td class="text-right">
                                            $<?php echo number_format( $room_rent, 2) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Taxes &amp; Fees', 'streamline-core'); ?>:</td>
                                        <td class="text-right">
                                            $<?php echo number_format($taxes, 2) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Grand Total', 'streamline-core'); ?>:</td>
                                        <td class="text-right">
                                            $<?php echo number_format( $total, 2) ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php _ex('Close', 'Closes modal window', 'streamline-core'); ?></button>
                    <a class="btn btn-success btn-book"
                       href="<?php echo add_query_arg('hash', $hash, $checkout_url2); ?>"><?php _e('Book Now', 'streamline-core'); ?></a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="info-<?php echo $reservation['id']; ?>" tabindex="-1" role="dialog"
         aria-labelledby="shareLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?php _e( 'Close', 'streamline-core' ) ?>"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo $reservation['location_name']; ?></h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <img src="<?php echo $reservation["default_image_path"]; ?>" style="width:100%"/>
                        </div>
                        <div class="col-md-6 property-info"><?php echo $reservation['bedrooms_number']; ?><?php _ex('BR', 'Abbreviation for bedrooms', 'streamline-core'); ?> / <?php echo $reservation['bathrooms_number']; ?> <?php _ex('BA', 'Abbreviation for bathrooms', 'streamline-core'); ?></div>
                        <div class="col-md-6 property-info text-right"><?php _e('Sleeps', 'streamline-core'); ?> <?php echo $reservation['max_occupants']; ?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">

                            <div class="panel-group" id="accordion-<?php echo $reservation['id']; ?>" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion-<?php echo $reservation['id']; ?>"
                                               href="#collapseOne-<?php echo $reservation['id']; ?>" aria-expanded="true" aria-controls="collapseOne">
                                                <i class="glyphicon glyphicon-plus"></i> <?php _e('Description', 'streamline-core'); ?></a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne-<?php echo $reservation['id']; ?>" class="panel-collapse collapse" role="tabpanel"
                                         aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            <?php echo $reservation['description']; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                        <h4 class="panel-title">
                                            <a class="collapsed" role="button" data-toggle="collapse"
                                               data-parent="#accordion-<?php echo $reservation['id']; ?>" href="#collapseTwo-<?php echo $reservation['id']; ?>" aria-expanded="false"
                                               aria-controls="collapseTwo">
                                                <i class="glyphicon glyphicon-plus"></i> <?php _e('Photos', 'streamline-core'); ?>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo-<?php echo $reservation['id']; ?>" class="panel-collapse collapse" role="tabpanel"
                                         aria-labelledby="headingTwo">
                                        <div class="panel-body">

                                            <?php
                                                if(!isset($reservation['gallery']['image']['id']) > 0):
													foreach($reservation['gallery']['image'] as $image):
											?>
														<div class="col-xs-3">
															<a href="#" class="thumbnail">
																<img src="<?php echo $image['thumbnail_path']; ?>" class="img-responsive" style="height:82px" />
															</a>
														</div>
                                            <?php
                                                	endforeach;
                                                endif;
                                            ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php _ex('Close', 'Close modal window', 'streamline-core'); ?></button>
                    <a class="btn btn-success btn-share" href="<?php echo add_query_arg('hash', $hash, $checkout_url2); ?>"><?php _e('Book Now', 'streamline-core'); ?></a>
                </div>
            </div>
        </div>
    </div>
    <!-- modal ends-->


    <div class="row unitListing">

            <div class="col-md-12">
                <h2>
                <?php echo $reservation['location_name']; ?>
                <?php if (!empty($reservation['max_occupants'])) { ?>
                <em> | </em> <span><?php _e('Sleeps', 'streamline-core'); ?> <?php echo $reservation['max_occupants']; ?></span><?php } ?>
                </h2>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="unitThumbnail"
                     style="background-image:url('<?php echo $reservation['default_thumbnail_path']; ?>');"><a
                        href="javascript:void(0);" id="activateLightbox_<?php echo $reservation['id']; ?>"></a></div>
            </div>
            <div class="col-lg-5 col-md-4 col-sm-6">

                <?php
                if (!empty($reservation['location_area_name'])) {
                    echo "<p class=\"unitLocation\">{$reservation['location_area_name']}</p>\n";
                }
                if (!empty($reservation['bedrooms_number']) || !empty($reservation['bathrooms_number'])) {
                    echo "<p class=\"unitBedsBaths\">";
                    if (!empty($reservation['bedrooms_number'])) {
                        echo $reservation['bedrooms_number'] . " BR";
                    }
                    if (!empty($reservation['bedrooms_number']) && !empty($reservation['bathrooms_number'])) {
                        echo " / ";
                    }
                    if (!empty($reservation['bathrooms_number'])) {
                        echo $reservation['bathrooms_number'] . " BA";
                    }
                    echo "</p>\n";
                }

								if(!empty($reservation['short_description'])){
                	echo "<p>{$reservation['short_description']}</p>";
								}
                if ( count( $reservation['unit_amenities']['amenity'] ) > 0) {
                    $arr_primary_amenities = array(
                        __( 'Private Jacuzzi', 'streamline-core'),
                        __( 'High Speed Wireless Internet', 'streamline-core' ),
                    );

                    $arr_secondary_amenities = array(
                        __( 'Private Jacuzzi', 'streamline-core'),
                        __( 'High Speed Wireless Internet', 'streamline-core' ),
                        __( 'Steps to Ski Lift', 'streamline-core' ),
                        __( 'Steps to Ski Slopes', 'streamline-core' ),
                        __( 'Free Shuttle Route', 'streamline-core' ),
                        __( 'Private Deck', 'streamline-core' ),
                        __( 'Grocery Services', 'streamline-core' ),
                    );
                    ?>

                    <ul>
                        <?php
                        if(isset($reservation['unit_amenities']['amenity']['amenity_name'])){
                            echo '<li>' . $reservation['unit_amenities']['amenity']['amenity_name'] . '</li>';
                        }else{
                            foreach ( $reservation['unit_amenities']['amenity'] as $amenity) {
                                if (isset($amenity['amenity_name']) && in_array($amenity['amenity_name'], $arr_secondary_amenities)) {
                                    echo '<li>' . $amenity['amenity_name'] . '</li>';
                                }
                            }
                        }
                        ?>
                    </ul>
                <?php
                }
                ?>

                <p><a href="#" class="btn-more-info" data-id="<?php echo $reservation['unit_id']; ?>"><?php _e('More Info', 'streamline-core'); ?></a></p>
                <p><a href="javascript:void(0);" class="share-with-friends" data-slug="<?php echo $property_info['data']['seo_page_name'] ?>" data-hash="<?php echo $reservation['hash']; ?>"><?php _e('Share this listing', 'streamline-core'); ?> </a></p>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <form action="/custom_vacation_quote.html" method="post" class="prettyBookForm"
                      onsubmit="javascript: return checkUnitAvailabilityComplex(<?php echo $reservation['id']; ?>);">
                    <input type="hidden" name="hash" value="<?php echo $reservation_hash; ?>"/>
                    <input type="hidden" name="qhash" value="<?php echo $reservation_qhash; ?>"/>
                    <input type="hidden" name="quote_hash" value="<?php echo $reservation['hash']; ?>"/>
                    <input type="hidden" name="unit_id_<?php echo $reservation['id']; ?>" id="unit_id_<?php echo $reservation['id']; ?>" value="<?php echo $reservation['unit_id']; ?>"/>

                    <p class="unitSafe"><?php _e('Placing your order is safe &amp; secure', 'streamline-core'); ?></p>

                    <div class="unitPriceBlockTop">
                        <p class="unitPrice">$<?php echo number_format(str_replace("$", "", ($reservation['price_common'])),0); ?></p>

                        <p><em><?php _e('Includes all taxes &amp; fees', 'streamline-core'); ?></em></p>

                        <div class="caret"></div>
                    </div>
                    <div class="unitPriceBlockBottom">
                        <p>
                            <a href="<?php echo add_query_arg('hash', $hash, $checkout_url2); ?>"
                               class="btn btn-success btn-lg btn-book2"><?php _e('Book Now', 'streamline-core'); ?></a>
                        </p>
                        <p><a href="#" data-toggle="modal" data-target="#modal-<?php echo $reservation['id']; ?>"><?php _e('Pricing breakdown', 'streamline-core'); ?></a></p>

                        <ul class="ccards">
                            <?php if ( (int)$options['property_card_type_visa'] === 1 ) : ?>
                                <li class="visa"><?php _e('Visa', 'streamline-core'); ?></li>
                            <?php endif;?>
                            <?php if ( (int)$options['property_card_type_master_card'] === 1 ) : ?>
                                <li class="mastercard"><?php _e('MasterCard', 'streamline-core'); ?></li>
                            <?php endif;?>
                            <?php if ( (int)$options['property_card_type_amex'] === 1 ) : ?>
                                <li class="amex"><?php _e('American Express', 'streamline-core'); ?></li>
                            <?php endif;?>
                            <?php if ( (int)$options['property_card_type_discover'] === 1 ) : ?>
                                <li class="discover"><?php _e('Discover', 'streamline-core'); ?></li>
                            <?php endif;?>
                        </ul>

                    </div>
                </form>
                <form id="more_info_<?php echo $reservation['unit_id']; ?>" action="<?php echo esc_url($link_to_property); ?>" method="get" style="display: none">
                    <input type="hidden" value="<?php echo $reservations_quote['data']['occupants_small']; ?>" name="ch">
                    <input type="hidden" value="<?php echo $reservations_quote['data']['occupants']; ?>" name="oc">
                    <input type="hidden" value="<?php echo $reservations_quote['data']['startdate']; ?>" name="sd">
                    <input type="hidden" value="<?php echo $reservations_quote['data']['enddate']; ?>" name="ed">
                    <input type="hidden" value="<?php echo $reservation['hash']; ?>" name="hash">
                    <input type="hidden" value="<?php echo $reservations_quote['data']['pets']; ?>" name="pets">
                </form>
            </div>
                </div>
            </div>
    </div>

<?php
    }
?>
</div><!-- /#custom-quote -->

<div class="modal fade" id="share-with-friends-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="<?php _e( 'Close', 'streamline-core' ) ?>"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php _e('Share with Friends', 'streamline-core'); ?></h4>
      </div>
      <div class="modal-body">
        <div id="share-with-friends-messages"></div>
        <div class="container-fluid">
          <form class="form-horizontal">
            <div class="form-group">
              <label for="fnames" class="col-sm-3 control-label"><?php _e('Friend(s) Name(s)', 'streamline-core'); ?></label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="fnames" id="share-with-friends-fnames" placeholder="<?php _e('Separated by comma', 'streamline-core'); ?>">
                <input type="hidden" name="nonce" id="share-with-friends-nonce" value="<?php echo wp_create_nonce( 'share-with-friends' ); ?>">
              </div>
            </div>
            <div class="form-group">
              <label for="email" class="col-sm-3 control-label"><?php _e('Friend(s) Email(s)', 'streamline-core'); ?></label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="emails" id="share-with-friends-femails" placeholder="<?php _e('Separated by comma', 'streamline-core'); ?>">
              </div>
            </div>
            <div class="form-group">
              <label for="name" class="col-sm-3 control-label"><?php _e('Your Name', 'streamline-core'); ?></label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="name" id="share-with-friends-name" placeholder="">
              </div>
            </div>
            <div class="form-group">
              <label for="email" class="col-sm-3 control-label"><?php _e('Your Email', 'streamline-core'); ?></label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="email" id="share-with-friends-email" placeholder="">
              </div>
            </div>
            <div class="form-group">
              <label for="msg" class="col-sm-3 control-label"><?php _e('Message', 'streamline-core'); ?></label>
              <div class="col-sm-9">
                <textarea name="msg" id="share-with-friends-msg" class="form-control"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hash" id="share-with-friends-hash" value="" />
        <input type="hidden" name="slug" id="share-with-friends-slug" value="" />
        <button class="btn btn-default" data-dismiss="modal"><?php _ex('Close', 'Closes modal window', 'streamline-core'); ?></button>
        <button class="btn btn-success" id="btn-share-with-friends-submit"><?php _e( 'Send Now', 'streamline-core' ) ?></button>
      </div>
    </div>
  </div>
</div>
