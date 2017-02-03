<?php
/**
 * Created by PhpStorm.
 * User: MSI
 * Date: 21/08/2015
 * Time: 9:45 SA
 */


function get_children_template_directory_uri() {
    $directory_template = get_template_directory_uri();
    $directory_child = str_replace('traveler', '', $directory_template) . 'traveler-childtheme';

    return $directory_child;
}