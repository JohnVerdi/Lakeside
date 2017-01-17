<div id="primary" class="content-area" ng-controller="PropertyController as pCtrl" ng-cloak>
    <main id="main" class="site-main" role="main"
          ng-init="requestPropertyList('GetPropertyListWordPress', {params});">

        <div class="loading" ng-show="loading">
            <i class="fa fa-circle-o-notch fa-spin"></i> <?php _e( 'Loading...', 'streamline-core' ) ?>
        </div>

        <div class="alert alert-danger" ng-show="!properties.length && !loading">
            <p><?php _e( 'Unable to find any listings.', 'streamline-core' ) ?></p>
        </div>


        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12" id="listings_wrapper">


                </div>
            </div>
            <div class="row">
                <div class="listings_wrapper_box">
                    <div
                        class="col-sm-12 row-spac-2 col-md-4 col-sm-6 col-xs-12"
                        ng-show="properties.length"
                        dir-paginate="property in properties | filter: priceRange | itemsPerPage: 12">

                        <!-- unit start -->
                        <div class="listing">
                            <div class="panel-image listing-img">

                                <a href="/resortpro-listing-detail/?id={[property.id]}&sd={[search.start_date]}&ed={[search.end_date]}"
                                   class="media-photo media-cover" ng-mouseenter="highlightIcon(property.id)"
                                   ng-mouseleave="restoreIcon(property.id)">
                                    <div class="listing-img-container media-cover text-center">
                                        <img ng-src="{[property.default_thumbnail_path]}" class="img-responsive-height"
                                             alt="$title photo"
                                             err-src="<?php ResortPro::assets_url('images/dummy-image.jpg'); ?>"/>
                                    </div>
                                </a>

	                        <span
                                class="link-reset panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label">
	                            <div>
                                    <div ng-show="property.price_data.daily > 0">
                                        <sup class="h6 text-contrast">$</sup>
                                        <span class="h3 text-contrast price-amount">{[property.price_data.daily]}</span>
                                        <sup class="h6 text-contrast"><?php _e( 'USD', 'streamline-core' ) ?></sup>
                                    </div>

                                    <span ng-show="property.price_data.daily <=0"><?php _e( 'No price available', 'streamline-core' ) ?></span>


                                </div>
							</span>

                            </div>
                            <div class="panel-body panel-card-section">
                                <div class="media">
                                    <a class="text-normal" target="listing_$unit_id" href="/resortpro-listing-detail/?id={[property.id]}&sd={[search.start_date]}&ed={[search.end_date]}">
                                        <h3 class="h5 listing-name text-truncate row-space-top-1"
                                            title="{[property.title]}">
                                            {[property.title]}
                                        </h3>
                                    </a>

                                    <div class="text-muted listing-location text-truncate">
                                        <a class="text-normal link-reset" href="/resortpro-listing-detail/?id={[property.id]}&sd={[search.start_date]}&ed={[search.end_date]}">
                                            {[property.location_name]}
                                        </a> | <?php _e( 'Beds:', 'streamline-core' ) ?> {[property.bedrooms_number]} / <?php _e( 'Baths:', 'streamline-core' ) ?> {[property.bathrooms_number]}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- unit end -->

                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-12">
                    <div style="text-align:center;">
                        <dir-pagination-controls max-size="5"></dir-pagination-controls>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 hidden-sm hidden-xs" style="height:600px">
            <div class="map" style="max-width:100%">

                <map center="33.397, -111.944" zoom="12" on-dragend="dragEnd(search)" on-zoom-changed="dragEnd(search)"
                     ng-init="initializeMap()" class="map-canvas"></map>

                <div class="map-refresh-controls google">
                    <!-- <a class="map-manual-refresh btn btn-primary hide">
                        Volver a buscar aquí
                        <i class="icon icon-refresh icon-space-left"></i>
                    </a> -->
                    <div class="panel map-auto-refresh">
                        <label class="checkbox">
                            <input class="map-auto-refresh-checkbox" type="checkbox" checked="checked"/>
                            <small><?php _e( 'Search while moving map', 'streamline-core' ) ?></small>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

    </main>
    <!-- .site-main -->
</div><!-- .content-area -->
