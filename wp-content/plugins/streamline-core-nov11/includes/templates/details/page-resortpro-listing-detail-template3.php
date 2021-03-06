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

$iframe_url = "https://www.google.com/maps/embed/v1/place?key=AIzaSyAQCDSl4cy4e3p23iVcjYV_CHWLMtxIKC8&q={$property['location_latitude']},{$property['location_longitude']}";
?>

<style type="text/css">
  .entry-header{
    display: none;
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

  .panel-group .panel-title a{
    display: block;
  }
</style>
<div id="primary" class="container content-area" ng-controller="PropertyController as pCtrl">
  <main id="main" class="site-main" role="main" ng-init="initializeData(<?php echo $property['id']; ?>);">

    <div class="row">
      <div class="col-md-12">
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

      <div class="col-md-8">
        <h1 class="property-title">
          <?php
          if(empty($property['name']) || $property['name'] == 'Home' ){
            echo $property['location_name'];
          }else{
            echo $property['name'];
          }
          ?>
        </h1>
      </div>

      <div class="col-md-4 unit-rating">
        <?php if($property['rating_average'] > 0): ?>
          <div class="star-rating text-right" style="vertical-align:top">
            <div style="display: inline-block"
                 class="star-rating"
                 star-rating
                 rating-value="<?php echo $property['rating_average'] ?>"
                 data-max="5">
            </div>
            <?php
            $reviews_txt = ' ' . ($property['rating_count'] > 1) ? __( 'reviews', 'streamline-core' ) : __( 'review', 'streamline-core' );
            ?>
            <p style="vertical-align:top; display:inline-block; font-size:1em !important; line-height:36px; width:auto !important">(<?php echo $property['rating_count'] ?> <?php echo $reviews_txt ?> )</p>


          </div>
        <?php endif;?>
      </div>
      <input type="hidden" value="<?php echo $property['id'] ?>" id="unit_id">
    </div>
    <div class="row">
      <div class="col-md-8">

        <div class="ms-partialview-template" id="partial-view-1">

          <div class="master-slider ms-skin-default" id="masterslider" data-slider-height="<?php echo $slider_height; ?>">
            <?php
            if(count($property_gallery) > 0){
              foreach ($property_gallery as $image): ?>
                <div class="ms-slide">
                  <img src="<?php ResortPro::assets_url('masterslider/blank.gif'); ?>"
                       data-src="<?php echo $image['image_path'] ?>"
                       alt="<?php echo $image['id'] ?>" />

                  <?php if($show_captions && is_string($image['description'])): ?>
                    <div class="ms-layer ms-caption" style="top:10px; left:30px;">
                      <?php echo $image['description'] ?>
                    </div>
                  <?php endif; ?>

                </div>
                <?php
              endforeach;
            }else{
              ?>
              <div class="ms-slide">
                <img src="<?php ResortPro()->assets_url('masterslider/blank.gif'); ?>"
                     data-src="<?php echo $property['default_image_path']; ?>"
                     alt="<?php echo $property['name']; ?>" />
              </div>
              <?php
            }
            ?>
          </div>
        </div>

        <table class="table table-details">
          <tr>
            <td><?php _e( 'Sleeps:', 'streamline-core' ) ?> <span><?php echo $property['max_occupants']; ?></span></td>
            <td><?php _e( 'Bedrooms:', 'streamline-core' ) ?> <span><?php echo $property['bedrooms_number']; ?></span></td>
            <td><?php _e( 'Bathrooms:', 'streamline-core' ) ?> <span><?php echo $property['bathrooms_number']; ?></span></td>
            <td><?php _e( 'Pets:', 'streamline-core' ) ?> <span><?php echo $property['max_pets']; ?></span></td>
          </tr>
        </table>

        <?php if(!wp_is_mobile()): ?>

          <div class="desktop-cont">
            <ul id="property-detail-tabs" class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active">
                <a href="#property-details-pane" aria-controls="property-details-pane"
                   data-toggle="tab"><?php _e( 'Details', 'streamline-core' ) ?></a>
              </li>
              <?php if (isset($options['unit_tab_amenities']) && $options['unit_tab_amenities'] == 1 &&  (count($property['unit_amenities']['amenity']) > 0)): ?>
                <li role="presentation">
                  <a href="#property-amenities-pane" aria-contorls="property-amenities-pane"
                     data-toggle="tab"><?php _e( 'Amenities', 'streamline-core' ) ?></a>
                </li>
              <?php endif; ?>
              <?php if (isset($options['unit_tab_location']) && $options['unit_tab_location'] == 1 && (!empty($property['location_latitude']) && !empty($property['location_longitude']))): ?>
                <li role="presentation" ng-click="mapResize();">
                  <a href="#property-location-pane" aria-controls="property-location-pane"
                     data-toggle="tab"><?php _e( 'Location', 'streamline-core' ) ?></a>
                </li>
              <?php endif; ?>
              <?php if(isset($options['unit_tab_reviews']) && $options['unit_tab_reviews'] == 1): ?>
                <li role="presentation" ng-if="reviews.length > 0">
                  <a href="#property-reviews-pane" aria-controls="property-reviews-pane"
                     data-toggle="tab"><?php _e( 'Reviews', 'streamline-core' ) ?></a>
                </li>
              <?php endif; ?>
              <?php if(isset($options['unit_tab_rates']) && $options['unit_tab_rates'] == 1): ?>
                <li role="presentation">
                  <a href="#property-rates-pane" aria-controls="property-rates-pane"
                     data-toggle="tab"><?php _e( 'Rates', 'streamline-core' ) ?></a>
                </li>
              <?php endif; ?>
              <?php if(isset($options['unit_tab_room_details']) && $options['unit_tab_room_details'] == 1): ?>
                <li role="presentation">
                  <a href="#property-rooms-pane" aria-controls="property-rooms-pane"
                     data-toggle="tab"><?php _e( 'Room Details', 'streamline-core' ) ?></a>
                </li>
              <?php endif; ?>
              <?php if(isset($options['unit_tab_availability']) && $options['unit_tab_availability'] == 1): ?>
                <li role="presentation">
                  <a href="#property-availability-pane"
                     aria-controls="property-availability-pane" data-toggle="tab"><?php _e( 'Availability', 'streamline-core' ) ?></a>
                </li>
              <?php endif; ?>
              <?php if(isset($options['unit_tab_floorplan']) && $options['unit_tab_floorplan'] == 1 && (!empty($property['floor_plan_url']))): ?>
                <li role="presentation">
                  <a href="#property-floorplan-pane"
                     aria-controls="property-floorplan-pane" data-toggle="tab"><?php _e( 'Floor Plan', 'streamline-core' ) ?></a>
                </li>
              <?php endif; ?>
              <?php if(isset($options['unit_tab_virtualtour']) && $options['unit_tab_virtualtour'] == 1 && (!empty($property['virtual_tour_url']))): ?>
                <li role="presentation">
                  <a href="#property-virtualtour-pane"
                     aria-controls="property-virtualtour-pane" data-toggle="tab"><?php _e( 'Virtual Tour', 'streamline-core' ) ?></a>
                </li>
              <?php endif; ?>
            </ul>
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane fade in active" id="property-details-pane">
                <div id="property-description">
                  <h3 id="property-details-pane"><?php _e( 'Description', 'streamline-core' ) ?></h3>

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
              <?php endif; ?>
              <?php if (isset($options['unit_tab_location']) && $options['unit_tab_location'] == 1 && (!empty($property['location_latitude']) && !empty($property['location_longitude']))): ?>
                <div role="tabpanel" class="tab-pane" id="property-location-pane">
                  <div id="property-location">
                    <h3 id=""><?php _e( 'Location', 'streamline-core' ) ?></h3>
                    <iframe src="<?php echo $iframe_url; ?>" style="width: 100%; height: 300px"></iframe>
                  </div>
                </div>
              <?php endif; ?>

              <?php if(isset($options['unit_tab_availability']) && $options['unit_tab_availability'] == 1): ?>
                <div id="property-availability-pane" role="tabpanel" class="tab-pane" class="availability">
                  <h3><?php _e( 'Availability', 'streamline-core' ) ?></h3>
                  <?php do_action('streamline-insert-calendar', $property['id'] ); ?>
                </div>
              <?php endif; ?>

              <?php if(isset($options['unit_tab_reviews']) && $options['unit_tab_reviews'] == 1): ?>
                <div id="property-reviews-pane" role="tabpanel" class="tab-pane" ng-init="getPropertyReviews(<?php echo $property['id']; ?>)">
                  <h3><?php _e( 'Reviews', 'streamline-core' ) ?></h3>
                  <div id="property-reviews">
                    <div class="row row-review" ng-show="reviews.length > 0" ng-repeat="review in reviews">
                      <div class="col-sm-8">
                        <h3 ng-if="!isEmptyObject(review.title)" ng-bind="review.title"></h3>
                        <h3 ng-cloak ng-if="isEmptyObject(review.title)"><?php _e( 'Guest Review', 'streamline-core' ) ?></h3>

                        <div class="by">by <span class="guest-name" ng-bind="review.guest_name"></span> on <span class="creation-date" ng-bind="review.creation_date"></span></div>

                        <div class="review-details" ng-bind-html="review.comments | trustedHtml"></div>
                      </div>
                      <div class="col-sm-4">
                        <div style="display: inline-block" class="star-rating text-right" star-rating
                             rating-value="review.points" data-max="5"></div>
                      </div>
                    </div>
                    <div ng-show="!reviews.length > 0">
                      <p><?php _e( 'No reviews have been entered for this unit.', 'streamline-core' ) ?></p>
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <?php if(isset($options['unit_tab_rates']) && $options['unit_tab_rates'] == 1): ?>
                <div id="property-rates-pane" role="tabpanel" class="tab-pane" ng-init="getRatesDetails(<?php echo $property['id']; ?>)">
                  <h3><?php _e( 'Rates', 'streamline-core' ) ?></h3>
                  <div id="property-rates">
                    <div id="rates-details">
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
                          <td ng-bind="(rate.period_begin + ' - ' + rate.period_end)"></td>
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
                <div id="property-rooms-pane" role="tabpanel" class="tab-pane" ng-init="getRoomDetails(<?php echo $property['id']; ?>)">
                  <h3>Rooms</h3>
                  <div id="room-details">
                    <table class="table table-striped table-hover table-condensed table-bordered" ng-if="room_details.length >0">
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
                          <span ng-if="!isArray(room.beds_details) && !isEmptyObject(room.beds_details)" ng-bind="room.beds_details"></span>
                          <span ng-if="isArray(room.beds_details)">
                              <ul>
                                  <li ng-repeat="bd in room.beds_details" ng-bind="bd"></li>
                              </ul>
                          </span>
                        </td>
                        <td>
                          <span ng-if="!isArray(room.bathroom_details) && !isEmptyObject(room.bathroom_details)" ng-bind="room.bathroom_details"></span>
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
              <?php endif; ?>
              <?php if(isset($options['unit_tab_floorplan']) && $options['unit_tab_floorplan'] == 1 && (!empty($property['floor_plan_url']))): ?>
                <div id="property-floorplan-pane" role="tabpanel" class="tab-pane">
                  <h3><?php _e( 'Floor Plan', 'streamline-core' ) ?></h3>
                  <div id="floor_plan">
                    <?php echo $property['floor_plan_url']; ?>
                  </div>
                </div>
              <?php endif; ?>
              <?php if(isset($options['unit_tab_virtualtour']) && $options['unit_tab_virtualtour'] == 1 && (!empty($property['virtual_tour_url']))): ?>
                <div id="property-virtualtour-pane" role="tabpanel" class="tab-pane">
                  <h3><?php _e( 'Virtual Tour', 'streamline-core' ) ?></h3>
                  <div id="virtual_tour">
                    <?php echo $property['virtual_tour_url']; ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>

        <?php else: ?>

          <div class="mobile-cont">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                  <h4 class="panel-title">
                    <a role="button" data-toggle="collapse"
                       href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      <?php _e( 'Details', 'streamline-core' ) ?>
                    </a>
                  </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel"
                     aria-labelledby="headingOne">
                  <div class="panel-body">
                    <div class="property_description">
                      <?php
                      if (is_string($property['description'])) {
                        echo $property['description'];
                      }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
              <?php if (isset($options['unit_tab_amenities']) && $options['unit_tab_amenities'] == 1 &&  (count($property['unit_amenities']['amenity']) > 0)): ?>
                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title">
                      <a class="collapsed" role="button" data-toggle="collapse"
                         href="#collapseTwo" aria-expanded="false"
                         aria-controls="collapseTwo">
                        <?php _e( 'Amenities', 'streamline-core' ) ?>
                      </a>
                    </h4>
                  </div>
                  <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                       aria-labelledby="headingTwo">
                    <div class="panel-body">
                      <ul class="list-group row amenities">
                        <?php
                        foreach ($property['unit_amenities']['amenity'] as $amenity) {
                          ?>
                          <li class="list-group-item col-xs-6 col-sm-6">
                            <?php echo $amenity['amenity_name']; ?>
                          </li>
                        <?php } ?>
                      </ul>
                      <div class="clearfix"></div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
              <?php if (isset($options['unit_tab_location']) && $options['unit_tab_location'] == 1 && (!empty($property['location_latitude']) && !empty($property['location_longitude']))): ?>
                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingThree">
                    <h4 class="panel-title">
                      <a class="collapsed" role="button" data-toggle="collapse"
                         href="#collapseThree" aria-expanded="false"
                         aria-controls="collapseThree">
                        <?php _e( 'Location', 'streamline-core' ) ?>
                      </a>
                    </h4>
                  </div>
                  <div id="collapseThree" class="panel-collapse collapse" role="tabpanel"
                       aria-labelledby="headingThree">
                    <div class="panel-body">
                      <iframe src="<?php echo $iframe_url; ?>" style="width: 100%; height: 300px"></iframe>
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <?php if(isset($options['unit_tab_reviews']) && $options['unit_tab_reviews'] == 1): ?>
                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingFour">
                    <h4 class="panel-title">
                      <a role="button" data-toggle="collapse"
                         href="#collapseReviews" aria-expanded="true" aria-controls="collapseReviews">
                        <?php _e( 'Reviews', 'streamline-core' ) ?>
                      </a>
                    </h4>
                  </div>
                  <div id="collapseReviews" class="panel-collapse collapse" role="tabpanel"
                       aria-labelledby="headingFour">
                    <div class="panel-body" ng-init="getPropertyReviews(<?php echo $property['id']; ?>)">
                      <div class="row row-reviews" ng-show="reviews.length > 0" ng-repeat="review in reviews">
                        <div class="col-sm-8">
                          <h3 ng-cloak ng-if="!isEmptyObject(review.title)" ng-bind="review.title"></h3>
                          <h3 ng-cloak ng-if="isEmptyObject(review.title)"><?php _e( 'Guest Review', 'streamline-core' ) ?></h3>
                          <span class="by"><?php _e( 'by', 'streamline-core' ) ?>
                            <span ng-bind="review.guest_name"></span>
                            <?php _e( 'on', 'streamline-core' ) ?>
                            <span ng-bind="review.creation_date"></span>
                          </span>
                          <div class="review-details" ng-bind="review.comments"></div>
                        </div>
                        <div class="col-sm-4">
                          <span class="rating" ng-bind="review.points"></span>
                        </div>
                      </div>
                      <div ng-show="!reviews.length > 0">
                        <?php _e( 'No reviews have been entered for this unit.', 'streamline-core' ) ?>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <?php if(isset($options['unit_tab_availability']) && $options['unit_tab_availability'] == 1): ?>
                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingFive">
                    <h4 class="panel-title">
                      <a role="button" data-toggle="collapse"
                         href="#collapseAvailability" aria-expanded="true"
                         aria-controls="collapseAvailability">
                        <?php _e( 'Availability', 'streamline-core' ) ?>
                      </a>
                    </h4>
                  </div>
                  <div id="collapseAvailability" class="panel-collapse collapse" role="tabpanel"
                       aria-labelledby="headingFive">
                    <div class="panel-body">
                      <?php do_action('streamline-insert-calendar', $property['id'] ); ?>
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <?php if(isset($options['unit_tab_rates']) && $options['unit_tab_rates'] == 1): ?>
                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingRates">
                    <h4 class="panel-title">
                      <a role="button" data-toggle="collapse"
                         href="#collapseRate" aria-expanded="true"
                         aria-controls="collapseRate">
                        <?php _e( 'Rates', 'streamline-core' ) ?>
                      </a>
                    </h4>
                  </div>
                  <div id="collapseRate" class="panel-collapse collapse" role="tabpanel"
                       aria-labelledby="headingRates">
                    <div class="panel-body" ng-init="getRatesDetails(<?php echo $property['id']; ?>)">
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
                          <td ng-bind="rate.period_begin + ' - ' + rate.period_end"></td>
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
                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingRooms">
                    <h4 class="panel-title">
                      <a role="button" data-toggle="collapse"
                         href="#collapseRooms" aria-expanded="true"
                         aria-controls="collapseRooms">
                        <?php _e( 'Room Details', 'streamline-core' ) ?>
                      </a>
                    </h4>
                  </div>
                  <div id="collapseRooms" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingRooms">
                    <div class="panel-body" ng-init="getRoomDetails(<?php echo $property['id']; ?>)">
                      <div id="room-details-mobile">
                        <table class="table table-striped table-hover table-condensed table-bordered" ng-if="room_details.length >0">
                          <thead>
                          <tr>
                            <th><?php _e( 'Room', 'streamline-core' ) ?></th>
                            <th><?php _e( 'Beds', 'streamline-core' ) ?></th>
                            <th><?php _e( 'Baths', 'streamline-core' ) ?></th>
                          </tr>
                          </thead>
                          <tbody>
                          <tr class="row-review" ng-repeat="room in room_details">
                            <td ng-bind="room.name"></td>
                            <td>
                              <span ng-if="!isArray(room.beds_details) && !isEmptyObject(room.beds_details)" ng-bind="room.beds_details"></span>
                              <span ng-if="isArray(room.beds_details)">
                                  <ul>
                                      <li ng-repeat="bd in room.beds_details" ng-bind="bd"></li>
                                  </ul>
                              </span>
                            </td>
                            <td>
                              <span ng-if="!isArray(room.bathroom_details) && !isEmptyObject(room.bathroom_details)" ng-bind="room.bathroom_details"></span>
                              <span ng-if="isArray(room.bathroom_details)">
                                  <ul>
                                      <li ng-repeat="bd in room.bathroom_details" ng-bind="bd"></li>
                                  </ul>
                              </span>
                            </td>
                          </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <?php if(isset($options['unit_tab_floorplan']) && $options['unit_tab_floorplan'] == 1 && (!empty($property['floor_plan_url']))): ?>
                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingFloor">
                    <h4 class="panel-title">
                      <a role="button" data-toggle="collapse"
                         href="#collapseFloorplan" aria-expanded="true"
                         aria-controls="collapseFloorplan">
                        <?php _e( 'Floor Plan', 'streamline-core' ) ?>
                      </a>
                    </h4>
                  </div>
                  <div id="collapseFloorplan" class="panel-collapse collapse" role="tabpanel"
                       aria-labelledby="headingFloor">
                    <div class="panel-body">
                      <div id="floorplan-mobile">
                        <?php echo $property['floor_plan_url']; ?>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>

              <?php if(isset($options['unit_tab_virtualtour']) && $options['unit_tab_virtualtour'] == 1 && (!empty($property['virtual_tour_url']))): ?>
                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingTour">
                    <h4 class="panel-title">
                      <a role="button" data-toggle="collapse"
                         href="#collapseVirtualtour" aria-expanded="true"
                         aria-controls="collapseVirtualtour">
                        <?php _e( 'Virtual Tour', 'streamline-core' ) ?>
                      </a>
                    </h4>
                  </div>
                  <div id="collapseVirtualtour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTour">
                    <div class="panel-body">
                      <div id="virtualtour-mobile">
                        <?php echo $property['virtual_tour_url']; ?>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
      <div class="col-md-4" id="resortpro-book-unit"
           ng-init="maxOccupants='<?php echo $max_children; ?>'; isDisabled=true; total_reservation=0; book.unit_id=<?php echo $property['id'] ?>;book.checkin='<?php echo $start_date; ?>';book.checkout='<?php echo $end_date ?>';getPreReservationPrice(book,<?php echo $res ?>)">

        <div class="inquiry right-side">

          <div class="alert alert-{[alert.type]} animate"
               ng-repeat="alert in alerts">
            <div ng-bind-html="alert.message | trustedHtml"></div>
          </div>

          <?php if(!empty($booknow_title)): ?>
            <h3 class="text-center"><?php echo $booknow_title; ?></h3>
          <?php endif; ?>

          <form action="<?php echo $checkout_url ?>" method="post" name="resortpro_form_checkout">
            <input type="hidden" name="resortpro_book_unit" value="<?php echo $nonce; ?>"/>
            <input type="hidden" name="book_unit" value="<?php echo $property['id'] ?>"/>
            <?php if(!empty($hash)): ?>
              <input type="hidden" name="hash" value="<?php echo $hash; ?>" />
            <?php endif; ?>
            <h3 class="price" ng-show="res == 0" ng-cloak >
              <span ng-bind="first_day_price | currency:undefined:0"></span>
              <span class="text"> <?php _e( 'Per Night', 'streamline-core' ) ?></span>
            </h3>

            <h3 class="price" ng-show="res == 1 && total_reservation > 0" ng-cloak >{[total_reservation |
              currency:undefined:0]} <span
                class="text" style="font-size: 0.6em"><?php _e( 'including taxes and fees', 'streamline-core' ) ?></span></h3>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label><?php _e( 'Arrive', 'streamline-core' ) ?></label>
                  <input type="text"
                         ng-model="book.checkin"
                         id="book_start_date"
                         name="book_start_date"
                         class="form-control"
                         show-days="renderCalendar(date, false)"
                         update-price="getPreReservationPrice(book,1)"
                         update-checkout="setCheckoutDate(date)"
                         bookcheckin
                         data-min-stay="<?php echo $min_stay ?>"
                         data-checkin-days="<?php echo $checkin_days ?>"
                  />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label><?php _e( 'Depart', 'streamline-core' ) ?></label>
                  <input type="text"
                         ng-model="book.checkout"
                         id="book_end_date"
                         name="book_end_date"
                         class="form-control"
                         show-days="renderCalendar(date, true)"
                         update-price="getPreReservationPrice(book,1)"
                         bookcheckout
                  />
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

            <table ng-show="res == 1 && total_reservation > 0" class="table table-stripped table-bordered table-condensed">
              <tr ng-if="days" ng-repeat="day in reservation_days">
                <td ng-bind="day.date"></td>
                <td class="text-right" ng-bind="calculateMarkup(day.price.toString()) | currency:undefined:2"></td>
              </tr>
              <tr ng-if="!days">
                <td ng-bind="reservation_days.date"></td>
                <td class="text-right"><span ng-bind="subTotal | currency:undefined:2"></span></td>
              </tr>
              <tr style="border-top:solid 2px #333">
                <td><?php _e( 'Subtotal', 'streamline-core' ) ?></td>
                <td class="text-right"><span ng-bind="subTotal | currency:undefined:2"></span></td>
              </tr>
              <tr ng-if="coupon_discount > 0">
                <td><?php _e( 'Discount', 'streamline-core' ) ?></td>
                <td class="text-right"><span ng-bind="coupon_discount | currency:undefined:0"></span></td>
              </tr>
              <tr>
                <td><?php _e( 'Taxes and fees', 'streamline-core' ) ?></td>
                <td class="text-right"><span ng-bind="taxes | currency:undefined:2"></span></td>
              </tr>
            </table>

            <?php if(!(is_numeric($property['online_bookings']) && $property['online_bookings'] == 0)): ?>
              <div class="form-group">
                <button ng-disabled="isDisabled" id="resortpro_unit_submit" href="this.submit()"
                        class="btn btn-lg btn-block btn-success">
                  <i class="glyphicon glyphicon-check"></i> <?php _e( 'Book Now', 'streamline-core' ) ?>
                </button>
              </div>
            <?php endif; ?>
          </form>
          <?php do_action('streamline-insert-share', $property['seo_page_name'], $property['id'], $start_date, $end_date, $occupants, $occupants_small, $pets ); ?>
        </div>

        <div class="inquiry right-side" id="inquiry_box" style="margin-top:24px">
          <?php if(!empty($inquiry_title)): ?>
            <h3 class="text-center"><?php echo $inquiry_title; ?></h3>
          <?php endif; ?>

          <?php do_action('streamline-insert-inquiry', $property['location_name'], $property['id'], $max_adults, $max_children, $max_pets, $min_stay, $checkin_days, false, $start_date, $end_date, $occupants, $occupants_small, $pets ); ?>
        </div>
      </div>
    </div>
  </main>
  <?php do_action('streamline-insert-booknow', $property['location_name'], $property['id'], $max_adults, $max_children, $max_pets, $min_stay, $checkin_days ); ?>

  <!-- .site-main -->
</div><!-- .content-area -->
