<?php
if (function_exists('vc_map')){
    vc_map(array(
        "name"            => __( "ST Tour Cards Accepted" , ST_TEXTDOMAIN ) ,
        "base"            => "st_tour_card_accepted" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => 'Shinetheme' ,
    ));
}
if (!function_exists('st_tour_card_accepted_fn')){
    function st_tour_card_accepted_fn($attr){
        $cards = st()->get_option('tour_cards_accept',"");
        $html = "<div class='st_tour_card_accepted'>";
        $html .= __("We accepted ", ST_TEXTDOMAIN);
        if (!empty($cards) and is_array($cards)){
            foreach ($cards as $items ){
                extract(wp_parse_args($items,array('title'=>"#" , 'image'=>"#",'link'=> "#")));
                if (!empty($image) and !empty($link)){
                    $html .="<a href='".$link."'><img height='28' width='auto' alt='".$title."' src='".$image."'/></a>";
                }
            }
        }
        $html .="</div>";
        return $html ;
    }

}
st_reg_shortcode('st_tour_card_accepted' , 'st_tour_card_accepted_fn');