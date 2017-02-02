<?php
/**
 * Header template
 */
;?>

<header id="main-header" class="st_menu" >
    <div class="header-top <?php echo apply_filters('st_header_top_class','') ?>">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <a class="logo" href="<?php echo home_url('/')?>">
                        <?php
                        $logo_url = st()->get_option('logo');
                        if(!empty($logo_url)){
                            $logo = TravelHelper::get_attchment_size($logo_url , true);
                            ?>
                            <img <?php if ($logo){echo (" width='".$logo['width']."px' height ='".$logo['height']."px' ") ; } ?> src="<?php echo esc_url($logo_url); ?>" alt="logo" title="<?php bloginfo('name')?>">
                            <?php
                        }
                        ?>
                    </a>
                </div>
                <?php get_template_part('users/user','nav');?>
            </div>
        </div>
    </div>
    <div class="main_menu_wrap" id="menu1">
        <div class="<?php echo apply_filters('st_default_menu_wrapper', "container") ; ?>" >
            <div class="nav">
                <?php
                $logo__mobile_url = st()->get_option('logo_mobile',$logo_url);
                $html_logo_mobile = "";
                if(!empty($logo__mobile_url)){
                    $html_logo_mobile = '<a href=\''.home_url('/').'\'><img width=auto height=40px class=st_logo_mobile src='.$logo__mobile_url.' /></a>';
                }
                ?>
                <?php if(has_nav_menu('primary')){
                    wp_nav_menu(array('theme_location'=>'primary',
                                      "container"=>"",
                                      'items_wrap'      => '<ul id="slimmenu" data-title="'.$html_logo_mobile.'" class="%2$s slimmenu">%3$s</ul>',
                    ));
                }
                ?>
            </div>
        </div>
    </div><!-- End .main_menu_wrap-->
</header>