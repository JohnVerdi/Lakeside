<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * 404
 *
 * Created by ShineTheme
 *
 */
get_header("full");
?>
    <div class="full-center class404">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 class_text_404">
                    <p class="text-hero"><?php st_the_language('404') ?></p>
                    <?php echo ( st()->get_option('404_text') ) ?><br>
                    <a class="btn btn-white btn-ghost btn-lg mt5" href="<?php echo home_url() ?>">
                        <i class="fa fa-long-arrow-left"></i> <?php st_the_language('to_homepage') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php get_footer("full"); ?>