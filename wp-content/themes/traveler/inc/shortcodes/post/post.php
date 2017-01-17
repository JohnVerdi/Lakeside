<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 12/15/14
 * Time: 11:23 AM
 */

if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( "ST Post Data" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_post_data' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Shinetheme' ,
            'show_settings_on_create' => true ,
            "params"                  => array(
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
                array(
                    "type"        => "dropdown" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Data Type" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "field" ,
                    "description" => "" ,
                    "value"       => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "Title" , ST_TEXTDOMAIN )   => 'title' ,
                        __( "Content" , ST_TEXTDOMAIN ) => 'content' ,
                        __( "Excerpt" , ST_TEXTDOMAIN ) => 'excerpt' ,
                        __( "Thumbnail" , ST_TEXTDOMAIN ) => 'thumbnail' ,
                    )
                ) ,

                array(
                    'type'        => 'dropdown',
                    'holder'      => "div" , 
                    'heading'     => __("Thumbnail size " , ST_TEXTDOMAIN),
                    'param_name'  => "thumb_size",
                    'description' => "",
                    "value"       => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "Thumbnail" , ST_TEXTDOMAIN ) => 'thumbnail' ,
                        __( "Medium" , ST_TEXTDOMAIN ) => 'medium' ,
                        __( "Large" , ST_TEXTDOMAIN ) => 'large' ,
                        __( "Full" , ST_TEXTDOMAIN ) => 'full' ,
                        ),
                    'dependency'    => array(
                        'element'   => "field",
                        'value'     => 'thumbnail'
                        )
                    ),

            ) ,
        )
    );
}
if(!function_exists( 'st_post_data' )) {
    function st_post_data( $attr = array() )
    {
        $default = array(
            'title'   => '' ,
            'font_size'   => '3' ,
            'field'   => 'title' ,
            'post_id' => false,
            'thumb_size'=> 'thumbnail'
        );
        extract( wp_parse_args( $attr , $default ) );

        if(!$post_id and is_single()) {
            $post_id = get_the_ID();
        }

        if($post_id and is_single()) {
            $content = '';
            switch( $field ) {
                case "content":
                    $post = get_post( $post_id );
                    $content .= $post->post_content;
                    $content = apply_filters( 'the_content' , $content );
                    $content = str_replace( ']]>' , ']]&gt;' , $content );
                    if(!empty( $title ) and !empty( $content )) {
                        $content = '<h'.$font_size.'>' . $title . '</h'.$font_size.'>' . $content;
                    }
                    break;
                case "excerpt":
                    $post = get_post( $post_id );
                    if(isset( $post->post_excerpt )) {
                        $content .= $post->post_excerpt;
                    }
                    if(!empty( $title ) and !empty( $content )) {
                        $content = '<h'.$font_size.'>' . $title . '</h'.$font_size.'>' . $content;
                    }
                    break;
                case "title":
                    $content = get_the_title( $post_id );
                    if(!empty( $title ) and !empty( $content )) {
                        $content = '<h'.$font_size.'>' . $title . '</h'.$font_size.'>' . $content;
                    }
                    break;
                case "thumbnail":
                    if(has_post_thumbnail($post_id)){
                        $content .= get_the_post_thumbnail($post_id , $thumb_size , array("class"=> "st_post_data_thumb"));
                    }                     
            }
            return $content;
        }

    }
}

st_reg_shortcode( 'st_post_data' , 'st_post_data' );

if(!function_exists( 'st_post_share' )) {
    function st_post_share()
    {
        return '<div class="package-info tour_share" style="clear: both;text-align: right">
            ' . st()->load_template( 'hotel/share' ) . '
        </div>';
    }
}
st_reg_shortcode( 'st_post_share' , 'st_post_share' );

