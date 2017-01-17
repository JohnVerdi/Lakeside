<?php
if(function_exists('vc_map')){
    vc_map( array(
        "name" => __("ST List Review", ST_TEXTDOMAIN),
        "base" => "st_list_review",
        "content_element" => true,
        "icon" => "icon-st",
        "params" => array(
            array(
                "type" => "dropdown",
                "heading" => __("Style", ST_TEXTDOMAIN),
                "param_name" => "st_style",
                "description" =>"",
                'value'            => array(
                    __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                    __( 'Default' , ST_TEXTDOMAIN )        => 'html' ,
                   /* __( 'Tour box style' , ST_TEXTDOMAIN )       => 'st_tour_ver'*/
                ) ,
            ),
            array(
                "type" => "textfield",
                "heading" => __("Max Number", ST_TEXTDOMAIN),
                "param_name" => "number",
                "description" =>"",
            ),
            array(
                "type" => "textfield",
                "heading" => __("Max length", ST_TEXTDOMAIN),
                "param_name" => "st_max_len",
                "description" =>"",
            ),
        )
    ) );
}

if(!function_exists('st_vc_st_list_review')){
    function st_vc_st_list_review($arg,$content=false)
    {
        if(is_singular()){
            global $st_list_review_number;
            global $st_max_len;$st_max_len =100;
            $data = shortcode_atts(array(
                'st_style'=>'html',
                'number' =>5,
                'st_max_len'=> 150,
            ), $arg, 'st_list_review' );
            extract($data);
            $st_list_review_number = $number;

            ob_start();
            comments_template( '/st_templates/vc-elements/st-list-review/'.$st_style.'.php' );
            $html = @ob_get_clean();
            return $html;
        }

    }
}
st_reg_shortcode('st_list_review','st_vc_st_list_review');
