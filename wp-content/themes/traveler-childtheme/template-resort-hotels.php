<?php
/**
 * Template Name: Location Hotels and Resorts
 */
get_header();
get_template_part( 'breadcrumb' );

?>
    <div class="mfp-with-anim mfp-dialog mfp-search-dialog mfp-hide" id="search-dialog">
    </div>
    <div class="container">
        <?php
        include('data-resort-hotels.php');
        ?>
    </div>
<?php
wp_reset_query();
get_footer();
?>