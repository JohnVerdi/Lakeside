<?php
    if(function_exists('vc_map')){
        vc_map( array(
            "name" => __("ST Tab", ST_TEXTDOMAIN),
            "base" => "st_tab",
            "as_parent" => array('only' => 'st_tab_item'),
            "content_element" => true,
            "show_settings_on_create" => false,
            "js_view" => 'VcColumnView',
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params" => array(
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Element style", ST_TEXTDOMAIN),
                    "param_name" => "style",
                    "description" =>"",
                    'value'=>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Tour box style (Vertical ) ',ST_TEXTDOMAIN)=>'vertical',
                        __('Tour box style (Horizontal ) ',ST_TEXTDOMAIN)=>'horizontal',
                    ),
                ),
            )
        ) );
        vc_map( array(
            "name" => __("ST Tab item", ST_TEXTDOMAIN),
            "base" => "st_tab_item",
            "content_element" => true,
            "as_child" => array('only' => 'st_tab'),
            "icon" => "icon-st",
            "params" => array(
                // add params same as with any other content element
                array(
                    "type" => "textfield",
                    "heading" => __("Title", ST_TEXTDOMAIN),
                    "param_name" => "st_title",
                    "description" =>"",
                ),
                array(
                    "type" => "textfield",
                    "heading" => __("icon", ST_TEXTDOMAIN),
                    "param_name" => "st_icon",
                    "description" =>"Example: fa fa-home". "<a href='https://fortawesome.github.io/Font-Awesome/icons/'> Read more</a>",
                ),
                array(
                    "type" => "textarea_html",
                    "heading" => __("Content", ST_TEXTDOMAIN),
                    "param_name" => "content",
                    "description" =>"",
                ),
            )
        ) );
    }

    if ( class_exists( 'WPBakeryShortCodesContainer' ) and !class_exists('WPBakeryShortCode_st_tab')) {
        class WPBakeryShortCode_st_tab extends WPBakeryShortCodesContainer {
            protected function content($arg, $content = null) {
                extract(wp_parse_args($arg,array('style'=>'')));
                $r ="";
                global $st_title_tb;$st_title_tb="";
                global $i_tab;$i_tab=0;
                global $id_rand ; $id_rand = rand();
                $content_data= st_remove_wpautop($content);
                $id = rand();
                if ($style == 'vertical'){
                    $style.= " st_tour_ver" ;
                    $r .= '<div class="st_tab '.$style.' tabbable st_tab_'.esc_attr($id_rand).'" style="">
                            <div class="row">
                                <div class="col-md-3 col-xs-12">
                                    <ul id="myTab'.$id.'" class="nav nav-tabs myTab">
                                      '.$st_title_tb.'
                                    </ul>
                                </div>
                                <div class="col-md-9 col-xs-12">
                                    <div id="myTabContent'.$id.'" class="tab-content">
                                      '.$content_data.'
                                    </div>
                                </div>
                            </div>
                        </div>';
                    return $r;
                }
                if (!empty($style)) $style.= " st_tour_ver" ;
                $r .= '<div class="st_tab '.$style.' tabbable st_tab_'.esc_attr($id_rand).'" style="">
                            <ul id="myTab'.$id.'" class="nav nav-tabs myTab">
                              '.$st_title_tb.'
                            </ul>
                            <div id="myTabContent'.$id.'" class="tab-content">
                              '.$content_data.'
                            </div>
                        </div>';
                return $r;
            }
        }
    }
    if ( class_exists( 'WPBakeryShortCode' ) and !class_exists('WPBakeryShortCode_st_tab_item') ) {
        class WPBakeryShortCode_st_tab_item extends WPBakeryShortCode {
            protected function content($arg, $content = null) {
                global $st_title_tb;
                global $i_tab;
                global $id_rand;
                $data = shortcode_atts(array(
                    'st_title' =>"",
                    'st_icon'   => "",
                ), $arg,'st_tab_item');
                extract($data);
                if (!empty($st_icon)) {$st_icon = "<i class='fa ".$st_icon."'></i>";}
                if($i_tab == '0'){
                    $class_active = "active";
                }else{
                    $class_active="";
                }
                $st_title_tb .= '<li class="'.esc_attr($class_active).'">
                               <a href="#tab-'.esc_attr($id_rand).'-'.esc_attr($i_tab).'" data-toggle="tab">'.$st_icon.' '.$st_title.'</a>
                             </li>';
                $text = '<div class="tab-pane fade '.esc_attr($class_active).' in " id="tab-'.esc_attr($id_rand).'-'.esc_attr($i_tab).'">
                     '.do_shortcode($content).'
                     </div>';
                $i_tab++;
                return $text;
            }
        }
    }