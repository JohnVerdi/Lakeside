<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Single blog
 *
 * Created by ShineTheme
 *
 */
get_header();
?>
    <div class="container">
        <h1 class="page-title"><?php the_title()?></h1>
        <div class="row">
            <?php $sidebar_pos=apply_filters('st_blog_sidebar','right');
            if($sidebar_pos=="left"){
                get_sidebar('blog');
            }
            ?>
            <div class="<?php echo apply_filters('st_blog_sidebar','right')=='no'?'col-sm-12':'col-sm-9'; ?>">
                <?php
                while(have_posts()){
                    the_post();
                    get_template_part('content-single');
                    if ( comments_open() || '0' != get_comments_number() ) :
                        comments_template();
                    endif;
                }?>
            </div>
            <?php
            if($sidebar_pos=="right"){
                get_sidebar('blog');
            }
            ?>
        </div>
    </div>
<?php
get_footer();