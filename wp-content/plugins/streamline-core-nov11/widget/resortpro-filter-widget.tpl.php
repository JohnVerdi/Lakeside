<?php

/*
 * Template for the output of the ResortPro Widget
 * Override by placing a file called resortpro-widget.template.php in your active theme
 *
 * AVAILABLE VARIABLES:
 *
 * $action - the path to post the form to, you really don't want to mess with this.
 * $start_date - the default start_date for the filter
 * $end_date - the default end_date (+5 days from start_date)
 * Test comment
 */

?>

<div class="filter-widget">
    <!-- ANGULAR FORM FOR PROCESSING WHEN LISTINGS HAVE ALREADY BEEN PULLED -->
	<?php do_action( 'streamline_widget_filter_before_form', $instance ); ?>
    <form method="post" class="form" id="resortpro-widget-form" ng-submit="availabilitySearch(search)" ng-init="plus='<?php echo $instance['number_bedrooms-plus']; ?>'">
		<?php do_action( 'streamline_widget_filter_before', $instance ); ?>

        <input type="hidden" name="resortpro_nonce" value="<?php echo $nonce; ?>"/>
        <!--<input type="hidden" name="redirect" value="<?php echo $_SERVER['REQUEST_URI']; ?>" /> -->
        <div class="row">
            <?php echo $search_template; ?>
        </div>

		<?php do_action( 'streamline_widget_filter_before_amenities' ); ?>

        <?php if (count($amenities) && false): ?>
        <div class="row row-amenities">

            <?php
            $a_groups = array();
            foreach ($amenities as $amenity) {
                //echo $amenity['name'].'<br />';
                if (!in_array($amenity['group_name'], $a_groups)) {
                    $a_groups[] = $amenity['group_name'];
                }
            }

            foreach ($a_groups as $group) {
                ?>

                <div class="col-md-12 amenity_group" style="margin-bottom:24px">
                    <label><?php echo $group; ?></label><br/>
                    <?php
                    foreach ($amenities as $amenity) {
                        if ($group == $amenity['group_name']) {
                            echo '<input type="checkbox" name="amenity_' . $amenity['id'] . '" value="1" ng-model="selected[' . $amenity['id'] . ']" ng-true-value="' . $amenity['id'] . '" ng-click="availabilitySearch(search)" /> ' . $amenity['name'] . '<br />';
                        }
                    }
                    ?>
                </div>

                <?php
            }

            ?>
        </div>
        <?php endif; ?>
	<?php do_action( 'streamline_widget_filter_after' ); ?>
    </form>
	<?php do_action( 'streamline_widget_filter_after_form' ); ?>
</div>
