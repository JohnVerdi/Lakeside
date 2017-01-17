<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Activity element photo
 *
 * Created by ShineTheme
 *
 */
if(!is_singular('st_activity') or !isset($attr)) return false;

$default=array(
    'style'=>'slide'
);

extract(wp_parse_args($attr,$default));

$gallery=get_post_meta(get_the_ID(),'gallery',true);
$gallery_array=explode(',',$gallery);
switch($style){



    case "grid":
            ?>
            <div class="row row-no-gutter" id="popup-gallery">
                <?php

                if(is_array($gallery_array) and !empty($gallery_array))
                {
                    foreach($gallery_array as $key=>$value)
                    {
                        $img_link=wp_get_attachment_image_src($value,array(800,600,'bfi_thumb'=>true));

                        ?>
                        <div class="col-md-3">
                            <a class="hover-img popup-gallery-image" href="<?php echo isset($img_link[0])?$img_link[0]:false; ?>" data-effect="mfp-zoom-out">

                                <?php echo wp_get_attachment_image($value,array(278,208,'bfi_thumb'=>true));?>
                                <i class="fa fa-plus round box-icon-small hover-icon i round"></i>
                            </a>
                        </div>
                    <?php
                    }
                }
                ?>
            </div>
            <?php
        break;
    case "slide";
    default :
        ?>
        <div class="fotorama" data-allowfullscreen="true" data-nav="thumbs">
            <?php
            if(is_array($gallery_array) and !empty($gallery_array))
            {
                foreach($gallery_array as $key=>$value)
                {
                    echo wp_get_attachment_image($value,array(800,600,'bfi_thumb'=>true));
                }
            }
            ?>

        </div>
        <?php
        break;
}
?>
