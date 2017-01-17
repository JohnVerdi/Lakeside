<!-- listing 4 -->
<div class="listing listing-4 col-md-12" ng-mouseenter="highlightIcon(property.id)" ng-mouseleave="restoreIcon(property.id)">
    <div class="row">
        <div class="col-md-6">
            <div class="property-outer">
                <div class="hidden-md hidden-lg">
                    <h3 class="listing-name row-space-top-1" class="listing-name row-space-top-1" ng-bind="getUnitName(property)"></h3>
                    <h4 class="location-name"><span ng-bind="property.location_area_name"></span></h4>
                </div>
                <a class="petFriendly" ng-if="property.max_pets > 0" data-toggle="tooltip" data-placement="right" title="Pet friendly">
                    <i class="fa fa-paw"></i>
                </a>
                <a ng-href="{[goToProperty(property.seo_page_name, search.start_date, search.end_date, search.occupants, search.occupants_small, search.pets)]}">
                    <img ng-src="{[property.default_image_path]}" class="img-responsive-height"
                     ng-alt="{[property.location_name]}"
                     err-src="<?php ResortPro()->assets_url('images/dummy-image.jpg'); ?>"
                     style="width: 100%;"/>
                </a>
                <div class="row">
                    <div class="col-xs-6" style="padding-right: 0">
                        <a class="propertyButtons book"
                           ng-href="{[goToProperty(property.seo_page_name, search.start_date, search.end_date, search.occupants, search.occupants_small, search.pets)]}">
                            <?php _e( 'More Info', 'streamline-core' ) ?>
                        </a>
                    </div>
                    <div class="col-xs-6" style="padding-left: 0">
                        <a class="propertyButtons inquiry" href="#" ng-click="setUnitForInquiry(property.id)" data-toggle="modal" data-target="#myModal2">
                            <?php _e( 'Property Inquiry', 'streamline-core' ) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-8">
                    <div class="hidden-sm hidden-xs">
                        <h3 class="listing-name row-space-top-1" ng-bind="getUnitName(property)"></h3>                        
                        <h4 class="location-name"><span ng-bind="property.location_area_name"></span></h4>
                    </div>
                </div>
                <div class="col-md-4">                    
                    
                    <div class="price_wrapper" ng-if="!isSimplePricing(property)">                
                        <span class="h3 text-contrast price-amount" ng-bind="getTotalPrice(property,0)"></span><br />
                        <span ng-bind="getTotalAppend(property)"></span>
                    </div>
                    <div class="price_wrapper" ng-if="isSimplePricing(property)">
                        <span ng-bind="getPrependTex(property.price_data)"></span><br />
                        <span class="h3 text-contrast price-amount" ng-bind="getSimplePrice(property.price_data,0)"></span><br />
                        <span ng-bind="getAppendTex(property.price_data)"></span>
                    </div>
                </div>
            </div>
            <div class="star-rating" ng-if="property.rating_average > 0">
                <div class="rating-stars" star-rating rating-value="property.rating_average" data-max="5"></div>
                <span class="rating-count" ng-bind="property.rating_count | pluralizeRating"></span>

                <?php if($use_favorites): ?>
                <a ng-if="!checkFavorites(property)" class="btn-fav" ng-click="addToFavorites(property)" data-toggle="tooltip" data-placement="right" title="Add to favorites">
                    <i class="fa fa-heart-o"></i>
                </a>
                
                <a ng-if="checkFavorites(property)" class="btn-fav" ng-click="removeFromFavorites(property)" data-toggle="tooltip" data-placement="right" title="Remove from favorites">
                    <i class="fa fa-heart"></i>
                </a>
                <?php endif; ?>

            </div>
            <div class="star-rating" ng-if="property.rating_average == 0">
                <span><?php _e( 'No rating available', 'streamline-core' ) ?></span>
            </div>
            <p>
                <?php _e( 'Beds:', 'streamline-core' ) ?> <strong ng-bind="property.bedrooms_number"></strong>&nbsp;&nbsp;
                <?php _e( 'Baths:', 'streamline-core' ) ?> <strong ng-bind="property.bathrooms_number"></strong>&nbsp;&nbsp;
                <?php _e( 'Sleeps:', 'streamline-core' ) ?> <strong ng-bind="property.max_occupants"></strong>&nbsp;&nbsp;                
            </p>
            <p ng-if="!isEmptyObject(property.short_description)" ng-bind-html="property.short_description | trustedHtml"></p>
        </div>
    </div>
</div>
<!-- unit end -->