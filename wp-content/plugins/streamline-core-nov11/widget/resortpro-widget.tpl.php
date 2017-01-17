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
 *
 */

?>

<!-- SERVER SIDE FORM FOR PAGES WHERE LISTINGS HAVE NOT BEEN PULLED -->
<div>


    <!-- ANGULAR FORM FOR PROCESSING WHEN LISTINGS HAVE ALREADY BEEN PULLED -->
    <form method="post" class="form" id="resortpro-widget-form" ng-submit="availabilitySearch(search)"
          ng-show="properties.length > 0">

        <input type="hidden" name="resortpro_nonce" value="<?php echo $nonce; ?>"/>
        <!--<input type="hidden" name="redirect" value="<?php echo $_SERVER['REQUEST_URI']; ?>" /> -->

        <div class="row">
            <?php
            $child = true;
            $col = ($child) ? 3 : 4;
            ?>

            <div class="col-md-<?php echo $col; ?> col-sm-6">

                <input type="hidden" name="latNE" ng-model="latNE" value=""/>
                <input type="hidden" name="longNE" ng-model="longNE" value=""/>
                <input type="hidden" name="latSW" ng-model="latSW" value=""/>
                <input type="hidden" name="longSW" ng-model="longSW" value=""/>

                <div class="form-group has-feedback">
                    <label for="start_date"><?php _e( 'Check In', 'streamline-core' ) ?></label>
                    <input type="text"
                           class="form-control input-sm datepicker"
                           name="start_date"
                           id="search_start_date"
                           ng-model="search.start_date"
                           sdpicker />
                    <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                </div>
            </div>
            <div class="col-md-<?php echo $col; ?> col-sm-6">
                <div class="form-group has-feedback">
                    <label for="end_date"><?php _e( 'Check Out', 'streamline-core' ) ?></label>
                    <input type="text"
                           class="form-control input-sm datepicker"
                           name="end_date"
                           id="search_end_date"
                           ng-model="search.end_date"
                           edpicker />
                    <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                </div>
            </div>
            <div class="col-md-<?php echo $col ?> col-sm-6">
                <div class="form-group">
                    <label for="occupants"><?php _e( 'Guests', 'streamline-core' ) ?></label>
                    <select id="occupants" name="occupants" class="form-control input-sm" ng-model="search.occupants">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8+</option>
                    </select>
                </div>
            </div>
            <?php if ($child): ?>
                <div class="col-md-<?php echo $col ?> col-sm-6">
                    <div class="form-group has-feedback">
                        <label for="children"><?php _e( 'Children', 'streamline-core' ) ?></label>
                        <select id="children" name="children" class="form-control input-sm" ng-model="search.children">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8+</option>
                        </select>
                    </div>
                </div>
            <?php endif; ?>

        </div>


        <div class="collapse" id="collapseFilters">
            <div class="row">
                <div class="form-group">

                    <?php
                    $pets = true;
                    ?>

                    <?php if (count($areas)): ?>
                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="areas"><?php _e( 'Location Area', 'streamline-core' ) ?></label>
                                <select name="areas" class="form-control input-sm" ng-model="area"
                                        ng-change="availabilitySearch(search)">
                                    <?php foreach ($areas as $area) {
                                        echo "<option value=\"{$area->id}\">{$area->name}</option>";
                                    } ?>
                                </select>
                            </div>

                        </div>
                    <?php endif; ?>

                    <?php if ($pets): ?>
                        <div class="col-md-2">
                            <label for="pets"><?php _e( 'Pets', 'streamline-core' ) ?></label>
                            <select id="pets" name="pets" class="form-control input-sm" ng-model="search.pets">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8+</option>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row row-price-range">
                <div class="col-md-12">
                    <p>
                        <label for="amount"><?php _e( 'Price range:', 'streamline-core' ) ?></label>
                        <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;" value="75 - 300">
                    </p>

                    <div sliderange ng-model="pricerange" show-availability="filterByPrice(minPrice, maxPrice)"></div>
                    <input type="hidden" ng-model="minPrice" />
                    <input type="hidden" ng-model="maxPrice" />
                </div>
            </div>

            <?php if (count($amenities)): ?>
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

                        <div class="amenity_group col-md-3 col-sm-6 col-xs-6" style="margin-bottom:24px">
                            <label><?php echo $group; ?></label>
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
        </div>



        <div class="form-group">
            <button id="btn-collapse" class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters">
                <i class="glyphicon glyphicon-menu-hamburger"></i> <span><?php _e( 'Show more filters', 'streamline-core' ) ?></span>
            </button>
<!--
            <button type="submit" class="btn btn-sm btn-success" ng-click="submit()">
                <i class="fa fa-fw fa-search"></i> Narrow Results
            </button> -->
        </div>

    </form>
</div>
