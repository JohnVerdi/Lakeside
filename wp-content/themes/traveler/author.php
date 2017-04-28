<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * author
 *
 * Created by ShineTheme
 *
 */
get_header();
?>
    <?php if ( have_posts() ) : ?>
    <div class="container">
        <h1 class="page-title">
            <?php
            /*
             * Queue the first post, that way we know what author
             * we're dealing with (if that is the case).
             *
             * We reset this later so we can run the loop properly
             * with a call to rewind_posts().
             */
            the_post();

            printf( st_get_language('all_posts_by_s'), get_the_author() );
            ?>
        </h1>
        <?php if ( get_the_author_meta( 'description' ) ) : ?>
            <div class="author-description"><?php the_author_meta( 'description' ); ?></div>
        <?php endif; ?>
    </div>
    <div class="container">
        <div class="row">
            <?php $sidebar_pos=apply_filters('st_blog_sidebar','right');
            if($sidebar_pos=="left"){
                get_sidebar('blog');
            }
            ?>
            <div class="<?php echo apply_filters('st_blog_sidebar','right')=='no'?'col-sm-12':'col-sm-9'; ?>">
                <?php
                rewind_posts();
                if(have_posts()):
                    while(have_posts())
                    {
                        the_post();
                        get_template_part('content','loop');
                    }
                    TravelHelper::paging();
                else:
                    get_template_part('content','none');
                endif;
                ?>
            </div>
            <?php $sidebar_pos=apply_filters('st_blog_sidebar','right');
            if($sidebar_pos=="right"){
                get_sidebar('blog');
            }
            ?>
        </div>
    </div>
    <?php endif;?>
<?php
get_footer();?>