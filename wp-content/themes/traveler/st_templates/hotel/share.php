<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Hotel share
 *
 * Created by ShineTheme
 *
 */
?>
<div class="share clear">
    <span><?php st_the_language('share')?><i class="fa fa-share fa-lg"></i></span>
    <ul class="clear">
        <?php $share_code=st()->get_option('edv_share_code');

        if($share_code){
            $share_code=str_replace('[__post_permalink__]',get_permalink(),$share_code);
            $share_code=str_replace('[__post_title__]',get_the_title(),$share_code);

            echo ($share_code);
        }else{?>
        <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink() ?>&amp;title=<?php the_title()?>" target="_blank" original-title="Facebook"><i class="fa fa-facebook fa-lg"></i></a></li>
        <li><a href="http://twitter.com/share?url=<?php the_permalink() ?>&amp;title=<?php the_title()?>" target="_blank" original-title="Twitter"><i class="fa fa-twitter fa-lg"></i></a></li>
        <li><a href="https://plus.google.com/share?url=<?php the_permalink() ?>&amp;title=<?php the_title()?>" target="_blank" original-title="Google+"><i class="fa fa-google-plus fa-lg"></i></a></li>
        <li><a class="no-open" href="javascript:void((function()%7Bvar%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)%7D)());" target="_blank" original-title="Pinterest"><i class="fa fa-pinterest fa-lg"></i></a></li>
        <li><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink() ?>&amp;title=<?php the_title()?>" target="_blank" original-title="LinkedIn"><i class="fa fa-linkedin fa-lg"></i></a></li>
        <?php }?>
    </ul>
</div>