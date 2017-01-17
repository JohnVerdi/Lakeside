<!-- layout 1 -->
<div class="listing-1 col-sm-12 row-spac-2 col-md-4 col-sm-6 col-xs-12 grid-1" ng-mouseenter="highlightIcon(property.id)"
           ng-mouseleave="restoreIcon(property.id)">
    <div class="property">
        <div class="propertyInfo">
            <div class="propertyTitle">                
                
                <a class="location_name truncate" ng-href="{[goToProperty(property.seo_page_name, search.start_date, search.end_date, search.occupants, search.occupants_small, search.pets)]}" ng-bind="getUnitName(property)"></a>
                
                <div class="propertyLocation truncate" ng-if="!isEmptyObject(property.location_area_id)" ng-bind="property.location_area_name"></div>
                                                                                
                <div class="star-rating" ng-if="property.rating_average > 0">
                    <div class="rating-stars" star-rating rating-value="property.rating_average" data-max="5"></div>
                    <span class="rating-count" ng-bind="'(' + (property.rating_count | pluralizeRating) + ')'"></span>
                </div>
                <div class="star-rating" ng-if="property.rating_average == 0">
                    <span><?php _e( 'No reviews', 'streamline-core' ) ?></span>
                </div>                
            </div>
        </div>
        <div class="propertyPhoto">
                        
            <a class="petFriendly" ng-if="property.max_pets > 0" data-toggle="tooltip" data-placement="left" title="Pet friendly"><i class="fa fa-paw"></i></a>            
            
            <?php if($use_favorites): ?>
            <a ng-if="!checkFavorites(property)" class="btn-fav" ng-click="addToFavorites(property)" data-toggle="tooltip" data-placement="right" title="Add to favorites">
            <i class="fa fa-heart-o"></i></a>
            
            <a ng-if="checkFavorites(property)" class="btn-fav" ng-click="removeFromFavorites(property)" data-toggle="tooltip" data-placement="right" title="Remove from favorites">
            <i class="fa fa-heart"></i></a>
            <?php endif; ?>

            <a class="thumb" ng-href="{[goToProperty(property.seo_page_name, search.start_date, search.end_date, search.occupants, search.occupants_small, search.pets)]}">
                <img ng-src="{[property.default_thumbnail_path]}"
                     err-src="<?php ResortPro::assets_url('images/dummy-image.jpg'); ?>"
                     ng-alt="{[property.location_name]}"/>
            </a>
        </div>
        <div class="propertyInfo2">

            <div class="propertySleeps" data-toggle="tooltip" data-placement="left" title="Sleeps: {[property.max_occupants]}">
                <i class="fa fa-group"></i> <span> <span ng-bind="property.max_occupants"></span>
            </div>
            <div class="propertyBedrooms" data-toggle="tooltip" data-placement="left" title="Bedrooms: {[property.bedrooms_number]}">
                <i class="fa fa-hotel"></i> <span ng-bind="property.bedrooms_number"></span>
            </div>

            <div class="propertyCost" ng-if="!isSimplePricing(property)">                
                <span ng-bind="getTotalPrice(property,0)"></span>
                <span ng-bind="getTotalAppend(property)"></span>
            </div>
            
            <div class="propertyCost" ng-if="isSimplePricing(property)">
                <span class="h6" ng-bind="getPrependTex(property.price_data)"></span>
                <span ng-bind="getSimplePrice(property.price_data,0)"></span>
                <span class="h6" ng-bind="getAppendTex(property.price_data)"></span>
            </div>

            
        </div>
    </div>
</div>