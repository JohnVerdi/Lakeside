<!-- layout 3 -->
<div class="listing listing-3 col-md-12" ng-mouseenter="highlightIcon(property.id)"
           ng-mouseleave="restoreIcon(property.id)">
    <div class="row">
        <div class="col-md-12 property2">
            <div class="property-outer">
                <a ng-href="{[goToProperty(property.seo_page_name, search.start_date, search.end_date, search.occupants, search.occupants_small, search.pets)]}">
                    <img ng-src="{[property.default_image_path]}" class="img-responsive-height"
                        ng-alt="{[property.location_name]}"
                        err-src="<?php ResortPro()->assets_url('images/dummy-image.jpg'); ?>"/>
                </a>
                <div class="property_overlay">
                    <div class="row">
                        <div class="col-md-9">
                            <div style="padding-left:12px">                            
                                <h3 class="listing-name row-space-top-1">
                                    <a ng-href="{[goToProperty(property.seo_page_name, search.start_date, search.end_date, search.occupants, search.occupants_small, search.pets)]}">
                                        <strong ng-bind="getUnitName(property)"></strong>
                                    </a>
                                </h3>
                                <a class="petFriendly" ng-if="property.max_pets > 0" data-toggle="tooltip" data-placement="right" title="Pet friendly">
                                    <i class="fa fa-paw"></i>
                                </a>

                                <?php if($use_favorites): ?>
                                <a ng-if="!checkFavorites(property)" class="btn-fav" ng-click="addToFavorites(property)" data-toggle="tooltip" data-placement="right" title="Add to favorites">
                                    <i class="fa fa-heart-o"></i>
                                </a>                            
                                <a ng-if="checkFavorites(property)" class="btn-fav" ng-click="removeFromFavorites(property)" data-toggle="tooltip" data-placement="right" title="Remove from favorites">
                                    <i class="fa fa-heart"></i>
                                </a>                        
                                <?php endif; ?>

                                <h4 class="location-name">
                                    <span ng-bind="property.location_area_name"></span> |
                                    <?php _e( 'Beds:', 'streamline-core' ) ?> <strong ng-bind="property.bedrooms_number"></strong> |
                                    <?php _e( 'Baths:', 'streamline-core' ) ?> <strong ng-bind="property.bathrooms_number"></strong> |
                                    <?php _e( 'Sleeps:', 'streamline-core' ) ?> <strong ng-bind="property.max_occupants"></strong>
                                </h4>
                            </div>
                        </div>
                        <div class="col-md-3 right_side">
                            <div style="padding-right:12px">
                                                    
                                <div class="price_wrapper2 text-right" ng-if="!isSimplePricing(property)">                
                                    <span class="h3 text-contrast price-amount" ng-bind="getTotalPrice(property,0)"></span><br />
                                    <span ng-bind="getTotalAppend(property)"></span>
                                </div>

                                <div class="price_wrapper2 text-right" ng-if="isSimplePricing(property)">
                                    <span class="h6" ng-bind="getPrependTex(property.price_data)"></span>
                                    <span class="h3 text-contrast price-amount" ng-bind="getSimplePrice(property.price_data,0)"></span>
                                    <span class="h6" ng-bind="getAppendTex(property.price_data)"></span>
                                </div>

                                <div class="text-right star-rating" ng-if="property.rating_average > 0">
                                    <div class="rating-stars" star-rating rating-value="property.rating_average" data-max="5"></div>
                                    <span class="rating-count" ng-bind="'(' + (property.rating_count | pluralizeRating) + ')'"></span>
                                </div>
                                <div class="text-right star-rating" ng-if="property.rating_average == 0">
                                    <span><?php _e( 'No rating available', 'streamline-core' ) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clear clearfix"></div>
<!-- unit end -->
