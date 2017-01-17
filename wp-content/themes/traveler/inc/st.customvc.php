<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Custom VC element
 *
 * Created by ShineTheme
 *
 */

if(function_exists('vc_add_shortcode_param') and class_exists( 'Vc_Manager' )) {
    if(!function_exists( 'st_css_classes_for_vc_row_and_vc_column' )) {
        function st_css_classes_for_vc_row_and_vc_column( $class_string , $tag )
        {
            if($tag == 'vc_row' || $tag == 'vc_row_inner') {
                $class_string = str_replace( 'vc_row-fluid' , '' , $class_string );
            }
            if(defined( 'WPB_VC_VERSION' )) {
                if(version_compare( WPB_VC_VERSION , '4.2.3' , '>' )) {
                    if($tag == 'vc_column' || $tag == 'vc_column_inner') {
                        //$class_string = preg_replace('/vc_span(\d{1,2})/', 'col-lg-$1', $class_string);
                        $class_string = str_replace( 'vc_col' , 'col' , $class_string );
                        $class_string = str_replace( 'col-sm' , 'col-md' , $class_string );
                        $class_string = str_replace( 'col-md2' , 'col-sm' , $class_string );
                    }
                } else {
                    if($tag == 'vc_column' || $tag == 'vc_column_inner') {
                        $class_string = preg_replace( '/vc_span(\d{1,2})/' , 'col-lg-$1' , $class_string );
                        $class_string = str_replace( 'col-sm' , 'col-md' , $class_string );
                        $class_string = str_replace( 'col-md2' , 'col-sm' , $class_string );
                    }
                }
            }
            return $class_string;
        }

    }
// Filter to Replace default css class for vc_row shortcode and vc_column
    add_filter( 'vc_shortcodes_css_class' , 'st_css_classes_for_vc_row_and_vc_column' , 10 , 2 );
// Add new Param in Row
    if(function_exists( 'vc_add_param' )) {
        vc_add_param( 'vc_row' , array(
                "type"       => "dropdown" ,
                "heading"    => __( 'Full Width' , ST_TEXTDOMAIN ) ,
                "param_name" => "row_fullwidth" ,
                "value"      => array(
                    __( 'No' , ST_TEXTDOMAIN )  => 'no' ,
                    __( 'Yes' , ST_TEXTDOMAIN ) => 'yes' ,
                ) ,
            )
        );
        vc_add_param( 'vc_row' , array(
                "type"        => "dropdown" ,
                "heading"     => __( 'Parallax' , ST_TEXTDOMAIN ) ,
                "param_name"  => "parallax_class" ,
                "value"       => array(
                    __( 'No' , ST_TEXTDOMAIN )  => 'no' ,
                    __( 'Yes' , ST_TEXTDOMAIN ) => 'yes' ,
                ) ,
                "description" => __( "You can choose yes to display Parallax Effect" , ST_TEXTDOMAIN ) ,
            )
        );
        vc_add_param( 'vc_row' , array(
                "type"       => "dropdown" ,
                "heading"    => __( 'Background Video' , ST_TEXTDOMAIN ) ,
                "param_name" => "bg_video" ,
                "value"      => array(
                    __( 'No' , ST_TEXTDOMAIN )  => 'no' ,
                    __( 'Yes' , ST_TEXTDOMAIN ) => 'yes' ,
                ) ,
            )
        );
        vc_add_param( 'vc_row' , array(
                "type"       => "st_poster_media" ,
                "heading"    => __( 'Poster Media video' , ST_TEXTDOMAIN ) ,
                "param_name" => "st_poster_media" ,
            )
        );
        vc_add_param( 'vc_row' , array(
                "type"       => "st_media" ,
                "heading"    => __( 'Media video' , ST_TEXTDOMAIN ) ,
                "param_name" => "st_media" ,
            )
        );
        vc_add_param( 'vc_row' , array(
                "type"        => "textfield" ,
                "heading"     => __( 'Input ID element' , ST_TEXTDOMAIN ) ,
                "param_name"  => "row_id" ,
                "value"       => '' ,
                "description" => __( "Row ID" , ST_TEXTDOMAIN ) ,
            )
        );
    }
    if(!function_exists( 'st_media_settings_field' )) {
        function st_media_settings_field( $settings , $value )
        {
            $dependency = '';//vc_generate_dependencies_attributes($settings);
            return '<div class="my_param_block">'
            . '<input id="st_url_media" name="' . $settings[ 'param_name' ]
            . '" class="wpb_vc_param_value wpb-textinput '
            . $settings[ 'param_name' ] . ' ' . $settings[ 'type' ] . '_field" type="text" value="'
            . $value . '" ' . $dependency . '/>'
            . '</div>'
            . '<button id="btn_add_media" class="vc_btn vc_panel-btn-save vc_btn-primary">'.__("Upload",ST_TEXTDOMAIN).'</button>' // New button element
            . "<script>
        jQuery(document).ready(function($){
            var _custom_media = true,
                _orig_send_attachment = wp.media.editor.send.attachment;
            $('#btn_add_media').click(function(e) {
                var send_attachment_bkp = wp.media.editor.send.attachment;
                var button = $(this);

                _custom_media = true;
                wp.media.editor.send.attachment = function(props, attachment){
                    if ( _custom_media ) {
                        $('#st_url_media').val(attachment.url);
                    } else {
                        return _orig_send_attachment.apply( this, [props, attachment] );
                    };
                }
                wp.media.editor.open(button);
                return false;
            });
            $('.add_poster_media').on('click', function(){
                _custom_media = false;
            });
        });
    </script>";
        }
    }
    if(!function_exists( 'st_poster_media_settings_field' )) {
        function st_poster_media_settings_field( $settings , $value )
        {
            $dependency = '';//vc_generate_dependencies_attributes($settings);
            return '<div class="my_param_block">'
            . '<input id="st_url_poster_media" name="' . $settings[ 'param_name' ]
            . '" class="wpb_vc_param_value wpb-textinput '
            . $settings[ 'param_name' ] . ' ' . $settings[ 'type' ] . '_field" type="text" value="'
            . $value . '" ' . $dependency . '/>'
            . '</div>'
            . '<button id="btn_add_poster_media" class="vc_btn vc_panel-btn-save vc_btn-primary">'.__("Upload",ST_TEXTDOMAIN).'</button>' // New button element
            . "<script>
        jQuery(document).ready(function($){
            var _custom_media = true,
                _orig_send_attachment = wp.media.editor.send.attachment;
            $('#btn_add_poster_media').click(function(e) {
                var send_attachment_bkp = wp.media.editor.send.attachment;
                var button = $(this);

                _custom_media = true;
                wp.media.editor.send.attachment = function(props, attachment){
                    if ( _custom_media ) {
                        $('#st_url_poster_media').val(attachment.url);
                    } else {
                        return _orig_send_attachment.apply( this, [props, attachment] );
                    };
                }
                wp.media.editor.open(button);
                return false;
            });
            $('.add_poster_media').on('click', function(){
                _custom_media = false;
            });
        });
    </script>";
        }
    }
    vc_add_param( 'vc_tab' , array(
            "type"        => "textfield" ,
            "heading"     => __( 'Tab Icon' , ST_TEXTDOMAIN ) ,
            "param_name"  => "tab_icon" ,
            "value"       => '' ,
            "description" => __( "Icon next to the tab title" , ST_TEXTDOMAIN ) ,
        )
    );
    vc_add_param( 'vc_tabs' , array(
            "type"        => "dropdown" ,
            "heading"     => __( 'Tab Style' , ST_TEXTDOMAIN ) ,
            "param_name"  => "tab_style" ,
            "value"       => array(
                __( "--- Select ---" , ST_TEXTDOMAIN ) => "" ,
                __( "Search tab" , ST_TEXTDOMAIN ) => "search_tab" ,
                __( "Single tab" , ST_TEXTDOMAIN ) => "single_tab" ,
            ) ,
            "description" => __( "Select tab style" , ST_TEXTDOMAIN ) ,
        )
    );
    if(!function_exists( 'st_post_type_location_settings_field' )) {
        function st_post_type_location_settings_field( $settings , $value )
        {
            $dependency = '';
            $query      = array(
                'post_type' => 'location' ,
                'post__in'  => explode( ',' , $value ),
                'posts_per_page' => -1 ,
            );
            $txt        = '';
            $list       = query_posts( $query );
            while( have_posts() ) {
                the_post();
                $txt .= '{id: "' . get_the_ID() . '", name: "' . get_the_title() . '" , description:  "ID: ' . get_the_ID() . '" },';
            }
            wp_reset_query();
            return '<div class="my_param_block">'
            . '<input id="st_location" name="' . $settings[ 'param_name' ]
            . '" class="st_pt_location st_post_select_ajax wpb_vc_param_value wpb-textinput '
            . $settings[ 'param_name' ] . ' ' . $settings[ 'type' ] . '_field" type="text" value="'
            . $value . '" ' . $dependency . ' data-post-type="location" multiple />'
            . '</div>'
            . "<script>
                jQuery(document).ready(function($){
                    $('.st_pt_location').each(function(){
                        var me=$(this);
                        $(this).select2({
                            placeholder: me.data('placeholder'),
                            minimumInputLength:2,
                            allowClear: true,
                            multiple: true,
                            ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
                                url: ajaxurl,
                                dataType: 'json',
                                quietMillis: 250,
                                data: function (term, page) {
                                    return {
                                        q: term, // search term,
                                        action:'st_post_select_ajax',
                                        post_type:me.data('post-type')
                                    };
                                },
                                results: function (data, page) { // parse the results into the format expected by Select2.
                                    // since we are using custom formatting functions we do not need to alter the remote JSON data
                                    return { results: data.items };
                                },
                                cache: true
                            },
                            initSelection: function(element, callback) {
                                var data = [" . $txt . "];
                                callback(data);
                            },
                            formatResult: function(state){
                                if (!state.id) return state.name; // optgroup
                                return state.name+'<p><em>'+state.description+'</em></p>';
                            },
                            formatSelection: function(state){
                                if (!state.id) return state.name; // optgroup
                                return state.name+'<p><em>'+state.description+'</em></p>';
                            },
                            escapeMarkup: function(m) { return m; }
                        });
                    });
                });
            </script>
            <style>.select2-drop{z-index:999999;}
                   .select2-container{width:100%;}
                   .select2-search-choice p{line-height:0}
            </style>";
        }

    }
    /**
     *
     *
     * @since 1.1.5
     * */
    if(!function_exists( 'st_post_type_location_2_settings_field' )) {
        function st_post_type_location_2_settings_field( $settings , $value )
        {
            $dependency = '';
            $query      = array(
                'post_type' => 'location' ,
                'post__in'  => explode( ',' , $value ),
                'posts_per_page' => -1 ,
            );
            $txt        = '{}';
            query_posts( $query );
            while( have_posts() ) {
                the_post();
                $txt = '{id: "' . get_the_ID() . '", name: "' . get_the_title() . '" , description:  "ID: ' . get_the_ID() . '" }';
            }
            wp_reset_query(); wp_reset_postdata();
            return '<div class="my_param_block">'
            . '<input id="st_location" name="' . $settings[ 'param_name' ]
            . '" class="st_pt_location_2 wpb_vc_param_value wpb-textinput '
            . $settings[ 'param_name' ] . ' ' . $settings[ 'type' ] . '_field" type="text" value="'
            . $value . '" ' . $dependency . ' data-post-type="location" />'
            . '</div>'
            . "<script>
                jQuery(document).ready(function($){
                    $('.st_pt_location_2').each(function(){
                        var me=$(this);
                        $(this).select2({
                            placeholder: me.data('placeholder'),
                            minimumInputLength:2,
                            allowClear: true,
                            ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
                                url: ajaxurl,
                                dataType: 'json',
                                quietMillis: 250,
                                data: function (term, page) {
                                    return {
                                        q: term, // search term,
                                        action:'st_post_select_ajax',
                                        post_type:me.data('post-type')
                                    };
                                },
                                results: function (data, page) { // parse the results into the format expected by Select2.
                                    // since we are using custom formatting functions we do not need to alter the remote JSON data
                                    return { results: data.items };
                                },
                                cache: true
                            },
                            initSelection: function(element, callback) {
                                var data = ".$txt.";
                                console.log(data);
                                callback(data);
                            },
                            formatResult: function(state){
                                if (!state.id) return state.name; // optgroup
                                return state.name+'<p><em>'+state.description+'</em></p>';
                            },
                            formatSelection: function(state){
                                if (!state.id) return state.name; // optgroup
                                return state.name+'<p><em>'+state.description+'</em></p>';
                            },
                            escapeMarkup: function(m) { return m; }
                        });
                    });
                });
            </script>
            <style>.select2-drop{z-index:999999;}
                   .select2-container{width:100%;}
                   .select2-search-choice p{line-height:0}
            </style>";
        }

    }
    vc_add_shortcode_param( 'st_media' , 'st_media_settings_field' );
    vc_add_shortcode_param( 'st_poster_media' , 'st_poster_media_settings_field' );
    vc_add_shortcode_param( 'st_post_type_location' , 'st_post_type_location_settings_field' );
    /**
     *
     *
     * @since 1.1.5
     * */
    vc_add_shortcode_param( 'st_post_type_location_2' , 'st_post_type_location_2_settings_field' );
    /*if(function_exists('vc_st_reg_shortcode_param'))
    {
        vc_st_reg_shortcode_param('st_media', 'st_media_settings_field');
        vc_st_reg_shortcode_param('st_post_type_location', 'st_post_type_location_settings_field');
    }*/

    if(!function_exists( 'st_post_type_hotel_settings_field' )) {
        function st_post_type_hotel_settings_field( $settings , $value )
        {
            $dependency = '';
            $query      = array(
                'post_type' => 'st_hotel' ,
                'post__in'  => explode( ',' , $value ),
                'posts_per_page' => -1 ,
            );
            $txt        = '';
            $list       = query_posts( $query );
            while( have_posts() ) {
                the_post();
                $txt .= '{id: "' . get_the_ID() . '", name: "' . get_the_title() . '" , description:  "ID: ' . get_the_ID() . '" },';
            }
            wp_reset_query(); wp_reset_postdata();
            return '<div class="my_param_block">'
            . '<input id="st_location" name="' . $settings[ 'param_name' ]
            . '" class="st_pt_hotel st_post_select_ajax wpb_vc_param_value wpb-textinput '
            . $settings[ 'param_name' ] . ' ' . $settings[ 'type' ] . '_field" type="text" value="'
            . $value . '" ' . $dependency . ' data-post-type="st_hotel" multiple />'
            . '</div>'
            . "<script>
                jQuery(document).ready(function($){
                    $('.st_pt_hotel').each(function(){
                        var me=$(this);
                        $(this).select2({
                            placeholder: me.data('placeholder'),
                            minimumInputLength:2,
                            allowClear: true,
                            multiple: true,
                            ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
                                url: ajaxurl,
                                dataType: 'json',
                                quietMillis: 250,
                                data: function (term, page) {
                                    return {
                                        q: term, // search term,
                                        action:'st_post_select_ajax',
                                        post_type:me.data('post-type')
                                    };
                                },
                                results: function (data, page) { // parse the results into the format expected by Select2.
                                    // since we are using custom formatting functions we do not need to alter the remote JSON data
                                    return { results: data.items };
                                },
                                cache: true
                            },
                            initSelection: function(element, callback) {
                                var data = [" . $txt . "];
                                callback(data);
                            },
                            formatResult: function(state){
                                if (!state.id) return state.name; // optgroup
                                return state.name+'<p><em>'+state.description+'</em></p>';
                            },
                            formatSelection: function(state){
                                if (!state.id) return state.name; // optgroup
                                return state.name+'<p><em>'+state.description+'</em></p>';
                            },
                            escapeMarkup: function(m) { return m; }
                        });
                    });
                });
            </script>
            <style>.select2-drop{z-index:999999;}
                   .select2-container{width:100%;}
                   .select2-search-choice p{line-height:0}
            </style>";
        }

    }
    vc_add_shortcode_param( 'st_post_type_hotel' , 'st_post_type_hotel_settings_field' );

    vc_add_shortcode_param( 'st_list_location' , 'st_list_location' );

    if(!function_exists('st_list_location')) {
        function st_list_location($settings, $value)
        {
            $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
            $list_location = TravelHelper::treeLocationHtml();

            $output_html = '<select name="'.$param_name.'" class="wpb_vc_param_value wpb-input wpb-select '.$param_name.'" data-option="">';
            $output_html .= '<option class="" value="">'.esc_html__('-- Select --',ST_TEXTDOMAIN).'</option>';
            if($list_location) {
                foreach ($list_location as $key => $location) {
                    $level_html = '';

                    for($i = 1;$i <= $location['level']/20;$i++){
                        $level_html .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                    $output_html .= '<option class="level-'.$location['level'].'" '.($value==$location['ID']?'selected':'').' value="'.$location['ID'].'">'.balanceTags($level_html).$location['post_title'].'</option>';
                }
            }
            $output_html .= '<select>';
            return $output_html;

        }
    }
}