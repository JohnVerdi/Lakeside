<?php
$packages=apply_filters('st_demo_packages',array());
?>

<div class="traveler-important-notice">
    <p class="about-description"><?php printf(__("The Demo content is a replication of the Live Content. By importing it, you could get several sliders, sliders,pages, posts, theme options, widgets, sidebars and other settings.To be able to get them, make sure that you have installed and activated these plugins:  Contact form 7 , Option tree and Visual Composer <br><span style=\"color:#f0ad4e\">WARNING: By clicking Import Demo Content button, your current theme options, sliders and widgets will be replaced. It can also take a minute to complete.</span> <br><span style=\"color:red\"><b>Please back up your database before doing this.</b> %s ",ST_TEXTDOMAIN),'<a href="http://shinetheme.com/demosd/documentation/category/traveler/demo-contents-traveler/" target="_blank">View more info here.</a>')?></p>
</div>

<div class="console_iport" style="margin-bottom: 20px;"></div>

<div class="traveler-demo-themes">
    <div class="st-install feature-section theme-browser rendered">
        <div class='st_landing_page_admin_grid'>
            <?php if(!empty($packages)){
                foreach($packages as $key=>$value)
                {
                    ?>
                    <div class="theme">
                        <div class="theme-screenshot">
                            <img src="<?php echo esc_attr($value['preview_image']) ?>">
                        </div>
                        <h3 class="theme-name" id="classic"><?php echo esc_html($value['title']) ?></h3>
                        <div class="theme-actions">
                            <a onclick="return false" class="button button-primary st-install-demo" data-demo-id="<?php echo esc_attr($key) ?>" href="#"><?php _e('Install',ST_TEXTDOMAIN)?></a>
<!--							<a class="button button-primary" target="_blank" href="#">--><?php //_e('Preview',ST_TEXTDOMAIN)?><!--</a> -->
						</div>

                        <div class="demo-import-loader preview-all"></div>
                        <div class="demo-import-loader preview-classic"><i class="dashicons dashicons-admin-generic"></i></div>
                    </div>
                    <?php
                }
            } ?>
        </div>
        
    </div>
</div>