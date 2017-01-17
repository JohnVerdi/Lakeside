<?php
/**
 * The template for displaying unit details
 *
 * This is the template that displays all resortpro listings by default.
 * You can override this template by creating your own "my_theme/template/page-resortpro-listing-detail.php" file
 *
 * @package    ResortPro
 * @since      v1.0
 */


get_header();

?>


<?php
//before post stuff
if(have_posts()) :
    while( have_posts()) :
        the_post();
        ?>

        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="content unit-details">
                        <?php the_content() ?>
                    </div>
                </div>
            </div>
        </div>

    <?php
    endwhile;
endif;
//ater psot stuff
?>




<?php

get_footer();
?>
