<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STAdminMenu
 *
 * Created by ShineTheme
 *
 */
if(!class_exists('STAdminMenu'))
{

    class STAdminMenu extends STAdmin
    {
        function __construct()
        {
            add_action('admin_menu',array($this,'admin_menu'),50);
        }

        function admin_menu()
        {

            add_submenu_page( apply_filters('ot_theme_options_menu_slug','st_traveler_options'), "Attributes", 'Attributes', 'manage_options', 'hotel_attributes', array( $this, 'attributes_page' ));


        }


        function attributes_page()
        {


            if(class_exists('STAttribute'))
            {
                $a=new STAttribute();
                echo balanceTags( $a->content());
            }

        }

    }

    $admin=new STAdminMenu();
}