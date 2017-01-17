<?php
if(function_exists('vc_map')){
    vc_map( array(
        "name" => __("ST Rating Count", ST_TEXTDOMAIN),
        "base" => "st_rating_count",
        "content_element" => true,
        "icon" => "icon-st",
        "category"=>"Shinetheme",
        "params" => array(
            array(
                "type" => "textfield",
                "holder" => "div",
                "heading" => __("Icon ", ST_TEXTDOMAIN),
                "param_name" => "st_icon",
                "description" =>"",
                'value'=>'fa-flag',
                'edit_field_class'=>'vc_col-sm-12',
            ),
        )
    ) );
}

if(!function_exists('st_vc_rating_count')){
    function st_vc_rating_count($attr)
    {
        extract(wp_parse_args($attr,array('st_icon'=>'fa-flag')));
        $return ="<div class='st_tour_ver st_rating_count'>";
        $return .="<i class='color-main fa ".$st_icon."'></i>";
        $avg=STReview::get_avg_rate();
        $return .= "<div class='st_rating_count_inner main-color text-right'><b>"; // start inner
        if($avg<=4){
            $return .= __('Good', ST_TEXTDOMAIN) ;
        }elseif($avg<=5){
            $return .= __('Best', ST_TEXTDOMAIN) ;
        }
        $return .= " ".esc_attr($avg*2)."<small>/10</small>";
        $return .= "</b><br>";
        $reviews = STReview::count_comment(get_the_ID(),'st_reviews');
        if ($reviews==1) $return .= "<i>". sprintf("from %s review",$reviews, ST_TEXTDOMAIN)."</i>";
        if ($reviews>1) $return .= "<i>". sprintf("from %s reviews",$reviews, ST_TEXTDOMAIN)."</i>";
        $return .= "</div>"; // end inner
        $return .="</div>";
        return $return ;
    }
}
st_reg_shortcode('st_rating_count','st_vc_rating_count');