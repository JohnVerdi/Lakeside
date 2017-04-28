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
?>
<div id="primary" class="content-area" ng-controller="PropertyController as pCtrl">
    <div ng-init="<?php echo $scope_params; ?>">
        <main id="main" class="site-main" role="main" ng-init="<?php echo $method ?>">

            <div class="row">

                <?php if (is_active_sidebar('top_shortcode_widget')): ?>
                  <div class="col-md-12">
                    <?php dynamic_sidebar('top_shortcode_widget'); ?>
                  </div>
                <?php endif; ?>

                <div class="col-md-12">
                  <div class="loading" ng-show="loading">
                      <i class="fa fa-circle-o-notch fa-spin"></i> <?php _e( 'Loading...', 'streamline-core' ) ?>
                  </div>

                  <div ng-if="results.length == 0" ng-cloak>
                      <div class="alert alert-danger">
                          <p><?php _e( 'Unable to find any listings.', 'streamline-core' ) ?></p>
                      </div>
                  </div>
                </div>

                <div class="col-md-12">

                  <div class="row" ng-init="sortBy='<?php echo $sorted_by; ?>'">

                  <?php if($options['property_show_sorting_options']): ?>
                    <div class="col-md-12">
                                <div class="row row-sort">

                                    <div class="col-lg-4 col-md-4 col-sm-10 col-xs-10" ng-init="sortBy='<?php echo $sorted_by; ?>'">
                                      <select class="form-control input-sm" ng-model="sortBy">
                                        <option value="default"><?php _e( 'Sort By', 'streamline-core' ) ?></option>
                                        <?php if ( (int)$options['sort_filter_occupants'] === 1 ) : ?>
                                          <option value="max_occupants"><?php _e( 'Occupants', 'streamline-core' ) ?></option>
                                        <?php endif; ?>
                                        <?php if ( (int)$options['sort_filter_bedrooms_number'] === 1 ) : ?>
                                          <option value="bedrooms_number"><?php _e( 'Bedrooms Number', 'streamline-core' ) ?></option>
                                        <?php endif; ?>
                                        <?php if ( (int)$options['sort_filter_bathrooms_number'] === 1 ) : ?>
                                          <option value="bathrooms_number"><?php _e( 'Bathrooms Number', 'streamline-core' ) ?></option>
                                        <?php endif; ?>
                                        <?php if ( (int)$options['sort_filter_name'] === 1 ) : ?>
                                          <option value="name"><?php _e( 'Unit Name', 'streamline-core' ) ?></option>
                                        <?php endif; ?>
                                        <?php if ( (int)$options['sort_filter_area'] === 1 ) : ?>
                                          <option value="square_foots"><?php _e( 'Square feet', 'streamline-core' ) ?></option>
                                        <?php endif; ?>
                                        <?php if ( (int)$options['sort_filter_view'] === 1 ) : ?>
                                          <option value="view_name"><?php _e( 'View', 'streamline-core' ) ?></option>
                                        <?php endif; ?>
                                        <?php if ( (int)$options['sort_filter_price'] === 1 ) : ?>
                                          <option value="price_low"><?php _e( 'Price', 'streamline-core' ) ?></option>
                                        <?php endif; ?>
                                        <?php if ( (int)$options['sort_filter_pets'] === 1 ) : ?>
                                          <option value="max_pets"><?php _e( 'Pets', 'streamline-core' ) ?></option>
                                        <?php endif; ?>
                                      </select>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <a href="" class="btn btn-sm btn-sort btn-default active">
                                            <i class="fa fa-sort-amount-asc" ng-click="sort=false"></i>
                                        </a>
                                        <a href="" class="btn btn-sm btn-sort btn-default">
                                            <i class="fa fa-sort-amount-desc" ng-click="sort=true"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                  <?php endif; ?>

                  <div class="listings_wrapper_box">
                    <div class="listings_wrapper_box" ng-init="limit = 1000">
                      <div ng-repeat="property in propertiesObj | orderBy: customSorting : sort | limitTo: limit as results">
                        <?php include $template; ?>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                  </div>
              </div>
            </div>
        </main>
        <div class="modal fade" id="myModal2" tabindex="-2" role="dialog" aria-labelledby="myModalLabel2">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <form method="post" name="resortpro_inquiry" novalidate>
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel"><?php _e( 'Property Inquiry', 'streamline-core' ) ?></h4>
                        </div>
                        <div class="modal-body">

                                <input type="hidden" ng-model="inquiry.unit_id">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control"
                                                   name="inquiry_first_name"
                                                   id="inquiry_first_name"
                                                   placeholder="<?php _e( 'Name', 'streamline-core' ) ?>"
                                                   ng-required="true"
                                                   ng-model="inquiry.first_name"/>
                                            <div ng-show="resortpro_inquiry.$submitted || resortpro_inquiry.inquiry_first_name.$touched">
                                                <span class="error" ng-show="resortpro_inquiry.inquiry_first_name.$error.required" ng-bind="'<?php _e( 'First name is required.', 'streamline-core' ) ?>'"></span>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control"
                                                   name="inquiry_last_name"
                                                   id="inquiry_last_name"
                                                   placeholder="<?php _e( 'Last Name', 'streamline-core' ) ?>"
                                                   ng-required="true"
                                                   ng-model="inquiry.last_name"/>
                                            <div ng-show="resortpro_inquiry.$submitted || resortpro_inquiry.inquiry_last_name.$touched">
                                                <span class="error" ng-show="resortpro_inquiry.inquiry_last_name.$error.required" ng-bind="'<?php _e( 'Last name is required.', 'streamline-core' ) ?>'"></span>

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
                                                   placeholder="<?php _e( 'Email', 'streamline-core' ) ?>"
                                                   ng-required="true"
                                                   ng-model="inquiry.email"/>
                                            <div ng-show="resortpro_inquiry.$submitted || resortpro_inquiry.inquiry_email.$touched">
                                                <span class="error" ng-show="resortpro_inquiry.inquiry_email.$error.required && resortpro_inquiry.inquiry_phone.$error.required" ng-bind="'<?php _e( 'Email or phone is required.', 'streamline-core' ) ?>'"></span>
                                                <span class="error" ng-show="resortpro_inquiry.inquiry_email.$error.email" ng-bind="'<?php _e( 'This is not a valid email.', 'streamline-core' ) ?>'"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control"
                                                   name="inquiry_phone"
                                                   id="inquiry_phone"
                                                   placeholder="<?php _e( 'Phone', 'streamline-core' ) ?>"
                                                   ng-required="true"
                                                   ng-model="inquiry.phone"/>
                                            <div ng-show="resortpro_inquiry.$submitted || resortpro_inquiry.inquiry_phone.$touched">
                                                <span class="error" ng-show="resortpro_inquiry.inquiry_email.$error.required && resortpro_inquiry.inquiry_phone.$error.required" ng-bind="'<?php _e( 'Phone or email is required.', 'streamline-core' ) ?>'"></span>
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
                                                   placeholder="<?php _e( 'Checkin', 'streamline-core' ) ?>"
                                                   data-bindpicker="#inquiry_enddate"
                                                   ng-required="true"
                                                   ng-model="inquiry.startDate"/>
                                            <div ng-show="resortpro_inquiry.$submitted || resortpro_inquiry.inquiry_startdate.$touched">
                                                <span class="error" ng-show="resortpro_inquiry.inquiry_startdate.$error.required" ng-bind="'<?php _e( 'Checkin is required.', 'streamline-core' ) ?>'"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xs-6 col-sm-6">
                                            <input type="text" class="form-control datepicker"
                                                   name="inquiry_enddate"
                                                   id="inquiry_enddate"
                                                   placeholder="<?php _e( 'Checkout', 'streamline-core' ) ?>"
                                                   ng-required="true"
                                                   ng-model="inquiry.endDate"/>

                                            <div ng-show="resortpro_inquiry.$submitted || resortpro_inquiry.inquiry_enddate.$touched">
                                                <span class="error" ng-show="resortpro_inquiry.inquiry_enddate.$error.required" ng-bind="'<?php _e( 'Checkout is required.', 'streamline-core' ) ?>'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <label for="inquiry_occupants"><?php _e( 'Adults', 'streamline-core' ) ?></label>
                                            <select class="form-control"
                                                    name="inquiry_occupants"
                                                    id="inquiry_occupants"
                                                    ng-model="inquiry.occupants">
                                                <?php for($i = 1; $i <= $max_occupants; $i++): ?>
                                                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <label for="inquiry_occupants_small"><?php _e( 'Children', 'streamline-core' ) ?></label>
                                            <select class="form-control"
                                                    name="inquiry_occupants_small"
                                                    id="inquiry_occupants_small"
                                                    ng-model="inquiry.occupantsSmall">
                                                <?php for($i = 1; $i <= $max_occupants_small; $i++): ?>
                                                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <label for="inquiry_pets"><?php _e( 'Pets', 'streamline-core' ) ?></label>
                                            <select class="form-control"
                                                    name="inquiry_pets"
                                                    id="inquiry_pets"
                                                    ng-model="inquiry.pets">
                                                <?php for($i = 1; $i <= $max_pets; $i++): ?>
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
                                                  placeholder="<?php _e( 'Question or Comment', 'streamline-core' ) ?>"
                                                  ng-model="inquiry.message"></textarea>
                                        </div>
                                    </div>
                                </div>

                             <div class="alert alert-{[alert.type]} animate"
                                     ng-repeat="alert in alerts">
                                        <div ng-bind-html="alert.message | trustedHtml"></div>
                                    </div>
                        </div>
                        <div class="modal-footer">
                                <a type="button"
                                        class="btn btn-default"
                                        data-dismiss="modal"
                                        ng-click="resetInquiry(inquiry)">
                                    <?php _e( 'Close', 'streamline-core' ) ?>
                                </a>
                                <button type="submit"
                                        id="resortpro_unit_submit"
                                        ng-click="validateInquiry(inquiry,true)"
                                        class="btn btn-success">
                                    <i class="glyphicon glyphicon-comment"></i> <?php _e( 'Send Inquiry', 'streamline-core' ) ?>
                                </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
    <!-- .site-main -->
</div><!-- .content-area -->
