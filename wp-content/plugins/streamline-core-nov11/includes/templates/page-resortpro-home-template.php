<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all resortpro listings by default.
 * You can override this template by creating your own "my_theme/template/page-resortpro-home.php" file
 *
 * @package    ResortPro
 * @since      v1.0
 */


get_header();

$locations = StreamlineCore_Wrapper::get_location_areas();
?>

<div class="hero" style="background-image:url(<?php echo get_template_directory_uri(); ?>/assets/laguna_image.jpg);">
    <?php if (is_active_sidebar('search_home')) : ?>
        <div id="widget-area" class="widget-area" role="complementary">
            <?php dynamic_sidebar('search_home'); ?>
        </div><!-- .widget-area -->
    <?php endif; ?>
</div>
<div class="container searchResults" ng-controller="PropertyController as pCtrl" ng-cloak>
    <div class="row">
        <div class="col-md-12">
            <div class="pageTitle"><?php _e( 'Laguna Beach Rentals', 'streamline-core' ) ?></div>
        </div>
    </div>
    <div class="row form-group hidden-md hidden-lg">

        <div class="col-md-6 col-sm-6 col-xs-6">
            <label for="cmbBedrooms"><?php _e( 'Bedrooms', 'streamline-core' ) ?></label>
            <select class="form-control" name="cmbBedrooms">
                <option value="0"><?php _e( 'All Bedrooms', 'streamline-core' ) ?></option>
                <option value="1"><?php _e( '1 Bedroom', 'streamline-core' ) ?></option>
                <option value="2"><?php _e( '2 Bedroom', 'streamline-core' ) ?></option>
                <option value="3"><?php _e( '3 Bedroom', 'streamline-core' ) ?></option>
                <option value="4"><?php _e( '4 Bedroom', 'streamline-core' ) ?></option>
                <option value="5"><?php _e( '5 Bedroom', 'streamline-core' ) ?></option>
                <option value="6"><?php _e( '6 Bedroom', 'streamline-core' ) ?></option>
            </select>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6">
            <label for="cmbLocations"><?php _e( 'Locations', 'streamline-core' ) ?></label>
            <select class="form-control" name="cmbLocations">
                <?php foreach($locations as $location): ?>
                    <option value="<?php echo $location->id?>"><?php echo $location->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>

    </div>
    <div class="row hidden-sm hidden-xs">
        <div class="col-md-12">

            <div class="filters">
                <ul class="filter1" opt-kind="" ok-key="filter">
                    <li><a ng-click="filterBedrooms(0)" data-filter="" ok-sel="*"><?php _e( 'All Bedrooms', 'streamline-core' ) ?></a></li>
                    <li><a ng-click="filterBedrooms(1)" data-filter="1bed" ok-sel=".1bed"><?php _e( '1 Bedroom', 'streamline-core' ) ?></a></li>
                    <li><a ng-click="filterBedrooms(2)" data-filter="2bed" ok-sel=".2bed"><?php _e( '2 Bedroom', 'streamline-core' ) ?></a></li>
                    <li><a ng-click="filterBedrooms(3)" data-filter="3bed" ok-sel=".3bed"><?php _e( '3 Bedroom', 'streamline-core' ) ?></a></li>
                    <li><a ng-click="filterBedrooms(4)" data-filter="4bed" ok-sel=".4bed"><?php _e( '4 Bedroom', 'streamline-core' ) ?></a></li>
                    <li><a ng-click="filterBedrooms(5)" data-filter="5bed" ok-sel=".5bed"><?php _e( '5 Bedroom', 'streamline-core' ) ?></a></li>
                    <li><a ng-click="filterBedrooms(6)" data-filter="6bed" ok-sel=".6bed"><?php _e( '6 Bedroom', 'streamline-core' ) ?></a></li>

                </ul>
                <ul class="filter2" ng-init="getLocations()" opt-kind="" ok-key="filter">
                    <li><a ng-click="filterLocation(0)" data-filter="" ok-sel="*"><?php _e( 'All Properties', 'streamline-core' ) ?></a></li>
                    <li ng-repeat="location in locations">
                        <a href="#" data-filter="loc{[location.id]}" ok-sel=".loc{[location.id]}"
                           ng-click="filterLocation(location.id)">{[location.name]}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row propertyListings" isotope-container="isotope-container"
         ng-init="requestPropertyList('GetPropertyListWordPress');search.start_date='<?php echo $_GET['sd']; ?>';search.end_date='<?php echo $_GET['ed']; ?>'">
        <div class="col-md-4 {[property.bedrooms_number]}bed loc{[property.location_area_id]}"
             ng-show="properties.length"
             dir-paginate="property in properties | itemsPerPage: 100"
             isotope-item>
            <div class="property">
                <div class="propertyInfo">
                    <div class="propertyTitle">
                        <a href="/{[property.seo_page_name]}">{[property.name]}</a>
                    </div>
                    <div class="propertyLocation">{[property.location_name]}</div>
                    <div class="propertyDescription" ng-if="!isEmptyObject(property.short_description)" ng-bind-html="property.short_description | trustedHtml"></div>
                    <div class="propertyPets"><?php _e( 'Pet Friendly Property', 'streamline-core' ) ?></div>
                </div>
                <div class="propertyPhoto">
                    <a href="/{[property.seo_page_name]}">
                        <img ng-src="{[property.default_thumbnail_path]}"
                             err-src="<?php ResortPro::assets_url('images/dummy-image.jpg'); ?>"
                             alt="{[property.location_name]}"/>
                    </a>
                </div>

                <div class="propertyInfo2">
                    <div class="propertySleeps"><?php _e( 'Sleeps', 'streamline-core' ) ?><span>{[property.bathrooms_number]}</span></div>
                    <div class="propertyBedrooms"><?php _e( 'Bedrooms', 'streamline-core' ) ?><span>{[property.bedrooms_number]}</span></div>
                    <div class="propertyCost"><?php _e( 'From', 'streamline-core' ) ?><span>{[property.price_data.daily | currency]}</span></div>
                </div>
            </div>

        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <nav>
                <!-- Add class .pagination-lg for larger blocks or .pagination-sm for smaller blocks-->
                <ul class="pagination">
                    <li><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li><a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php get_footer(); ?>
