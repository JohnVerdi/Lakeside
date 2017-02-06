<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Page.php
 * Template Name: Streamline Boxed Page
 * Created by ShineTheme
 *
 */
get_header();
 
    $sidebar_id=apply_filters('st_blog_sidebar_id','blog');
?>
    <h1 class="page-title"><?php the_title()?></h1>
    <div class="row mb20">
        <?php $sidebar_pos=apply_filters('st_blog_sidebar','right');
        if($sidebar_pos=="left"){
            get_sidebar('blog');
        }
        ?>
        <div class="col-sm-12">
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
<?php
get_footer();