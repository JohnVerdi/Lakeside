<form class="frm-property-inquiry" name="resortpro_inquiry" ng-init="inquiry.unit_id=<?php echo $unit_id ?>" novalidate>
    <input type="hidden" ng-model="inquiry.unit_id">
<?php if($is_modal): ?>
    <div class="modal fade" id="myModal2" tabindex="-2" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="<?php _e( 'Close', 'streamline-core' ) ?>"><span
                    aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php _e( 'Property Inquiry', 'streamline-core' ) ?></h4>
                </div>
                <div class="modal-body">
<?php endif; ?>

    <div class="form-group">
        <div class="row">
            <div class="col-md-12">
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
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-12">
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
            <div class="col-md-12">
                <input type="email"
                class="form-control"
                name="inquiry_email"
                id="inquiry_email"
                placeholder="<?php _e( 'Email', 'streamline-core' ) ?>"
                ng-required="true"
                ng-model="inquiry.email"/>
                <div ng-show="resortpro_inquiry.$submitted || resortpro_inquiry.inquiry_email.$touched">
                    <span class="error" ng-show="resortpro_inquiry.inquiry_email.$error.required && resortpro_inquiry.inquiry_phone.$error.required"                    ng-bind="'<?php _e( 'Email or phone is required.', 'streamline-core' ) ?>'"></span>
                    <span class="error" ng-show="resortpro_inquiry.inquiry_email.$error.email" 
                                        ng-bind="'<?php _e( 'This is not a valid email.', 'streamline-core' ) ?>'"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-12">
                <input type="text" class="form-control"
                name="inquiry_phone"
                id="inquiry_phone"
                placeholder="<?php _e( 'Phone', 'streamline-core' ) ?>"
                ng-model="inquiry.phone"
                ng-required="true"
                />

                <div ng-show="resortpro_inquiry.$submitted || resortpro_inquiry.inquiry_phone.$touched">
                    <span class="error" ng-show="resortpro_inquiry.inquiry_email.$error.required && resortpro_inquiry.inquiry_phone.$error.required" 
                                        ng-bind="'<?php _e( 'Phone or email is required.', 'streamline-core' ) ?>'"></span>

                </div>

            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6 col-xs-6 col-sm-6" ng-init="inquiry.startDate='<?php echo $start_date; ?>'">
                <input type="text" class="form-control datepicker"
                data-bindpicker="#inquiry_enddate"
                name="inquiry_startdate"
                id="inquiry_startdate"
                placeholder="<?php _e( 'Checkin', 'streamline-core' ) ?>"
                ng-required="true"
                ng-model="inquiry.startDate"/>

                <div ng-show="resortpro_inquiry.$submitted || resortpro_inquiry.inquiry_startdate.$touched">
                    <span class="error" ng-show="resortpro_inquiry.inquiry_startdate.$error.required" 
                                        ng-bind="'<?php _e( 'Checkin is required.', 'streamline-core' ) ?>'"></span>

                </div>

            </div>
            <div class="col-md-6 col-xs-6 col-sm-6" ng-init="inquiry.endDate='<?php echo $end_date; ?>'">
                <input type="text" class="form-control datepicker"
                name="inquiry_enddate"
                id="inquiry_enddate"
                placeholder="<?php _e( 'Checkout', 'streamline-core' ) ?>"
                ng-required="true"
                ng-model="inquiry.endDate"/>

                <div ng-show="resortpro_inquiry.$submitted || resortpro_inquiry.inquiry_enddate.$touched">
                    <span class="error" ng-show="resortpro_inquiry.inquiry_enddate.$error.required" 
                                        ng-bind="'<?php _e( 'Checkout is required.', 'streamline-core' ) ?>'"></span>

                </div>

            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-4" ng-init="inquiry.occupants='<?php echo $occupants; ?>'">
                <label for="inquiry_occupants"><?php _e( 'Adults', 'streamline-core' ) ?></label>
                <select class="form-control"
                    name="inquiry_occupants"
                    id="inquiry_occupants"
                    ng-model="inquiry.occupants">
                    <?php
                    for ($i = 1; $i <= $max_adults; $i++) {
                        echo "<option value=\"{$i}\">{$i}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4" ng-init="inquiry.occupantsSmall='<?php echo $occupants_small; ?>'">
                <label for="inquiry_occupants_small"><?php _e( 'Children', 'streamline-core' ) ?></label>
                <select class="form-control"
                    name="inquiry_occupants_small"
                    id="inquiry_occupants_small"
                    ng-model="inquiry.occupantsSmall">
                    <?php
                    for ($i = 0; $i <= $max_guests; $i++) {
                    echo "<option value=\"{$i}\">{$i}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4" ng-init="inquiry.pets='<?php echo $pets; ?>'">
                <label for="inquiry_pets"><?php _e( 'Pets', 'streamline-core' ) ?></label>
                <select class="form-control"
                    name="inquiry_pets"
                    id="inquiry_pets"
                    ng-model="inquiry.pets">
                    <?php
                    for ($i = 0; $i <= $max_pets; $i++) {
                    echo "<option value=\"{$i}\">{$i}</option>";
                    }
                    ?>
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
    <div class="form-group">
        <button type="submit"
                id="resortpro_unit_submit"
                ng-click="validateInquiry(inquiry,true)"
                class="btn btn-lg btn-block btn-primary">
            <i class="glyphicon glyphicon-comment"></i> <?php _e( 'Send Inquiry', 'streamline-core' ) ?>
        </button>
    </div>

    <div class="alert alert-{[alert.type]} animate"
        ng-repeat="alert in alerts">
        <div ng-bind-html="alert.message | trustedHtml"></div>
    </div>
<?php if($is_modal): ?>
                </div> <!-- modal-body -->
            </div>  <!-- modal-content -->
        </div>  <!-- modal dialog -->
    </div> <!-- modal -->
<?php endif; ?>
</form>
