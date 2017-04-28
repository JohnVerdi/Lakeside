<?php
if (function_exists('vc_map')) {
    vc_map(array(
        "name" => __("ST Breadcrumb", ST_TEXTDOMAIN),
        "base" => "st_breadcrumb",
        "content_element" => true,
        "icon" => "icon-st",
        "category"=>"Shinetheme",
        "params" => array(
            /*array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Element style", ST_TEXTDOMAIN),
                "param_name" => "style",
                "description" =>"",
                'value'=>array(
                    __('--Default--',ST_TEXTDOMAIN)=>'',
                    __('Tour box Light style ',ST_TEXTDOMAIN)=>'bc_tour_box_light',
                    __('Tour box Dark style',ST_TEXTDOMAIN)=>'bc_tour_box_dark',
                ),
            ),*/
        )
    ));
}
if (!function_exists('st_breadcrumb_fn')){
    function st_breadcrumb_fn($attr){
        ?>
        <div class="container">
            <div class="breadcrumb">
                <?php st_breadcrumbs(); ?>
            </div>
        </div>
        <?php
    }
    st_reg_shortcode('st_breadcrumb','st_breadcrumb_fn');
}