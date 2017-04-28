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

<div id="single-room" class="booking-item-details" ng-controller="PropertyController as property">
    <main id="main" class="site-main" role="main" ng-init="
            propertyId=<?php echo $property['id']; ?>;
            initializeData();
            getRatesDetails(<?php echo $property['id']; ?>);
            getRoomDetails(<?php echo $property['id']; ?>);">
    <div class="thumb">
        <div class="thumb-img-pages" style="background-image: url('<?php echo $property['gallery']['image'][0]['image_path'];?>')" ></div>
    </div>
    <div class="container">
        <div class="vc_row wpb_row st bg-holder custom-row-single-room">
            <div class="container ">
                <div class="row">
                    <div class="custom-row-single-room wpb_column column_container col-md-8">
                        <div class="vc_column-inner wpb_wrapper">

                            <?php include_once 'includes/top_widget.php';?>

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
                                    <?php include_once 'includes/amenities.php';?> <!--Amenities-->
                                    <?php include_once 'includes/rates.php';?> <!--Rates-->
                                </div>
                            </div>

                            <div class="wpb_text_column wpb_content_element">
                                <div class="wpb_wrapper">
                                    <h3>Availability</h3>
                                </div>
                            </div>

                            <?php include_once 'includes/calendar.php';?> <!-- CALENDAR-->
                        </div>
                    </div>
                    <?php include_once 'includes/book_new.php';?> <!--Right widget book_now-->
                </div>
            </div>
        </div>
    </div>
    </main>
</div>
<span class="hidden st_single_hotel_room" data-fancy_arr="1"></span>

