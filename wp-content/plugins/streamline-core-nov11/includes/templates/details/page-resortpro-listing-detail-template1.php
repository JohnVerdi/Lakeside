<?php
/**
* The template for displaying pages
*
* This is the template that displays all resortpro listings by default.
* You can override this template by creating your own "my_theme/template/page-resortpro-listing-detail.php" file
*
* @package    ResortPro
* @since      v1.0
*/
?>
<style>
.entry-header{
    display: none;
}
.ms-showcase2-template {
padding-bottom: 155px;
}
.ms-showcase2-template .ms-view {
background: transparent;
}
.ms-showcase2-template .ms-bar {
background: black !important;
}
.ms-showcase2-template .ms-tabs-vertical-template {
margin: 0 auto;
}
.ms-showcase2-template.ms-dir-v {
padding-right: 140px;
padding-bottom: 0 !important;
}
.ms-showcase2-template .ms-thumb-list.ms-dir-h {
bottom: -145px;
width: 100%;
height: 137px;
}
.ms-showcase2-template .ms-thumb-list.ms-dir-v {
height: 100%;
width: 130px;
right: -140px;
top: 0;
}
.ms-showcase2-template .ms-thumb-list .ms-thumb-frame {
width: 127px;
height: 137px;
opacity: 0.5;
-webkit-transition: opacity 300ms;
-moz-transition: opacity 300ms;
-ms-transition: opacity 300ms;
-o-transition: opacity 300ms;
transition: opacity 300ms;
}
.ms-showcase2-template .ms-thumb-frame {
cursor: pointer;
}
.ms-showcase2-template .ms-thumb-list.ms-dir-h .ms-thumb-frame {
margin-right: 10px;
}
.ms-showcase2-template .ms-thumb-list.ms-dir-v .ms-thumb-frame {
margin-bottom: 10px;
}
.ms-showcase2-template .ms-thumb-list .ms-thumb-frame-selected {
opacity: 1;
}
.nav-pills {
margin-top: 0;
margin-bottom: 12px;
}
.nav-pills li {
border: solid 1px #ccc;
}
@media (max-width: 400px) {
    .ms-showcase2-template .ms-thumb-list.ms-dir-v {
    width: 64px;
    right: -70px;
    }
    .ms-showcase2-template .ms-thumb-list .ms-thumb-frame {
    width: 64px;
    height: 69px;
    }
    .ms-showcase2-template {
    padding-bottom: 75px;
    }
    .ms-showcase2-template.ms-dir-v {
    padding-right: 70px;
    }
    }
    @media (max-width: 200px) {
    .ms-showcase2-template .ms-thumb-list {
    display: none;
    }
    .ms-showcase2-template {
    padding-bottom: 0px;
    }
    .ms-showcase2-template.ms-dir-v {
    padding-right: 0px;
    }
}
.ms-caption{
    position: absolute !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    top: auto !important;
    background: rgba(0,0,0,0.7);
    padding: 10px;
    color: #fff;

}

.ms-lightbox-template .ms-lightbox{
    margin-top: -84px;
}
</style>
<div id="primary" class="container content-area" ng-controller="PropertyController as pCtrl">
    <main id="main" class="site-main" role="main" ng-init="initializeData(); propertyId=<?php echo $property['id']; ?>">
        <div class="row layout-1">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li><a href="/"><?php _e( 'Home', 'streamline-core' ) ?></a></li>
                    <li><a href="/search-results"><?php _e( 'All Rentals', 'streamline-core' ) ?></a></li>
                    <li>
                        <a href="/search-results?area_id=<?php echo $property['location_area_id'] ?>"><?php echo $property['location_area_name'] ?></a>
                    </li>
                    <li class="active">
                        <?php
                        if (empty($property['name']) || $property['name'] == 'Home') {
                        echo $property['location_name'];
                        } else {
                        echo $property['name'];
                        }
                        ?>
                    </li>
                </ol>
            </div>
            <h1 class="col-lg-12" style="margin-top:0">
            <?php
            if (empty($property['name']) || $property['name'] == 'Home') {
            echo $property['location_name'];
            } else {
            echo $property['name'];
            }
            ?>
            </h1>
            <input type="hidden" value="<?php echo $property['id'] ?>" id="unit_id">
            <div class="col-lg-8" style="margin-bottom:24px">
                <ul class="nav nav-pills nav-justified">
                    <li role="presentation" class=""><a href="#description-title"><?php _e( 'Description', 'streamline-core' ) ?></a></li>

                    <?php if (isset($options['unit_tab_amenities']) && $options['unit_tab_amenities'] == 1 &&  (count($property['unit_amenities']['amenity']) > 0)): ?>
                    <li role="presentation" class=""><a href="#amenities-title"><?php _e( 'Amenities', 'streamline-core' ) ?></a></li>
                    <?php endif; ?>

                    <?php if(isset($options['unit_tab_availability']) && $options['unit_tab_availability'] == 1): ?>
                    <li role="presentation" class=""><a href="#availability-title"><?php _e( 'Availability', 'streamline-core' ) ?></a></li>
                    <?php endif; ?>

                    <?php if (isset($options['unit_tab_location']) && $options['unit_tab_location'] == 1 && (!empty($property['location_latitude']) && !empty($property['location_longitude']))): ?>
                    <li role="presentation" class=""><a href="#location-title"><?php _e( 'Location', 'streamline-core' ) ?></a></li>
                    <?php endif; ?>

                    <?php if(isset($options['unit_tab_reviews']) && $options['unit_tab_reviews'] == 1): ?>
                    <li role="presentation" class="" ng-show="reviews.length > 0"><a href="#reviews-title"><?php _e( 'Reviews', 'streamline-core' ) ?></a></li>
                    <?php endif; ?>

                    <?php if(isset($options['unit_tab_rates']) && $options['unit_tab_rates'] == 1): ?>
                    <li role="presentation" class=""><a href="#rates-title"><?php _e( 'Rates', 'streamline-core' ) ?></a></li>
                    <?php endif; ?>

                    <?php if(isset($options['unit_tab_room_details']) && $options['unit_tab_room_details'] == 1): ?>
                    <li role="presentation" class=""><a href="#rooms-title"><?php _e( 'Rooms Details', 'streamline-core' ) ?></a></li>
                    <?php endif; ?>

                    <?php if(isset($options['unit_tab_floorplan']) && $options['unit_tab_floorplan'] == 1 && (!empty($property['floor_plan_url']))): ?>
                    <li role="presentation" class=""><a href="#floorplan-title"><?php _e( 'Floor Plan', 'streamline-core' ) ?></a></li>
                    <?php endif; ?>

                    <?php if(isset($options['unit_tab_virtualtour']) && $options['unit_tab_virtualtour'] == 1 && (!empty($property['virtual_tour_url']))): ?>
                    <li role="presentation" class=""><a href="#virtualtour-title"><?php _e( 'Virtual Tour', 'streamline-core' ) ?></a></li>
                    <?php endif; ?>

                </ul>
                <div class="ms-showcase2-template">
                    <div class="master-slider ms-skin-default ms-lightbox-template" id="masterslider2" data-slider-height="<?php echo $slider_height; ?>">
                        <?php
                        if(count($property_gallery) > 0){
                            foreach ($property_gallery as $image):
                        ?>
                        <div class="ms-slide">
                            <img src="<?php ResortPro::assets_url('masterslider/blank.gif'); ?>"
                            data-src="<?php echo $image['image_path'] ?>"
                            alt="<?php echo $image['id'] ?>"/>

                            <?php if($show_captions && is_string($image['description'])): ?>
                            <div class="ms-layer ms-caption" style="top:10px; left:30px;">
                                <?php echo $image['description'] ?>
                            </div>
                            <?php endif; ?>

                            <img class="ms-thumb" src="<?php echo $image['thumbnail_path'] ?>"
                            alt="<?php echo $image['description'] ?>"/>

                            <a href="<?php echo $image['image_path'] ?>" class="ms-lightbox"
                                data-title="{[image.description]}" data-toggle="lightbox"
                                data-gallery="property-images"> <?php _e( 'lightbox', 'streamline-core' ) ?> </a>
                        </div>
                        <?php
                        endforeach;
                        }else{
                        ?>
                            <div class="ms-slide">
                                <img src="<?php ResortPro()->assets_url('masterslider/blank.gif'); ?>"
                                     data-src="<?php echo $property['default_image_path']; ?>"
                                     alt="<?php echo $property['name']; ?>" />

                                <img class="ms-thumb" src="<?php echo $property['default_thumbnail_path'] ?>"
                                alt="<?php echo wp_strip_all_tags( $property['short_description'], true ); ?>"/>

                                <a href="<?php echo $property['default_image_path'] ?>" class="ms-lightbox"
                                    data-title="<?php echo $property['location_name'] ?>" data-toggle="lightbox"
                                data-gallery="property-images"> <?php _e( 'lightbox', 'streamline-core' ) ?> </a>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <a name="description-title" class="anchor"><?php _e( 'Description', 'streamline-core' ) ?></a>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="section-title" id=""><?php _e( 'Description', 'streamline-core' ) ?></h3>
                        <div class="property_description">
                            <?php
                            if (is_string($property['description'])) {
                            echo $property['description'];
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php if (isset($options['unit_tab_amenities']) && $options['unit_tab_amenities'] == 1 &&  (count($property['unit_amenities']['amenity']) > 0)): ?>
                <a name="amenities-title" class="anchor"><?php _e( 'Amenities', 'streamline-core' ) ?></a>
                <div class="row" style="margin-top:24px;">
                    <div class="col-md-12">
                        <h3 class="section-title"><?php _e( 'Amenities', 'streamline-core' ) ?></h3>
                        <div id="property-amenities">
                            <div class="row">
                                <?php
                                if($property['unit_amenities']['amenity']['amenity_name']){
                                    ?>
                                    <div class="col-md-12">
                                        <?php echo $property['unit_amenities']['amenity']['amenity_name']; ?>
                                    </div>
                                    <?php
                                }else{
                                    foreach ($property['unit_amenities']['amenity'] as $amenity) {
                                    ?>
                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                            <?php echo $amenity['amenity_name']; ?>
                                        </div>
                                    <?php }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(isset($options['unit_tab_availability']) && $options['unit_tab_availability'] == 1): ?>
                <a name="availability-title" class="anchor"><?php _e( 'Availability', 'streamline-core' ) ?></a>
                <div class="row" style="margin-top:24px;">
                    <div class="col-md-12">
                        <h3 class="section-title"><?php _e( 'Availability', 'streamline-core' ) ?></h3>
                        <?php do_action('streamline-insert-calendar', $property['id'] ); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (isset($options['unit_tab_location']) && $options['unit_tab_location'] == 1 && (!empty($property['location_latitude']) && !empty($property['location_longitude']))): ?>
                <a name="location-title" class="anchor"><?php _e( 'Location', 'streamline-core' ) ?></a>
                <div class="row" style="margin-top:24px;">
                    <div class="col-md-12">
                        <h3 class="section-title"><?php _e( 'Location', 'streamline-core' ) ?></h3>
                        <div id="property-location">
                            <map
                            center="<?php echo "{$property['location_latitude']},{$property['location_longitude']}" ?>"
                            zoom="8" scrollwheel="false">
                            <marker
                            position="<?php echo "{$property['location_latitude']},{$property['location_longitude']}" ?>"
                            title="<?php echo $property['name'] ?>"></marker>
                            </map>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if(isset($options['unit_tab_reviews']) && $options['unit_tab_reviews'] == 1): ?>
                <a name="reviews-title" class="anchor"><?php _e( 'Reviews', 'streamline-core' ) ?></a>
                <div class="row" style="margin-top:24px;" ng-show="reviews.length > 0">
                    <div class="col-md-12">
                        <h3 class="section-title"><?php _e( 'Reviews', 'streamline-core' ) ?></h3>
                        <div id="property-reviews" ng-init="getPropertyReviews()">
                            <div class="row row-review" ng-repeat="review in reviews">
                                <div class="col-sm-8">
                                    <h3 ng-cloak ng-if="!isEmptyObject(review.title)">{[review.title]}</h3>
                                    <h3 ng-cloak ng-if="isEmptyObject(review.title)"><?php _e( 'Guest Review', 'streamline-core' ) ?></h3>
                                    <span class="by"
                                    ng-cloak><?php _e( 'by', 'streamline-core' ) ?> {[review.guest_name]} on {[review.creation_date]}</span>
                                    <div class="review-details" ng-cloak>
                                        {[review.comments]}
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div style="display: inline-block" class="star-rating text-right" star-rating
                                    rating-value="review.points" data-max="5"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(isset($options['unit_tab_rates']) && $options['unit_tab_rates'] == 1): ?>
                <a name="rates-title" class="anchor"><?php _e( 'Rates', 'streamline-core' ) ?></a>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="section-title"><?php _e( 'Rates', 'streamline-core' ) ?></h3>
                        <div id="rates-details" ng-init="getRatesDetails(<?php echo $property['id']; ?>)">
                            <table class="table table-striped table-bordered table-condensed table-hover" ng-if="rates_details.length >0">
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
                                        <td ng-bind="rate.season_name"></td>
                                        <td><span ng-if="rate.period_begin"></span> - <span ng-if="rate.period_end"></span></td>
                                        <td ng-bind="rate.narrow_defined_days"></td>
                                        <td class="text-center"><span ng-if="rate.daily_first_interval_price" ng-bind="calculateMarkup(rate.daily_first_interval_price) | currency"></span></td>
                                        <td class="text-center"><span ng-if="rate.weekly_price" ng-bind="calculateMarkup(rate.weekly_price) | currency"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(isset($options['unit_tab_room_details']) && $options['unit_tab_room_details'] == 1): ?>
                <a name="rooms-title" class="anchor"><?php _e( 'Rooms Details', 'streamline-core' ) ?></a>
                <div class="row" ng-show="room_details.length > 0">
                    <div class="col-md-12">
                        <h3 class="section-title"><?php _e( 'Room Details', 'streamline-core' ) ?></h3>
                        <div id="room-details" ng-init="getRoomDetails(<?php echo $property['id']; ?>)">

                            <table class="table table-striped table-hover table-condensed table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php _e( 'Room', 'streamline-core' ) ?></th>
                                        <th><?php _e( 'Beds', 'streamline-core' ) ?></th>
                                        <th><?php _e( 'Baths', 'streamline-core' ) ?></th>
                                        <th><?php _e( 'TVs', 'streamline-core' ) ?></th>
                                        <th><?php _e( 'Comments', 'streamline-core' ) ?></th>
                                    </tr>
                                </thead>
                                <tbody>                                    
                                    <tr class="row-review" ng-repeat="room in room_details">
                                        <td ng-bind="room.name"></td>
                                        <td>
                                            <span ng-if="!isArray(room.beds_details) && !isEmptyObject(room.beds_details)" ng-bind="room.beds_details">                                                
                                            </span>
                                            <span ng-if="isArray(room.beds_details)">
                                                <ul>
                                                    <li ng-repeat="bd in room.beds_details" ng-bind="bd"></li>
                                                </ul>
                                            </span>
                                        </td>
                                        <td>
                                            <span ng-if="!isArray(room.bathroom_details) && !isEmptyObject(room.bathroom_details)" ng-if="room.bathroom_details"></span>
                                            <span ng-if="isArray(room.bathroom_details)">
                                                <ul>
                                                    <li ng-repeat="bd in room.bathroom_details" ng-bind="bd"></li>
                                                </ul>
                                            </span>
                                        </td>
                                        <td>
                                            <span ng-if="!isArray(room.television_details) && !isEmptyObject(room.television_details)" ng-bind="room.television_details"></span>
                                            <span ng-if="isArray(room.television_details)">
                                                <ul>
                                                    <li ng-repeat="bd in room.television_details" ng-bind="bd"></li>
                                                </ul>
                                            </span>
                                        </td>
                                        <td style="width:250px"><span ng-if="!isEmptyObject(room.comments)" ng-bind="room.comments"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(isset($options['unit_tab_floorplan']) && $options['unit_tab_floorplan'] == 1 && (!empty($property['floor_plan_url']))): ?>
                <a name="floorplan-title" class="anchor"><?php _e( 'Floor Plan', 'streamline-core' ) ?></a>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="section-title"><?php _e( 'Floor Plan', 'streamline-core' ) ?></h3>
                        <?php echo $property['floor_plan_url']; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(isset($options['unit_tab_virtualtour']) && $options['unit_tab_virtualtour'] == 1 && (!empty($property['virtual_tour_url']))): ?>
                <a name="virtualtour-title" class="anchor"><?php _e( 'Virtual Tour', 'streamline-core' ) ?></a>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="section-title"><?php _e( 'Virtual Tour', 'streamline-core' ) ?></h3>
                        <?php echo $property['virtual_tour_url']; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-4" id="resortpro-book-unit"
                ng-init="maxOccupants='<?php echo $max_children; ?>'; isDisabled=true; total_reservation=0; book.unit_id=<?php echo $property['id'] ?>;book.checkin='<?php echo $start_date; ?>';book.checkout='<?php echo $end_date ?>';getPreReservationPrice(book,<?php echo $res ?>)">
                <div class="<?php echo $sticky_class ?>" <?php echo $sticky_spacing ?> style="background-color: #fff; border:solid 1px #ccc; padding: 15px">
                    <form action="<?php echo $checkout_url ?>" method="post"
                        name="resortpro_form_checkout">
                        <input type="hidden" name="resortpro_book_unit" value="<?php echo $nonce; ?>"/>
                        <input type="hidden" name="book_unit" value="<?php echo $property['id'] ?>"/>
                        <?php if(!empty($hash)): ?>
                        <input type="hidden" name="hash" value="<?php echo $hash; ?>" />
                        <?php endif; ?>

                        <div ng-cloak>
                            <h3 class="price" ng-if="res == 0">
                                <span ng-bind="first_day_price | currency:undefined:0"></span>
                                <span class="concluding_text"> <?php _e( 'per night', 'streamline-core' ) ?></span>
                            </h3>
                            <h3 class="price" ng-if="res == 1 && total_reservation > 0" ng-cloak>
                                <span ng-bind="total_reservation | currency:undefined:0"></span>
                                <span class="concluding_text"><?php _e( 'including taxes and fees', 'streamline-core' ) ?></span></h3>
                            <div class="alert alert-{[alert.type]} animate alert-dismissible" role="alert" ng-repeat="alert in alerts">
                                <button type="button" class="close" data-dismiss="alert" aria-label="<?php _e( 'Close', 'streamline-core' ) ?>"><span
                                aria-hidden="true">&times;</span></button>
                                <div ng-bind-html="alert.message | trustedHtml"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $arrive_label ?></label>
                                    <input type="text"
                                        ng-model="book.checkin"
                                        id="book_start_date"
                                        name="book_start_date"
                                        class="form-control"
                                        show-days="renderCalendar(date, false)"
                                        update-price="getPreReservationPrice(book,1)"
                                        update-checkout="setCheckoutDate(date)"
                                        bookcheckin
                                        readonly="readonly"
                                        data-min-stay="<?php echo $min_stay ?>"
                                        data-checkin-days="<?php echo $checkin_days ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $depart_label ?></label>
                                    <input type="text"
                                        ng-model="book.checkout"
                                        id="book_end_date"
                                        name="book_end_date"
                                        class="form-control"
                                        show-days="renderCalendar(date, true);"
                                        update-price="getPreReservationPrice(book,1);"
                                        bookcheckout
                                        readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" ng-init="book.occupants='<?php echo $occupants; ?>'">
                                    <label for="book_occupants"><?php echo $adults_label ?></label>
                                    <select
                                        ng-model="book.occupants"
                                        ng-change="getPreReservationPrice(book,1);"
                                        name="book_occupants"
                                        class="form-control">
                                        <?php
                                        for ($i = 1; $i <= $max_adults; $i++) {
                                        echo "<option value=\"{$i}\">{$i}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php if ($max_children > 0): ?>
                            <div class="col-md-6">
                                <div class="form-group" ng-init="book.occupants_small='<?php echo $occupants_small; ?>'">
                                    <label for="book_occupants_small"><?php echo $children_label ?></label>
                                    <select
                                        name="book_occupants_small"
                                        class="form-control"
                                        ng-model="book.occupants_small"
                                        ng-change="getPreReservationPrice(book,1);">
                                        <?php
                                        for ($i = 0; $i <= $max_children; $i++) {
                                        echo "<option value=\"{$i}\">{$i}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($max_pets > 0): ?>
                            <div class="col-md-12">
                                <div class="form-group" ng-init="book.pets='<?php echo $pets; ?>'">
                                    <label for="book_pets"><?php echo $pets_label ?></label>
                                    <select
                                        name="book_pets"
                                        class="form-control"
                                        ng-model="book.pets"
                                        ng-change="getPreReservationPrice(book,1);">
                                        <?php
                                        for ($i = 0; $i <= $max_pets; $i++) {
                                        echo "<option value=\"{$i}\">{$i}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <table ng-show="res == 1 && total_reservation > 0"
                            class="table table-stripped table-bordered table-condensed">                            
                            <tr>
                                <td><?php _e( 'Subtotal', 'streamline-core' ) ?>
                                    <a ng-show="!reservationDetails.reservation_days.date" href="#" class="btn btn-default btn-xs btn-price-breakdown pull-right">
                                        <i class="glyphicon glyphicon-menu-hamburger"></i>
                                        <span><?php _e('View Breakdown', 'streamline-core'); ?></span></span>
                                    </a>
                                </td>
                                <td class="text-right"><span ng-bind="subTotal | currency:undefined:2"></span></td>
                            </tr>
                            
                            <tr ng-if="days" ng-repeat="day in reservation_days" class="breakdown-days-hidden" data-visible="false">
                                <td><span class="indent" ng-bind="day.date"></span></td>
                                <td class="text-right" ng-bind="calculateMarkup(day.price.toString()) | currency:undefined:2"></td>
                            </tr>
                            <tr ng-if="!days" class="breakdown-days-hidden" data-visible="false">
                                <td><span class="indent" ng-bind="reservation_days.date"></span></td>
                                <td class="text-right"><span ng-bind="subTotal | currency:undefined:2"></span></td>
                            </tr>

                            <tr ng-if="coupon_discount > 0">
                                <td><?php _e( 'Discount', 'streamline-core' ) ?></td>
                                <td class="text-right"><span ng-bind="'('+(coupon_discount | currency:undefined:2)+')'"></span></td>
                            </tr>
                            <tr>
                                <td><?php _e( 'Taxes and fees', 'streamline-core' ) ?>
                                    <a href="#" class="btn btn-default btn-xs btn-taxes-breakdown pull-right">
                                        <i class="glyphicon glyphicon-menu-hamburger"></i>
                                        <span><?php _e('View Breakdown', 'streamline-core'); ?></span>
                                    </a>
                                </td>
                                <td class="text-right"><span ng-bind="taxes | currency:undefined:2"></span></td>
                            </tr>
                            <tr ng-if="!required_fees.value" ng-repeat="reqFee in required_fees" class="breakdown-taxes-hidden" data-visible="false">
                                <td><span class="indent" ng-bind="reqFee.name"></span></td>
                                <td class="text-right" ng-bind="reqFee.value | currency"></td>
                            </tr>
                            <tr ng-if="required_fees.value" class="breakdown-taxes-hidden" data-visible="false">
                                <td><span class="indent" ng-bind="required_fees.name"></span></td>
                                <td class="text-right" ng-bind="required_fees.value | currency"></td>
                            </tr>
                            <tr ng-if="!taxes_details.value" ng-repeat="reqFee in taxes_details" class="breakdown-taxes-hidden" data-visible="false">
                                <td><span class="indent" ng-bind="reqFee.name"></span></td>
                                <td class="text-right"  ng-bind="reqFee.value | currency"></td>
                            </tr>
                            <tr ng-if="taxes_details.value" class="breakdown-taxes-hidden" data-visible="false">
                                <td><span class="indent" ng-bind="taxes_details.name"></span></td>
                                <td class="text-right"  ng-bind="taxes_details.value | currency"></td>
                            </tr>
                            <tr style="border-top:solid 2px #333">
                                <td><?php _e( 'Total', 'streamline-core' ) ?></td>
                                <td class="text-right"  ng-bind="total_reservation | currency"></td>
                            </tr>
                        </table>
                        <div class="form-group">
                            <?php if(!(is_numeric($property['online_bookings']) && $property['online_bookings'] == 0)): ?>
                            <button ng-disabled="isDisabled" id="resortpro_unit_submit" href="this.submit()" class="btn btn-lg btn-block btn-success">
                                <i class="glyphicon glyphicon-check"></i> <?php _e( 'Book Now', 'streamline-core' ) ?>
                            </button>
                            <?php endif; ?>
                            <button type="button" href="#" data-toggle="modal" data-target="#myModal2" class="btn btn-lg btn-block btn-primary">
                                <i class="glyphicon glyphicon-comment"></i> <?php _e( 'Property Inquiry', 'streamline-core' ) ?>
                            </button>
                        </div>
                    </form>

                    <?php do_action('streamline-insert-share', $property['seo_page_name'], $property['id'], $start_date, $end_date, $occupants, $occupants_small, $pets ); ?>

                    <div class="unit-rating">
                    <?php if ($property['rating_average'] > 0): ?>
                    <div class="star-rating text-right">
                        <div style="display: inline-block"
                            class="star-rating"
                            star-rating
                            rating-value="<?php echo $property['rating_average'] ?>"
                        data-max="5"></div>
                        <span style="display:inline-block; line-height: 31px; vertical-align: top">
                            <?php
                            $reviews_txt = ($property['rating_count'] > 1) ? ' reviews' : ' review';
                            ?>
                            (<?php echo $property['rating_count'] ?> <?php echo $reviews_txt ?> )
                        </span>
                    </div>
                    <?php endif; ?>
                    <ul class="list-group">
                        <li class="list-group-item"><?php _e( 'Sleeps:', 'streamline-core' ) ?>
                            <span class="pull-right"><strong><?php echo $property['max_occupants']; ?></strong></span>
                        </li>
                        <li class="list-group-item"><?php _e( 'Bedrooms:', 'streamline-core' ) ?>
                            <span class="pull-right"><strong><?php echo $property['bedrooms_number']; ?></strong></span>
                        </li>
                        <li class="list-group-item"><?php _e( 'Bathrooms:', 'streamline-core' ) ?>
                            <span class="pull-right"><strong><?php echo $property['bathrooms_number']; ?></strong></span>
                        </li>
                        <li class="list-group-item"><?php _e( 'Pets:', 'streamline-core' ) ?>
                            <span class="pull-right"><strong><?php echo $property['max_pets']; ?></strong></span>
                        </li>
                    </ul>
                </div>
                </div>

            </div>
        </div>
    </main>
    <?php do_action('streamline-insert-inquiry', $property['location_name'], $property['id'], $max_adults, $max_children, $max_pets, $min_stay, $checkin_days, true, $start_date, $end_date, $occupants, $occupants_small, $pets ); ?>
    <?php do_action('streamline-insert-booknow', $property['location_name'], $property['id'], $max_adults, $max_children, $max_pets, $min_stay, $checkin_days ); ?>

</div>
