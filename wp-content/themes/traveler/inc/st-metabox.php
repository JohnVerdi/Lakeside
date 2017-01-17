<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Initialize the custom Meta Boxes.
 *
 * Created by ShineTheme
 *
 */
$custom_metabox[] = array(
    'id'          => 'demo_meta_box',
    'title'       => __( 'Demo Meta Box', ST_TEXTDOMAIN),
    'pages'       => array( 'post' ),
    'context'     => 'normal',
    'priority'    => 'high',
    'fields'      => array(
        array(
            'label'       => __( 'Information', ST_TEXTDOMAIN),
            'id'          => 'infomation_tab',
            'type'        => 'tab'
        ),

        array(
            'label'       => __( 'Gallery', ST_TEXTDOMAIN),
            'id'          => 'gallery',
            'type'        => 'gallery',
            'desc'        =>  __( 'This is a Gallery option type', ST_TEXTDOMAIN),

        ),array(
            'label'       => __( 'Media(Video URL or Audio URL)', ST_TEXTDOMAIN),
            'id'          => 'media',
            'type'        => 'text',
            'desc'        =>  __( 'This field for Audio and Video Post Format', ST_TEXTDOMAIN),

        ),array(
            'label'       => __( 'Link', ST_TEXTDOMAIN),
            'id'          => 'link',
            'type'        => 'text',
            'desc'        =>  __( 'This is a Link option type', ST_TEXTDOMAIN),

        ),
        array(
            'label'       => __( 'Post Sidebar Setting', ST_TEXTDOMAIN),
            'id'          => 'sidebar_tab_post',
            'type'        => 'tab'
        ),
        array(
            'id'          => 'post_sidebar_pos',
            'label'       => __( 'Sidebar Position', ST_TEXTDOMAIN ),
            'desc'        =>  __( 'You can choose No sidebar, Left Sidebar and Rigt Sidebar' ,ST_TEXTDOMAIN ),
            'type'        => 'select',
            'section'     => 'option_blog',
            'choices'   =>array(
                array(
                    'value'=>'',
                    'label'=>__('---Select---',ST_TEXTDOMAIN)
                ),
                array(
                    'value'=>'no',
                    'label'=>__('No',ST_TEXTDOMAIN)
                ),
                array(
                    'value'=>'left',
                    'label'=>__('Left',ST_TEXTDOMAIN)
                ),
                array(
                    'value'=>'right',
                    'label'=>__('Right',ST_TEXTDOMAIN)
                )

            )
        ),
        array(
            'label'       => __( 'Select Sidebar', ST_TEXTDOMAIN),
            'id'          => 'post_sidebar',
            'type'        => 'sidebar-select',
        ), 
    )
);


//Pages
$custom_metabox[] = array(
    'id'          => 'st_footer_social',
    'title'       => __( 'Page Setting', ST_TEXTDOMAIN),
    'desc'        => '',
    'pages'       => array( 'page' ),
    'context'     => 'normal',
    'priority'    => 'high',
    'fields'      => array(
        /*array(
            'label'       => __( 'Header page Setting', ST_TEXTDOMAIN),
            'id'          => 'header_tab',
            'type'        => 'tab'
        ),
        array(
            'id'      => 'header_position' ,
            'label'   => __( 'Header Position' , ST_TEXTDOMAIN ) ,
            'desc'    => __( '<a href="http://www.w3schools.com/css/css_positioning.asp"><em>What is it ?</em></a>' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_header' ,
            'std'     => '',
            'choices'   => array(
                array('label' => __("Default" ,ST_TEXTDOMAIN) , 'value'=> "default", ),
                array('label' => __("Absolute" ,ST_TEXTDOMAIN) , 'value'=> "absolute", ),
            ),
        ) ,
        array(
            'label'       => __( 'Menu color', ST_TEXTDOMAIN),
            'id'          => 'menu_color',
            'type'        => 'typography',
            'desc'        => __( 'Input ', ST_TEXTDOMAIN),
            'std'           => '#333333'
        ),*/
        array(
            'label'       => __( 'Template Comming Soon Setting', ST_TEXTDOMAIN),
            'id'          => 'detail_tab',
            'type'        => 'tab'
        ),
        /*array(
            'label'         => __("Comingsoon style" , ST_TEXTDOMAIN),
            'id'            => 'cs_style',
            'type'          =>  'select',
            'choices'   => array(
                array('label' => __("Default" ,ST_TEXTDOMAIN) , 'value'=> "default", ),
                array('label' => __("Tour box style" ,ST_TEXTDOMAIN) , 'value'=> "st_tour_ver", ),
            ),
        ),*/
        array(
            'label'       => __( 'Data date countdown', ST_TEXTDOMAIN),
            'id'          => 'data_countdown',
            'type'        => 'date-picker',
            'desc'        => __( 'Input ', ST_TEXTDOMAIN),
        ),
        array(
            'label'       => __( 'Footer Social', ST_TEXTDOMAIN),
            'id'          => 'footer_social',
            'type'        => 'textarea',
            'desc'        => __( 'Input html social', ST_TEXTDOMAIN),
            'rows'         => 3,
        ),
        array(
            'label'       => __( 'Body background', ST_TEXTDOMAIN),
            'id'          => 'cs_bgr',
            'type'        => 'background',
            'desc'        => __( 'Body background', ST_TEXTDOMAIN),
        ),
        array(
            'label'       => __( 'Login page Setting', ST_TEXTDOMAIN),
            'id'          => 'login_tab',
            'type'        => 'tab'
        ),
        array(
            'label'       => __( 'Button sign in', ST_TEXTDOMAIN),
            'id'          => 'btn_sing_in',
            'type'        => 'text',
            'value'        => __( 'Sign in', ST_TEXTDOMAIN),
            'desc'        => __( 'Input text', ST_TEXTDOMAIN),
        ),
        array(
            'label'       => __( 'Button Register', ST_TEXTDOMAIN),
            'id'          => 'btn_register',
            'type'        => 'text',
            'value'       => __( 'Register', ST_TEXTDOMAIN),
            'desc'        => __( 'Input text', ST_TEXTDOMAIN),
        ),
        array(
            'label'       => __( 'Blog page Setting', ST_TEXTDOMAIN),
            'id'          => 'blog_tab',
            'type'        => 'tab'
        ),
        array(
            'label'       => __( 'Blog style', ST_TEXTDOMAIN),
            'id'          => 'blog_style',
            'type'        => 'select',
            'desc'        => __( 'Template blog style', ST_TEXTDOMAIN),
            'choices'   => array(
                array('label' => __("List" ,ST_TEXTDOMAIN) , 'value'=> "", ),
                array('label' => __("Grid" ,ST_TEXTDOMAIN) , 'value'=> "st_grid", ),
                array('label' => __("Tour box Grid" ,ST_TEXTDOMAIN) , 'value'=> "st_tour_grid", ),
                array('label' => __("Tour box List" ,ST_TEXTDOMAIN) , 'value'=> "st_tour_list", ),
            ),
        ),

    )
);

$custom_metabox[] = array(
    'id'          => 'st_page_metabox_option',
    'title'       => __( 'Page Options', ST_TEXTDOMAIN),
    'pages'       => array( 'page','product','location' ),
    'context'     => 'side',
    'priority'    => 'default',
    'fields'      => array(
        array(
            'label'       => __( 'Footer Template', ST_TEXTDOMAIN),
            'id'          => 'footer_template',
            'type'        => 'page-select',
        ),
        array(
            'id'          => 'post_sidebar_pos',
            'label'       => __( 'Sidebar Position', ST_TEXTDOMAIN ),
            'type'        => 'select',
            'section'     => 'option_blog',
            'choices'   =>array(
                array(
                    'value'=>'',
                    'label'=>__('--- Select ---',ST_TEXTDOMAIN)
                ),
                array(
                    'value'=>'no',
                    'label'=>__('No',ST_TEXTDOMAIN)
                ),
                array(
                    'value'=>'left',
                    'label'=>__('Left',ST_TEXTDOMAIN)
                ),
                array(
                    'value'=>'right',
                    'label'=>__('Right',ST_TEXTDOMAIN)
                )

            )
        ),
        array(
            'label'       => __( 'Select Sidebar', ST_TEXTDOMAIN),
            'id'          => 'post_sidebar',
            'type'        => 'sidebar-select',
        ),

    )
);


$custom_metabox[] = array(
        'id'       => 'st_custom_type_layout',
        'title'    => __('Layout Options', ST_TEXTDOMAIN),
        'desc'     => '',
        'pages'    => array('st_layouts'),
        'context'  => 'normal',
        'priority' => 'high',
        'fields'   => array(
            array(
                'label' => __('Type Layout Options', ST_TEXTDOMAIN),
                'id'    => 'type_layout_tab',
                'type'  => 'tab'
            ),
            array(
                'label'   => __('Layout type', ST_TEXTDOMAIN),
                'id'      => 'st_type_layout',
                'type'    => 'select',
                'choices' => array(
                    array(
                        'value' => 'st_hotel',
                        'label' => __('Hotel Single', ST_TEXTDOMAIN)
                    ),
                    array(
                        'value' => 'st_hotel_search',
                        'label' => __('Hotel Search', ST_TEXTDOMAIN)
                    ),
                    array(
                        'value' => 'hotel_room',
                        'label' => __('Hotel Room', ST_TEXTDOMAIN)
                    ),
                    array(
                        'value' => 'st_rental',
                        'label' => __('Rental Single', ST_TEXTDOMAIN)
                    ),
                    array(
                        'value' => 'st_rental_search',
                        'label' => __('Rental Search', ST_TEXTDOMAIN)
                    ),
                    array(
                        'value' => 'rental_room',
                        'label' => __('Rental Room', ST_TEXTDOMAIN)
                    ),
                    array(
                        'value' => 'st_activity',
                        'label' => __('Activity Single', ST_TEXTDOMAIN)
                    ),
                    array(
                        'value' => 'st_activity_search',
                        'label' => __('Activity Search', ST_TEXTDOMAIN)
                    ),
                    array(
                        'value' => 'st_tours',
                        'label' => __('Tour Single', ST_TEXTDOMAIN)
                    ),
                    array(
                        'value' => 'st_tours_search',
                        'label' => __('Tour Search', ST_TEXTDOMAIN)
                    ),
                    array(
                        'value' => 'st_cars',
                        'label' => __('Car Single', ST_TEXTDOMAIN)
                    ),
                    array(
                        'value' => 'st_cars_search',
                        'label' => __('Car Search', ST_TEXTDOMAIN)
                    )
                )
            ),
            array(
                'label'       => __( 'Footer Template', ST_TEXTDOMAIN),
                'id'          => 'footer_template',
                'type'        => 'page-select',
            ),
            array(
                'label' => __("Layout size") , 
                'id'    => __("layout_size"),
                'type'  => "select" , 
                'std'   => 'container',
                'desc'  => __("Select layout width size (<a href='http://getbootstrap.com/css/'>Read more </a>)" , ST_TEXTDOMAIN),
                'choices'=> array(
                    array(
                        'label' => __('Full width' , ST_TEXTDOMAIN),
                        'value' => 'full'
                        ),
                    array(
                        'label' => __('Boostrap Container' , ST_TEXTDOMAIN),
                        'value' => 'container'
                        ),
                    array(
                        'label' => __('Boostrap Container Fluid' , ST_TEXTDOMAIN),
                        'value' => 'container-fluid'
                        ),/*                    
                    array(
                        'label' => __('Customize  - building' , ST_TEXTDOMAIN),
                        'value' => 'customize'
                        ),*/
                    ),
                ),
            /*array(
                'label' => __("Breadcrumb") , 
                'id'    => __("is_breadcrumb"),
                'type'  => "on-off" , 
                'std'   => 'on',
                'desc'  => __("Off to hide Breadcrumb" , ST_TEXTDOMAIN),                
            ), */
            ), 
            
                
        
    );


