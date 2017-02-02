<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all resortpro listings by default.
 * You can override this template by creating your own "my_theme/template/page-resortpro-listings.php" file
 *
 * @package    ResortPro
 * @since      v1.0
 */
$options = StreamlineCore_Settings::get_options();


$str_bs_class = apply_filters('streamline-search-results-default-col', (is_active_sidebar('side_search_widget')) ? '8' : '12' );
$box_class = ($options['page_layout'] == 'boxed') ? 'container' : 'container-fluid';
?>

<div id="primary" class="content-area" ng-controller="PropertyController as pCtrl" ng-cloak>

    <div ng-init="<?php echo apply_filters('streamline-search-results-params', $str_params); ?>">
        <main id="main" class="site-main" role="main"
              ng-init="requestPropertyList('<?php echo $method; ?>');">

            <?php do_action('streamline-search-results-start'); ?>
            <div class="row">

                <div class="col-md-<?php echo $str_bs_class ?>">

                    <div class="row search_results_filter_area">

                        <?php if (is_active_sidebar('top_search_widget')): ?>
                            <div class="col-md-12">
                                <?php dynamic_sidebar('top_search_widget'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="loading" ng-show="loading">
                                        <i class="fa fa-circle-o-notch fa-spin"></i> <?php _e('Loading...', 'streamline-core') ?>
                                    </div>
                                    <?php echo apply_filters('show-unit-count-top', '<div ng-if="!loading && total_units > 0"><p class="search-pagination">' . sprintf(__('Showing %s of %s properties', 'streamline-core'), '<span ng-bind="results.length"></span>', '<span ng-bind="total_units"></span>') . '</p></div>'); ?>
                                </div>
                                <div class="col-md-6 text-right favorites">
                                    <?php if($use_favorites): ?>
                                    <div>
                                        <p>
                                            <i class="fa fa-heart"></i>
                                            <a href="<?php echo get_permalink(get_page_by_slug('favorites')) ?>">
                                                Favorites (<span ng-bind="wishlist.length"></span>)
                                            </a>
                                        </p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div ng-show="!loading" ng-if="isEmptyObject(results) || results.length == 0" ng-cloak>
                                <div class="alert alert-danger">
                                    <p><?php echo $noinv_msg ?>
                                        <span ng-if="mapEnabled">
                                            <?php _e('Please click', 'streamline-core') ?>
                                            <a href="#" ng-click="disableMapSearch();"><?php _e('here', 'streamline-core') ?></a>
                                            <?php _e('to disable map search and reload all the units.', 'streamline-core') ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

<!--                        --><?php //if ($options['property_show_sorting_options']): ?>

                            <div class="col-md-12">
                                <div class="row row-sort">
                                    <?php
                                    $default = ($sorted_by == 'random' || $sorted_by == 'rotation') ? $sorted_by : 'default';
                                    ?>
                                    <div class="col-lg-4 col-md-4 col-sm-10 col-xs-10" ng-init="sortBy = '<?php echo $sorted_by; ?>'">
                                        <select class="form-control input-sm" ng-model="sortBy" ng-change="checkSorting()">
                                            <option value="<?php echo $default ?>"><?php _e('Sort By', 'streamline-core') ?></option>
                                            <?php if ((int) $options['sort_filter_occupants'] === 1) : ?>
                                                <option value="max_occupants"><?php _e('Occupants (high to low)', 'streamline-core') ?></option>
                                            <?php endif; ?>
                                            <?php if ((int) $options['sort_filter_occupants_min'] === 1) : ?>
                                                <option value="min_occupants"><?php _e('Occupants (low to high)', 'streamline-core') ?></option>
                                            <?php endif; ?>
                                            <?php if ((int) $options['sort_filter_bedrooms_number'] === 1) : ?>
                                                <option value="bedrooms_number"><?php _e('Bedrooms Number (high to low)', 'streamline-core') ?></option>
                                            <?php endif; ?>
                                            <?php if ((int) $options['sort_filter_bedrooms_number_min'] === 1) : ?>
                                                <option value="min_bedrooms_number"><?php _e('Bedrooms Number (low to high)', 'streamline-core') ?></option>
                                            <?php endif; ?>
                                            <?php if ((int) $options['sort_filter_bathrooms_number'] === 1) : ?>
                                                <option value="bathrooms_number"><?php _e('Bathrooms Number', 'streamline-core') ?></option>
                                            <?php endif; ?>
                                            <?php if ((int) $options['sort_filter_name'] === 1) : ?>
                                                <option value="name"><?php _e('Unit Name', 'streamline-core') ?></option>
                                            <?php endif; ?>
                                            <?php if ((int) $options['sort_filter_area'] === 1) : ?>
                                                <option value="square_foots"><?php _e('Square feet', 'streamline-core') ?></option>
                                            <?php endif; ?>
                                            <?php if ((int) $options['sort_filter_view'] === 1) : ?>
                                                <option value="view_name"><?php _e('View', 'streamline-core') ?></option>
                                            <?php endif; ?>
                                            <?php if ((int) $options['sort_filter_price'] === 1) : ?>
                                                <option value="price"><?php _e('Price (hight to low)', 'streamline-core') ?></option>
                                            <?php endif; ?>
                                            <?php if ((int) $options['sort_filter_price_low'] === 1) : ?>
                                                <option value="price_low"><?php _e('Price (low to high)', 'streamline-core') ?></option>
                                            <?php endif; ?>
                                            <?php if ((int) $options['sort_filter_pets'] === 1) : ?>
                                                <option value="pets"><?php _e('Pets', 'streamline-core') ?></option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                </div>
                            </div>
<!--                        --><?php //endif; ?>

                        <div class="col-md-12">
                            <button  ng-click="sortBy(checkFavoritesRev(property))" ng-class="{reverse: reverse}">sort</button>
                            <div class="row listings_wrapper_box " ng-init="limit = searchSettings.propertyPagination">
                                <div class="row row-wrap loop_hotel loop_grid_hotel style_box" ng-repeat="property in propertiesObj| orderBy: customSorting:reverse as results">
                                    <?php
                                    include($template);
                                    ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="streamline-pagination-wrapper text-center" ng-show="!loading">
                                <button class="btn btn-primary"
                                        ng-if="total_units > 0"
                                        ng-hide="limit > results.length"
                                        ng-click="loadMore();"><?php _e('Show More', 'streamline-core') ?></button><br />
                                        <?php echo apply_filters('show-unit-count-bottom', '<p class="search-pagination" ng-if="!loading && total_units > 0">' . sprintf(__('Showing %s of %s properties', 'streamline-core'), '<span ng-bind="results.length"></span>', '<span ng-bind="total_units"></span>') . '</p>'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($str_bs_class == '8'): ?>

                    <div class="col-md-4 search-sidebar">
                        <?php dynamic_sidebar('side_search_widget'); ?>
                    </div>

                <?php endif; ?>

                <div class="clearfix"></div>
            </div>
        </main>
    </div>
    <!-- .site-main -->
    <div class="modal fade" id="myModal2" tabindex="-2" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form method="post" name="resortpro_inquiry" novalidate>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="<?php _e('Close', 'streamline-core') ?>"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"><?php _e('Property Inquiry', 'streamline-core') ?></h4>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" ng-model="inquiry.unit_id">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control"
                                           name="inquiry_first_name"
                                           id="inquiry_first_name"
                                           placeholder="<?php _e('Name', 'streamline-core') ?>"
                                           ng-required="true"
                                           ng-model="inquiry.first_name"/>
                                    <div ng-show="resortpro_inquiry.$submitted || resortpro_inquiry.inquiry_first_name.$touched">
                                        <span class="error" ng-show="resortpro_inquiry.inquiry_first_name.$error.required" ng-bind="'<?php _e('First name is required.', 'streamline-core') ?>'"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control"
                                           name="inquiry_last_name"
                                           id="inquiry_last_name"
                                           placeholder="<?php _e('Last Name', 'streamline-core') ?>"
                                           ng-required="true"
                                           ng-model="inquiry.last_name"/>
                                    <div ng-show="resortpro_inquiry.$submitted || resortpro_inquiry.inquiry_last_name.$touched">
                                        <span class="error" ng-show="resortpro_inquiry.inquiry_last_name.$error.required" ng-bind="'<?php _e('Last name is required.', 'streamline-core') ?>'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control"
                                           name="inquiry_email"
                                           id="inquiry_email"
                                           placeholder="<?php _e('Email', 'streamline-core') ?>"
                                           ng-required="true"
                                           ng-model="inquiry.email"/>

                                    <div ng-show="resortpro_inquiry.$submitted || resortpro_inquiry.inquiry_email.$touched">
                                        <span class="error" ng-show="resortpro_inquiry.inquiry_email.$error.required && resortpro_inquiry.inquiry_phone.$error.required" ng-bind="'<?php _e('Email or phone is required.', 'streamline-core') ?>'"></span>
                                        <span class="error" ng-show="resortpro_inquiry.inquiry_email.$error.email" ng-bind="'<?php _e('This is not a valid email.', 'streamline-core') ?>'"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control"
                                           name="inquiry_phone"
                                           id="inquiry_phone"
                                           placeholder="<?php _e('Phone', 'streamline-core') ?>"
                                           ng-required="true"
                                           ng-model="inquiry.phone"/>
                                    <div ng-show="resortpro_inquiry.$submitted || resortpro_inquiry.inquiry_phone.$touched">
                                        <span class="error" ng-show="resortpro_inquiry.inquiry_email.$error.required && resortpro_inquiry.inquiry_phone.$error.required" ng-bind="'<?php _e('Phone or email is required.', 'streamline-core') ?>'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-xs-6 col-sm-6">
                                    <input type="text" class="form-control datepicker"
                                           name="inquiry_startdate"
                                           id="inquiry_startdate"
                                           placeholder="<?php _e('Checkin', 'streamline-core') ?>"
                                           data-bindpicker="#inquiry_enddate"
                                           ng-required="true"
                                           ng-model="inquiry.startDate"/>
                                    <div ng-show="resortpro_inquiry.$submitted || resortpro_inquiry.inquiry_startdate.$touched">
                                        <span class="error" ng-show="resortpro_inquiry.inquiry_startdate.$error.required" ng-bind="'<?php _e('Checkin is required.', 'streamline-core') ?>'"></span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6 col-sm-6">
                                    <input type="text" class="form-control datepicker"
                                           name="inquiry_enddate"
                                           id="inquiry_enddate"
                                           placeholder="<?php _e('Checkout', 'streamline-core') ?>"
                                           ng-required="true"
                                           ng-model="inquiry.endDate"/>

                                    <div ng-show="resortpro_inquiry.$submitted || resortpro_inquiry.inquiry_enddate.$touched">
                                        <span class="error" ng-show="resortpro_inquiry.inquiry_enddate.$error.required" ng-bind="'<?php _e('Checkout is required.', 'streamline-core') ?>'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <label for="inquiry_occupants"><?php _e('Adults', 'streamline-core') ?></label>
                                    <select class="form-control"
                                            name="inquiry_occupants"
                                            id="inquiry_occupants"
                                            ng-model="inquiry.occupants">
                                                <?php for ($i = 1; $i <= $max_occupants; $i++): ?>
                                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <label for="inquiry_occupants_small"><?php _e('Children', 'streamline-core') ?></label>
                                    <select class="form-control"
                                            name="inquiry_occupants_small"
                                            id="inquiry_occupants_small"
                                            ng-model="inquiry.occupantsSmall">
                                                <?php for ($i = 1; $i <= $max_occupants_small; $i++): ?>
                                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <label for="inquiry_pets"><?php _e('Pets', 'streamline-core') ?></label>
                                    <select class="form-control"
                                            name="inquiry_pets"
                                            id="inquiry_pets"
                                            ng-model="inquiry.pets">
                                                <?php for ($i = 1; $i <= $max_pets; $i++): ?>
                                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea class="form-control"
                                              name="inquiry_message"
                                              id="inquiry_message"
                                              placeholder="<?php _e('Question or Comment', 'streamline-core') ?>"
                                              ng-model="inquiry.message"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-{[alert.type]} animate" ng-repeat="alert in alerts">
                            <div ng-bind-html="alert.message | trustedHtml"></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <a type="button"
                           class="btn btn-default"
                           data-dismiss="modal"
                           ng-click="resetInquiry(inquiry)">
                               <?php _e('Close', 'streamline-core') ?>
                        </a>
                        <button type="submit"
                                id="resortpro_unit_submit"
                                ng-click="validateInquiry(inquiry, true)"
                                class="btn btn-success">
                            <i class="glyphicon glyphicon-comment"></i> <?php _e('Send Inquiry', 'streamline-core') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><!-- .content-area -->
<script type="text/javascript">
        jQuery('.show_hide_filter').on('click', function () {
        jQuery('.row.row-price-range').slideToggle();
        jQuery('.row.row-amenities').slideToggle();
        jQuery('.filter_s').toggle();
        jQuery('.filter_h').toggle();
    });
</script>
