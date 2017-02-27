<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all resortpro listings by default.
 * You can override this template by creating your own "my_theme/template/page-resortpro-listing-detail.php" file
 *
 * @package    ResortPro
 * @since      v2.0
 */
?>
<!--<pre>-->
<!--    --><?php //print_r($property['roomsdetails_html_code']);?>
<!--</pre>-->
<style>
    .thumb-img-pages {
        background: 50% 75% no-repeat;
        background-size: cover ;
        height: 500px;
        width: 100%;
    }
    .booking-item-details {
        border: 1px solid #aaa;
        -webkit-border-radius:6px;
        -moz-border-radius:6px;
        border-radius:6px;
        padding: 15px;
    }
</style>

<div class="container" >
    <ol class="breadcrumb">
        <li><a href="/"><?php _e( 'Home', 'streamline-core' ) ?></a></li>
        <li><a href="/search-results"><?php _e( 'All Rentals', 'streamline-core' ) ?></a></li>
        <li><a href="/search-results?area_id=<?php echo $property['location_area_id'] ?>"><?php echo $property['location_area_name'] ?></a></li>
        <li class="active">
            <?php
            if(empty($property['name']) || $property['name'] == 'Home' ){
                echo $property['location_name'];
            }else{
                echo $property['name'];
            }
            ?>
        </li>
    </ol>
</div>
<div id="single-room" class="booking-item-details" ng-controller="PropertyController as hyi">
    <main id="main" class="site-main" role="main" ng-init="propertyId=<?php echo $property['id']; ?>;initializeData();getRatesDetails(<?php echo $property['id']; ?>);getRoomDetails(<?php echo $property['id']; ?>);">
    <div class="thumb">
        <div class="thumb-img-pages" style="background-image: url('<?php echo $property['gallery']['image'][0]['image_path'];?>')" ></div>
    </div>
    <div class="container">
        <div class="vc_row wpb_row st bg-holder custom-row-single-room">
            <div class="container ">
                <div class="row">
                    <div class="custom-row-single-room wpb_column column_container col-md-8">
                        <div class="vc_column-inner wpb_wrapper">

                            <div class="booking-item-details no-border-top">
                                <div class="row">
                                    <div class="col-xs-12 col-md-12">
                                        <h2 class="title"><?php echo $property['name'];?></h2>
                                        <div class="booking-item-rating">
                                            <div class="pull-left" style="margin: 20px 0;">
                                                <strong><a href="#" title="locateion"><?php echo $property['city'];?></a></strong>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-6 col-sm-3">
                                                <div class="facility-item" rel="tooltip" data-placement="top" title="" data-original-title="Sleeps">
                                                    <i class="fa fa-male"></i>
                                                    <h5 class="booking-item-feature-sign">Sleeps <?php echo $property['max_adults'];?></h5>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-3">
                                                <div class="facility-item" rel="tooltip" data-placement="top" title="" data-original-title="Beds">
                                                    <i class="im im-bed"></i>
                                                    <h5 class="booking-item-feature-sign">Beds <?php echo $property['max_occupants'];?></h5>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-3">
                                                <div class="facility-item" rel="tooltip" data-placement="top" title="" data-original-title="Baths">
                                                    <i class="fa fa-tint" aria-hidden="true"></i>
                                                    <h5 class="booking-item-feature-sign"><?php echo $property['square_foots'];?> m</h5>
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-sm-3">
                                                <div class="facility-item" rel="tooltip" data-placement="top" title="" data-original-title="No pets">
                                                    <i class="fa fa-paw" aria-hidden="true"></i>
                                                    <h5 class="booking-item-feature-sign">No-pets</h5>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="vc_empty_space" style="height: 30px"><span class="vc_empty_space_inner"></span></div>
                            <div class="wpb_tabs wpb_content_element" data-interval="0">
                                <div class="wpb_wrapper wpb_tour_tabs_wrapper ui-tabs vc_clearfix ui-widget ui-widget-content ui-corner-all">

                                    <ul class="nav nav-tabs ui-tabs-nav vc_clearfix ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
                                        <li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="tab-ab33b9ce-599d-6" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="#tab-ab33b9ce-599d-6" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1"><i class="fa fa-camera"></i> Photos </a></li>
                                        <li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tab-2adfa48d-4e61-4" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false"><a href="#tab-2adfa48d-4e61-4" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2"><i class="fa fa-asterisk"></i> Details</a></li>
                                        <li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tab-1439375290816-2-0" aria-labelledby="ui-id-3" aria-selected="false" aria-expanded="false"><a href="#tab-1439375290816-2-0" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-3"><i class="fa fa-comments"></i> Amenities</a></li>
                                        <li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tab-1439375290816-2-1" aria-labelledby="ui-id-4" aria-selected="false" aria-expanded="false"><a href="#tab-1439375290816-2-1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-3"><i class="fa fa-comments"></i> Rates</a></li>
                                    </ul>


                                    <div id="tab-ab33b9ce-599d-6" class="ui-tabs-panel wpb_ui-tabs-hide vc_clearfix ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-1" role="tabpanel" aria-hidden="false" style="display: block;">
                                        <!-- Slider -->
                                        <div class="fotorama"
                                             data-allowfullscreen="true"
                                             data-nav="thumbs">
                                            <?php foreach($property['gallery']['image'] as $image):?>
                                                <img src="<?php echo $image['image_path'];?>">
                                            <?php endforeach;?>
                                        </div>
                                        <!-- Slider End -->
                                    </div>

                                    <div id="tab-2adfa48d-4e61-4" class="ui-tabs-panel wpb_ui-tabs-hide vc_clearfix ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-2" role="tabpanel" aria-hidden="true" style="display: none;">
                                        <div class="room-facility about_listing">
                                            <div role="tabpanel" class="tab-pane fade in active" id="property-details-pane">
                                                <div id="property-description">
                                                    <h3 id="property-details-pane"><?php _e( 'Description', 'streamline-core' ) ?></h3>

                                                    <div class="property_description">
                                                        <?php
                                                        if (is_string($property['description'])) {
                                                            echo $property['description'];
                                                        }
                                                        echo $property['roomsdetails_html_code'];
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
<!--                                    Amenities-->
                                    <div id="tab-1439375290816-2-0" class="ui-tabs-panel wpb_ui-tabs-hide vc_clearfix ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-3" role="tabpanel" aria-hidden="true" style="display: none;">
                                        <div role="tabpanel" class="tab-pane" id="property-amenities-pane">
                                            <h3><?php _e( 'Amenities', 'streamline-core' ) ?></h3>
                                            <ul class="list-group row amenities">
                                                <?php
                                                if($property['unit_amenities']['amenity']['amenity_name']){
                                                    ?>
                                                    <li class="list-group-item col-xs-4">
                                                        <?php echo $property['unit_amenities']['amenity']['amenity_name']; ?>
                                                    </li>
                                                    <?php
                                                }else{
                                                    foreach ($property['unit_amenities']['amenity'] as $amenity) {
                                                        ?>
                                                        <li class="list-group-item col-xs-4">
                                                            <?php echo $amenity['amenity_name']; ?>
                                                        </li>
                                                    <?php }
                                                }
                                                ?>
                                            </ul>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

<!--                                    Rates-->
                                    <div id="tab-1439375290816-2-1" class="ui-tabs-panel wpb_ui-tabs-hide vc_clearfix ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-4" role="tabpanel" aria-hidden="true" style="display: none;">
                                        <div id="property-rates-pane" role="tabpanel" class="tab-pane">
                                            <h3><?php _e( 'Rates', 'streamline-core' ) ?></h3>
                                            <div id="property-rates">
                                                <div id="rates-details">

                                                    <table class="table table-striped table-bordered table-condensed table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th><?php _e( 'Season', 'streamline-core' ) ?></th>
                                                            <th><?php _e( 'Period', 'streamline-core' ) ?></th>
                                                            <th><?php _e( 'Min. Stay', 'streamline-core' ) ?></th>
                                                            <th><?php _e( 'Nightly Rate', 'streamline-core' ) ?></th>
                                                            <th><?php _e( 'Weekly Rate', 'streamline-core' ) ?></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        <tr class="row-rate" ng-repeat="rate in rates_details">
                                                            <td>{[rate.season_name]}</td>
                                                            <td>{[rate.period_begin]} - {[rate.period_end]}</td>
                                                            <td>{[rate.narrow_defined_days]}</td>
                                                            <td class="text-center"><span ng-if="rate.daily_first_interval_price" ng-bind="calculateMarkup(rate.daily_first_interval_price) | currency"></span></td>
                                                            <td class="text-center"><span ng-if="rate.weekly_price" ng-bind="calculateMarkup(rate.weekly_price) | currency"></span></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="wpb_text_column wpb_content_element ">
                                <div class="wpb_wrapper">
                                    <h3>Availability</h3>
                                </div>
                            </div>

                            <!-- CALENDAR-->
                            <div class="row calendar-wrapper mb20" data-post-id="<?php echo get_the_ID(); ?>">
                                <div class="col-xs-12 calendar-wrapper-inner">
                                    <div class="overlay-form"><i class="fa fa-refresh text-color"></i></div>
                                    <div class="calendar-content"></div>
                                </div>
                                <div class="col-xs-12 mt10">
                                    <div class="calendar-bottom">
                                        <div class="item ">
                                            <span class="color available"></span>
                                            <span> <?php echo __('Available', ST_TEXTDOMAIN) ?></span>
                                        </div>
                                        <div class="item redopacity">
                                            <span class="color"></span>
                                            <span>  <?php echo __('Not Available', ST_TEXTDOMAIN) ?></span>
                                        </div>
                                        <div class="item still ">
                                            <span class="color"></span>
                                            <span>  <?php echo __('Still Available', ST_TEXTDOMAIN) ?></span>
                                        </div>
                                        <div class="item ">
                                            <span class="color bgr-main"></span>
                                            <span> <?php echo __('Selected', ST_TEXTDOMAIN) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- CALENDAR end-->
                        </div>
                    </div>

                    <div class="wpb_column column_container col-md-4">
                        <div class="vc_column-inner wpb_wrapper">
                            <?php  include_once 'wp-content/themes/traveler-childtheme/st_templates/vc-elements/st-hotel-room/st_hotel_room_sidebar.php';?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </main>
</div>
<span class="hidden st_single_hotel_room" data-fancy_arr="1"></span>

