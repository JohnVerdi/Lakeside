<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 12/15/14
 * Time: 9:44 AM
 */
if(!st_check_service_available( 'st_activity' )) {
   return;
}
/**
* ST Thumbnail Activity
* @since 1.1.0
**/
if(function_exists('vc_map')){
    vc_map(
        array(
            'name' => __("ST Activity Thumbnail", ST_TEXTDOMAIN),
            'base' => 'st_thumbnail_activity',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => false,
            'params'=>array()
        )
    );
}
if(!function_exists('st_thumbnail_activity_func'))
{
    function st_thumbnail_activity_func()
    {
        if(is_singular('st_activity'))
        {
            return st()->load_template('activity/elements/image','featured');
        }
    }

    st_reg_shortcode('st_thumbnail_activity','st_thumbnail_activity_func');
}

/**
* ST Form Book
* @since 1.1.0
**/
if(function_exists('vc_map')){
    vc_map(
        array(
            'name' => __("ST Activity Booking Form", ST_TEXTDOMAIN),
            'base' => 'st_form_book',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => false,
            'params'=>array()
        )
    );
}
if(!function_exists('st_form_book_func'))
{
    function st_form_book_func()
    {
        if(is_singular('st_activity'))
        {
            return st()->load_template('activity/elements/form','book');
        }
    }
    st_reg_shortcode('st_form_book','st_form_book_func');
}

/**
* ST Excerpt Activity
* @since 1.1.0
**/
if(function_exists('vc_map')){
    vc_map(
        array(
            'name' => __("ST Activity Excerpt", ST_TEXTDOMAIN),
            'base' => 'st_excerpt_activity',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => false,
            'params'=>array()
        )
    );
}

if(!function_exists('st_excerpt_activity_func'))
{
    function st_excerpt_activity_func()
    {
        if(is_singular('st_activity'))
        {
            while(have_posts())
            {
                the_post();
                return '<blockquote class="center">'.get_the_excerpt()."</blockquote>";
            }

        }
    }
    st_reg_shortcode('st_excerpt_activity','st_excerpt_activity_func');
}

/**
* ST Activity Content
* @since 1.1.0
**/
if(function_exists('vc_map')){
    vc_map(
        array(
            'name' => __("ST Activity Content", ST_TEXTDOMAIN),
            'base' => 'st_activity_content',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => false,
            'params'=>array()
        )
    );
}
if(!function_exists('st_activity_content_func'))
{
    function st_activity_content_func()
    {
        if(is_singular('st_activity'))
        {
             return st()->load_template('activity/elements/content','activity');
        }
    }
    st_reg_shortcode('st_activity_content','st_activity_content_func');
}

/**
* ST Activity Detail Map
* @since 1.1.0
**/
if(function_exists('vc_map')){
    vc_map(
        array(
            'name' => __("ST Detailed Activity Map", ST_TEXTDOMAIN),
            'base' => 'st_activity_detail_map',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => true,
            'params'=>array(
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Range" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "range" ,
                    "description" => "Km" ,
                    "value"       => "20" ,
                ) ,
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Number" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "number" ,
                    "description" => "" ,
                    "value"       => "12" ,
                ) ,
                array(
                    "type"        => "dropdown" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Show Circle" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "show_circle" ,
                    "description" => "" ,
                    "value"       => array(
                        __( "No" , ST_TEXTDOMAIN )  => "no" ,
                        __( "Yes" , ST_TEXTDOMAIN ) => "yes"
                    ) ,
                )
            )
        )
    );
}

if(!function_exists('st_activity_detail_map'))
{
    function st_activity_detail_map($attr)
    {
        if(is_singular('st_activity')) {

            $default = array(
                'number'      => '12' ,
                'range'       => '20' ,
                'show_circle' => 'no' ,
            );
			$dump = wp_parse_args( $attr , $default);
            extract( $dump  );
            $lat   = get_post_meta( get_the_ID() , 'map_lat' , true );
            $lng   = get_post_meta( get_the_ID() , 'map_lng' , true );
            $zoom  = get_post_meta( get_the_ID() , 'map_zoom' , true );
            $class = STActivity::inst();
            $data  = $class->get_near_by( get_the_ID() , $range , $number );
            $location_center                     = '[' . $lat . ',' . $lng . ']';
            $data_map                            = array();
            $data_map[ 0 ][ 'id' ]               = get_the_ID();
            $data_map[ 0 ][ 'name' ]             = get_the_title();
            $data_map[ 0 ][ 'post_type' ]        = get_post_type();
            $data_map[ 0 ][ 'lat' ]              = $lat;
            $data_map[ 0 ][ 'lng' ]              = $lng;
            $data_map[ 0 ][ 'icon_mk' ]          = get_template_directory_uri() . '/img/mk-single.png';
            $data_map[ 0 ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/activity' , false , array( 'post_type' => '' ) ) );
            $data_map[ 0 ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/activity' , false , array( 'post_type' => '' ) ) );
            $stt                                 = 1;
            global $post;
            if(!empty( $data )) {
                foreach( $data as $post ) :
                    setup_postdata( $post );
                    $map_lat = get_post_meta( get_the_ID() , 'map_lat' , true );
                    $map_lng = get_post_meta( get_the_ID() , 'map_lng' , true );
                    if(!empty( $map_lat ) and !empty( $map_lng ) and is_numeric( $map_lat ) and is_numeric( $map_lng )) {
                        $post_type                              = get_post_type();
                        $data_map[ $stt ][ 'id' ]               = get_the_ID();
                        $data_map[ $stt ][ 'name' ]             = get_the_title();
                        $data_map[ $stt ][ 'post_type' ]        = $post_type;
                        $data_map[ $stt ][ 'lat' ]              = $map_lat;
                        $data_map[ $stt ][ 'lng' ]              = $map_lng;
                        $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_activity_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_yellow.png' );
                        $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/activity' , false , array( 'post_type' => '' ) ) );
                        $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/activity' , false , array( 'post_type' => '' ) ) );
                        $stt++;
                    }
                endforeach;
                wp_reset_postdata();
            }
            if($location_center == '[,]')
                $location_center = '[0,0]';
            if($show_circle == 'no') {
                $range = 0;
            }
            $data_tmp               = array(
                'location_center' => $location_center ,
                'zoom'            => $zoom ,
                'data_map'        => $data_map ,
                'height'          => 500 ,
                'style_map'       => 'normal' ,
                'number'          => $number ,
                'range'           => $range ,
            );
            $data_tmp[ 'data_tmp' ] = $data_tmp;
            $html                   = '<div class="map_single">'.st()->load_template( 'hotel/elements/detail' , 'map' , $data_tmp ).'</div>';
            return $html;

        }
    }
    st_reg_shortcode('st_activity_detail_map','st_activity_detail_map');
}

/**
* ST Activity Detail Review Summary
* @since 1.1.0
**/
if(function_exists('vc_map')){
    vc_map(
        array(
            'name' => __("ST Activity Review Summary", ST_TEXTDOMAIN),
            'base' => 'st_activity_detail_review_summary',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => false,
            'params'=>array()
        )
    );
}

if(!function_exists('st_activity_detail_review_summary'))
{
    function st_activity_detail_review_summary()
    {

        if(is_singular('st_activity'))
        {
            return st()->load_template('activity/elements/review_summary');
        }
    }
    st_reg_shortcode('st_activity_detail_review_summary','st_activity_detail_review_summary');
}

/**
* ST Activity Detail Review Detail
* @since 1.1.0
**/
if(function_exists('vc_map')){
    vc_map(
        array(
            'name' => __("ST Detailed Activity Review", ST_TEXTDOMAIN),
            'base' => 'st_activity_detail_review_detail',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => false,
            'params'=>array()
        )
    );
}

if(!function_exists('st_activity_detail_review_detail'))
{
    function st_activity_detail_review_detail()
    {
        if(is_singular('st_activity'))
        {
            return st()->load_template('activity/elements/review_detail');
        }
    }
    st_reg_shortcode('st_activity_detail_review_detail','st_activity_detail_review_detail');
}

/**
* ST Activity Review
* @since 1.1.0
**/
if(function_exists('vc_map')){
    vc_map(
        array(
            'name' => __("ST Activity Review", ST_TEXTDOMAIN),
            'base' => 'st_activity_review',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => true,
            'params'=>array(
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "title" ,
                    "description" => "" ,
                    "value"       => "",
                    'edit_field_class'=>'vc_col-sm-6',
                ) ,
                array(
                    "type"        => "dropdown" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "font_size" ,
                    "description" => "" ,
                    "value"       => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ),
                    'edit_field_class'=>'vc_col-sm-6',
                ) ,
            )
        )
    );
}
if(!function_exists('st_activity_review'))
{
    function st_activity_review($attr = array())
    {
        $default = array(
            'title'   => '' ,
            'font_size'   => '3' ,
        );
        extract( wp_parse_args( $attr , $default ) );
        if(is_singular('st_activity'))
        {
            if(comments_open() and st()->get_option('activity_review')!='off')
            {
                ob_start();
                    comments_template('/reviews/reviews.php');
                $html =  @ob_get_clean();
                if(!empty($title) and !empty($html)){
                    $html = '<h'.$font_size.'>'.$title.'</h'.$font_size.'>'.$html;
                }
                return $html;
            }
        }
    }
}
st_reg_shortcode('st_activity_review','st_activity_review');

/**
* ST Activity Video
* @since 1.1.0
**/
if(function_exists('vc_map')){
    vc_map(
        array(
            'name' => __("ST Activity Video", ST_TEXTDOMAIN),
            'base' => 'st_activity_video',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => false,
            'params'=>array()
        )
    );
}

if(!function_exists('st_activity_video'))
{
    function st_activity_video($attr=array())
    {
        if(is_singular('st_activity'))
        {
            if($video=get_post_meta(get_the_ID(),'video',true)){
                return "<div class='media-responsive'>".wp_oembed_get($video)."</div>";
            }
        }
    }
    st_reg_shortcode('st_activity_video','st_activity_video');
}

/**
* ST Activity Nearby
* @since 1.1.0
**/
if(function_exists('vc_map')){
    vc_map(
        array(
            'name' => __("ST Activity Nearby", ST_TEXTDOMAIN),
            'base' => 'st_activity_nearby',
            'content_element' => true,
            'icon' => 'icon-st',
            'category' => 'Activity',
            'show_settings_on_create' => true,
            'params'=>array(
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Title" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "title" ,
                    "description" => "" ,
                    "value"       => "",
                    'edit_field_class'=>'vc_col-sm-6',
                ) ,
                array(
                    "type"        => "dropdown" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "font_size" ,
                    "description" => "" ,
                    "value"       => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ),
                    'edit_field_class'=>'vc_col-sm-6',
                ) ,
            )
        )
    );
}
if(!function_exists('st_activity_nearby'))
{
    function st_activity_nearby($attr=array())
    {
        $default = array(
            'title'   => '' ,
            'font_size'   => '3' ,
        );
		$data= wp_parse_args( $attr , $default );
        extract(  wp_parse_args( $attr , $default ) );
        if(is_singular('st_activity'))
        {
            $html = st()->load_template('activity/elements/nearby',false,$data);

            return $html;
        }
    }
    st_reg_shortcode('st_activity_nearby','st_activity_nearby');
}

/**
 * ST activity show discount
 * @since 1.1.9
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( 'ST Activity Show Discount' , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_activity_show_discount' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Activity' ,
            'show_settings_on_create' => false ,
            'params'                  => array()
        )
    );
}


if(!function_exists( 'st_activity_show_discount' )) {
    function st_activity_show_discount()
    {
        if(is_singular( 'st_activity' )) {
            return st()->load_template( 'activity/elements/activity_show_info_discount' );
        }
    }
}
st_reg_shortcode( 'st_activity_show_discount' , 'st_activity_show_discount' );