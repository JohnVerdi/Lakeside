<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Search loading
 *
 * Created by ShineTheme
 *
 */
$loading_img=st()->get_option('search_preload_image');
?>
<div class="full-page-absolute" style="position: fixed;
    top: 0px;
    left: 0px;
    right:0px;
    bottom: 0px;
    z-index: 10000">
    <div class="bg-holder full">
        <div class="bg-mask"></div>
        <div class="bg-img" style="<?php if($loading_img) echo 'background-image:url('.esc_url($loading_img).')';?>"></div>
        <div class="bg-holder-content full text-white text-center">
            <a class="logo-holder" href="<?php echo home_url()?>">
                <img src="<?php echo st()->get_option('logo') ?>" alt="<?php st_the_language('logo') ?>" title="<?php st_the_language('logo') ?>" />
            </a>
            <div class="full-center">
                <div class="container">
                    <div class="spinner-clock">
                        <div class="spinner-clock-hour"></div>
                        <div class="spinner-clock-minute"></div>
                    </div>
                    <h2 class="mb5">
                        <?php  echo sprintf(st_get_language('looking_for_s'),apply_filters('st_search_preload_page',false)) ?>
                    </h2>
                    <p class="text-bigger"><?php st_the_language('it_will_take_couple_seconds')  ?></p>
                </div>
            </div>
        </div>
    </div>
</div>