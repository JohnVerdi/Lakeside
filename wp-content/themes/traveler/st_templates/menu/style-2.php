<header id="menu2" class="st_menu" >
    <div id='top_header' class="header-top <?php echo apply_filters('st_header_top_class','') ?>">
        <div class="container">
            <div class='row'>
                <?php
                $temp = TravelHelper::get_location_temp(); 
                $temp = $temp['temp'] ; 
                if (!$temp){$class="12" ; } else {$class= "6" ; }
                 ?>
                <div class='menu_div col-lg-2 col-md-12 col-sm-<?php echo esc_attr($class) ; ?> col-xs-<?php echo esc_attr($class) ; ?>'>
                      <a class="logo" href="<?php echo home_url('/')?>">
                        <?php
                        $logo_url = st()->get_option('logo',get_template_directory_uri().'/img/logo-white.png');
                        $logo = TravelHelper::get_attchment_size($logo_url , true);
                        ?>
                        <img <?php if ($logo){echo (" width='".$logo['width']."px' height ='".$logo['height']."px' ") ; } ?> src="<?php echo esc_url($logo_url); ?>" alt="logo" title="<?php bloginfo('name')?>">
                    </a>
                </div>
                
                <div class='col-lg-8 col-md-8 col-sm-12 col-xs-12'>
                    <div class="nav">
                        <?php if(has_nav_menu('primary')){
                            wp_nav_menu(
                                array(
                                    'theme_location'=>'primary',
                                    "container"=>"",
                                    'items_wrap'      => '<ul id="slimmenu" data-title="<a href=\''.home_url('/').'\'><img width=auto height=40px class=st_logo_mobile src='.$logo_url.' /></a>" class="%2$s slimmenu">%3$s</ul>',
                                )
                            );
                        } ?>
                    </div>
                </div>
                <?php if ($temp):?>
                <div class='col-lg-2 col-md-4 col-sm-6 col-xs-6 get_location_weather'>
                    <div class="top-user-area clearfix">
                        <p class="loc-info-weather">
                            <?php 
                                echo TravelHelper::get_location_weather();
                            ?>                            
                    </div>
                </div>
                <?php endif ;?>
            </div>
        </div>
    </div>
</header>