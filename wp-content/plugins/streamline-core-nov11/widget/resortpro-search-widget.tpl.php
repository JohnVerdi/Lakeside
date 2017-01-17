<div class="search_widget">
        <?php do_action( 'streamline_widget_search_before_form' ); ?>
    <form method="post" class="form" id="resortpro-widget-form"
          action="<?php echo get_permalink( get_option('resortpro_listings_page_id') ) ?>">
        <input type="hidden" name="resortpro_search_nonce" value="<?php echo $nonce; ?>"/>
        <?php if($instance['number_bedrooms-plus'] == '1'): ?>
            <input type="hidden" name="plus" value="1"/>
        <?php endif; ?>
        <?php do_action( 'streamline_widget_search_before' ); ?>
        <?php if(!empty($widget_title)): ?>
        <div class="row">
            <div class="col-md-12">
                <?php echo $widget_title; ?>
            </div>
        </div>
        <?php endif; ?>
        <div class="row">
            <?php echo $search_template; ?>
        </div>
        <?php do_action( 'streamline_widget_search_after' ); ?>
    </form>
        <?php do_action( 'streamline_widget_search_after_form' ); ?>
</div>
