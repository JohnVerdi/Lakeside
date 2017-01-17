<!-- layout 2 -->
<div class="listing listing-2 col-sm-12 row-spac-2 col-md-4 col-sm-6 col-xs-12" ng-mouseenter="highlightIcon(property.id)"
           ng-mouseleave="restoreIcon(property.id)">    
    <div class="panel-image listing-img">
        
        <a class="petFriendly" ng-if="property.max_pets > 0" style="font-size:1.5em" data-toggle="tooltip" data-placement="left" title="Pet friendly">
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

        <a ng-href="{[goToProperty(property.seo_page_name, search.start_date, search.end_date, search.occupants, search.occupants_small, search.pets)]}"
           class="media-photo media-cover">

            <div class="listing-img-container media-cover text-center">
                <img ng-src="{[property.default_thumbnail_path]}" class="img-responsive-height"
                     ng-alt="{[property.location_name]}"
                     err-src="<?php ResortPro::assets_url('images/dummy-image.jpg'); ?>"/>
            </div>
        </a>

        <span class="link-reset panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label" ng-if="property.price > 0 || property.price_data.daily > 0 || property.price_data.weekly > 0 || property.price_data.monthly > 0">
                        
            <div class="price_wrapper3" ng-if="!isSimplePricing(property)">                
                <span class="h3 text-contrast price-amount" ng-bind="getTotalPrice(property,0)"></span>
                <span class="h6 text-contrast" ng-bind="getTotalAppend(property)"></span>
            </div>
            <div class="price_wrapper3" ng-if="isSimplePricing(property)">
                <span class="h6" ng-bind="getPrependTex(property.price_data)"></span>
                <span class="h3 text-contrast price-amount" ng-bind="getSimplePrice(property.price_data,0)"></span>
                <span class="h6" ng-bind="getAppendTex(property.price_data)"></span>
            </div>
            
		</span>
    </div>
    <div class="panel-body panel-card-section">
        <div class="media">
            <a class="text-normal"
               ng-href="{[goToProperty(property.seo_page_name, search.start_date, search.end_date, search.occupants, search.occupants_small, search.pets)]}">
                <h3 class="h5 listing-name text-truncate row-space-top-1"
                    title="{[getUnitName(property)]}" ng-bind="getUnitName(property)">                  
                </h3>            
            </a>                    
            <a ng-if="!isEmptyObject(property.location_area_id)"
                class="text-normal link-reset truncate"
                ng-href="/search-results?location_area_id={[property.location_area_id]}" ng-bind="property.location_area_name">
            </a>

            <div class="text-muted listing-location text-truncate">
                <?php _e( 'Beds: <span ng-bind="property.bedrooms_number"></span> |', 'streamline-core' ) ?>
                <?php _e( 'Baths: <span ng-bind="property.bathrooms_number"></span> |', 'streamline-core' ) ?> 
                <?php _e( 'Sleeps: <span ng-bind="property.max_occupants"></span>', 'streamline-core' ) ?> 

                <div class="reviews-wrapper">
                    <div ng-if="property.rating_average > 0">
                        <div class="star-rating" star-rating rating-value="property.rating_average" data-max="5"></div>
                        <span class="star-rating-text" ng-bind="'(' + (property.rating_count | pluralizeRating) + ')'"></span>
                    </div>
                    <div ng-if="!property.rating_average > 0">
                        <span class="star-rating-text">No reviews</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- unit end -->