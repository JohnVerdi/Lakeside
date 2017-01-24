<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * function
 *
 * Created by ShineTheme
 *
 */

if(!defined('ST_TEXTDOMAIN'))
define ('ST_TEXTDOMAIN','traveler');

if(!defined('ST_TRAVELER_VERSION'))
{
    $theme=wp_get_theme();
	if($theme->parent())
	{
		$theme=$theme->parent();
	}
    define ('ST_TRAVELER_VERSION',$theme->get( 'Version' ));
}


$status=load_theme_textdomain(ST_TEXTDOMAIN,get_stylesheet_directory().'/language'); 

get_template_part('inc/class.traveler');

register_sidebar( array(
        'name'          => __( 'Streamline Homepage Search', 'twentyfifteen' ),
        'id'            => 'streamlinecore_home_search',
        'description'   => __( 'Add Streamline Core Search Widget here.', 'twentyfifteen' ),
        'before_widget' => '<div id="streamlinecore_home_search" class="container">',
        'after_widget'  => '</div>',
        'before_title'  => '<h1>',
        'after_title'   => '</h1>',
    ) );
    
if( isset($post_id)){
	wp_get_post_revisions( $post_id, $args );
}


