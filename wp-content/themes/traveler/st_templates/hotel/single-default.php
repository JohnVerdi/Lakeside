<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * hotel single default
 *
 * Created by ShineTheme
 *
 */
wp_enqueue_script('wpb_composer_front_js');
?>
    <div class="row" style="margin-top: 15px;margin-bottom:  20px;">
        <div class="col-sm-9">
            <?php  echo do_shortcode('[st_hotel_header]');?>

        </div>
        <div class="col-sm-3">
            <?php  echo do_shortcode('[st_hotel_price]');?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?php  echo do_shortcode('
                [vc_tabs]
                [vc_tab title="'.__("Photos", ST_TEXTDOMAIN) .'" tab_id="1418609998892-1-5" tab_icon="fa-camera"]
                [st_hotel_detail_photo style="slide"]
                [/vc_tab]
                [vc_tab title="'.__("On the Map", ST_TEXTDOMAIN) .'" tab_id="1418376646-2-22" tab_icon="fa-map-marker"]
                [vc_column_text][st_hotel_detail_map][/vc_column_text]
                [/vc_tab]
                [vc_tab title="'.__("Video", ST_TEXTDOMAIN) .'" tab_id="1419221514302-2-1" tab_icon="fa-youtube-play"]
                [vc_column_text]
                [st_hotel_video]
                [/vc_column_text]
                [/vc_tab]
                [/vc_tabs]');?>

        </div>
        <div class="col-sm-6">
            <div style="margin-top: 40px;">
                <div class="mb20">
                    <?php echo do_shortcode('[st_hotel_detail_review_summary]')?>
                </div>
                <?php echo do_shortcode('[st_hotel_detail_review_detail]')?>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 60px;">
        <div class="col-sm-12">
            <h3><?php st_the_language('available_rooms')?></h3>
        </div>
        <div class="col-sm-9">
            <div class="mb20">
                <?php echo do_shortcode('[st_hotel_detail_search_room]')?>
            </div>
            <?php echo do_shortcode('[st_hotel_detail_list_rooms]')?>
        </div>
        <div class="col-sm-3" style="margin-bottom: 20px;">
            <h4><?php st_the_language('about_hotel') ?></h4>
            <?php echo do_shortcode('[st_hotel_logo]
[st_post_data field=excerpt]
[st_hotel_detail_attribute taxonomy="hotel_facilities"]
')?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <h3><?php st_the_language('hotel_reviews')?></h3>
        </div>
        <div class="col-sm-8">
            <?php echo do_shortcode('[st_hotel_review]')?>
        </div>
        <div class="col-sm-4">
            <?php echo do_shortcode('[st_hotel_nearby]')?>
        </div>
    </div>
<?php
