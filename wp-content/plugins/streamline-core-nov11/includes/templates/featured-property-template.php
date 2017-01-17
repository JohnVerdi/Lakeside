<?php
/**
 * The template for displaying featured properties from the [resortpro-featured-properties] shortcode
 *
 * This template can be overridden by copying it to yourtheme/streamline_templates/featured-property.php.
 *
 *
 * @author  Streamline
 * @package StreamlineCore/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="col-sm-12 row-spac-2 col-md-6 col-sm-6 col-xs-12 grid-1">
    <div class="property">
        <div class="propertyInfo">
            <div class="propertyTitle row">
                <div class="col-md-7">
                    <a href="<?php echo $property_link ?>"><?php echo $unit['location_name'] ?></a>

                    <div class="propertyLocation"><?php echo $unit['location_area_name']; ?></div>
                    <div class="propertyDescription"><?php echo $unit['short_description']; ?></div>
                    <?php if($unit['max_pets'] > 0): ?>
                    <div class="propertyPets"><?php echo apply_filters( 'streamline-featured-pet-friendly', __('Pet Friendly Property', 'streamline-core') ); ?></div>
                <?php endif; ?>
                </div>
                <div class="col-md-5">
                    <div class="star-rating">
                        <div style="display: inline-block" class="star-rating text-right" star-rating
                             rating-value="<?php echo $unit['rating_average']; ?>" data-max="5"></div>
                        <p style="display:inline-block; vertical-align: top">(<?php echo $unit['rating_count']; ?> <?php _e( 'reviews', 'streamline-core' ) ?>)</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="propertyPhoto">
            <a href="<?php echo $property_link ?>">
                <img src="<?php echo $unit['default_thumbnail_path']; ?>"
                    alt="<?php echo $unit['location_name'] ?>"/>
            </a>
        </div>
        <div class="propertyInfo2">
            <div class="propertySleeps"><?php echo apply_filters( 'streamline-featured-sleeps', __('Sleeps', 'streamline-core') ); ?><span> <?php echo $unit['max_occupants']?></span></div>
            <div class="propertyBedrooms"><?php echo apply_filters( 'streamline-featured-bedrooms', __('Bedrooms', 'streamline-core') ); ?><span> <?php echo $unit['bedrooms_number'] ?></span></div>
            <div class="propertyCost">
            <?php echo apply_filters( 'streamline-featured-from', __('From', 'streamline-core') ); ?><span> $<?php echo number_format($unit['price_data']['daily'],0); ?></span></div>
        </div>
    </div>
</div>
