<?php
/**
 * Template Name: Boxed Page
 * The template for displaying pages
 *
 * This is the template that displays all resortpro listings by default.
 * You can override this template by creating your own "my_theme/template/page-resortpro-listings.php" file
 *
 * @package    ResortPro
 * @since      v1.0
 */
get_header();


?>
    <div class="container">
        <h1 class="page-title"><?php the_title()?></h1>
        <div class="row mb20">

            <div class="col-md-12">
                <?php while(have_posts()){
                    the_post();
                    ?>
                    <div <?php post_class()?>>
                        <div class="entry-content">
                            <?php
                            the_content();
                            ?>
                        </div>
                        <div>
                            <?php
                            if ( comments_open() || '0' != get_comments_number() ) :
                                comments_template();
                            endif; ?>
                        </div>
                        <div class="entry-meta">
                            <?php
                            wp_link_pages( );
                            edit_post_link(st_get_language('edit_this_page'), '<p>', '</p>');
                            ?>
                        </div>
                    </div>
                <?php
                }?>
            </div>

        </div>
    </div>
<?php
get_footer();