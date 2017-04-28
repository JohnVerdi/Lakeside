<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Cars single default
 *
 * Created by ShineTheme
 *
 */
?>
<style>.vc_custom_1419214287417{padding-top: 30px !important;}</style>
<?php
echo do_shortcode('
[vc_row]
[vc_column width="9/12"]
[vc_row_inner]
[vc_column_inner width="5/12"]
[vc_column_text]
[st_thumbnail_cars /]
[/vc_column_text]
[/vc_column_inner]
[vc_column_inner width="7/12"]
[st_cars_price st_style="2"]
[/vc_column_inner]
[/vc_row_inner]
[vc_column_text]
[st_excerpt_cars /]
[/vc_column_text]
[vc_row_inner css=".vc_custom_1419214287417{padding-top: 30px !important;}"]
[vc_column_inner width="1/3"]
[st_cars_attribute taxonomy="car_features"]
[/vc_column_inner]
[vc_column_inner width="1/3"]
[st_cars_attribute taxonomy="default_equipment"]
[/vc_column_inner]
[vc_column_inner width="1/3" el_class="booking-item-features-dark"]
[st_cars_attribute taxonomy="st_cars_pickup_features"]
[/vc_column_inner]
[/vc_row_inner]
[/vc_column]
[vc_column width="3/12"]
[vc_column_text]
[st_detail_date_location_cars /]
[/vc_column_text]
[/vc_column]
[/vc_row]');
?>

