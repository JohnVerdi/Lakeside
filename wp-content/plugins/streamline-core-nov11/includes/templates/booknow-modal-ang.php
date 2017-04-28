<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria - labelledby="myModalLabel">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <form class="form-horizontal" id="modal_form"
                  action="<?php echo get_permalink(get_page_by_slug('checkout')) ?>" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data - dismiss="modal" aria - label="<?php _e( 'Close', 'streamline-core' ) ?>"><span
                            aria - hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo $location_name ?></h4>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="resortpro_book_unit" value="<?php echo $nonce ?>"/>
                    <input type="hidden" name="book_unit" value="<?php echo $unit_id ?>"/>

                    <div class="row">
                        <div class="alert alert-{[alert.type]} animate alert-dismissible" role="alert"
                            ng-repeat="alert in alerts">
                            <button type="button" class="close" data-dismiss="alert" aria-label="<?php _e( 'Close', 'streamline-core' ) ?>"><span
                            aria-hidden="true">&times;</span></button>
                            <div ng-bind-html="alert.message | trustedHtml"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sd" class="col-sm-4 control-label"><?php _e( 'Check in:', 'streamline-core' ) ?></label>

                                <div class="col-sm-8">
                                    <input type="text"
                                    ng-model="modal_checkin"
                                    id="modal_start_date"
                                    name="book_start_date"
                                    class="form-control"
                                    show-days="myShowDaysFunction(date)"
                                    update-price="updatePricePopupCalendar()"
                                    modalcheckin
                                    readonly="readonly"
                                    data-min-stay="<?php echo $min_stay ?>"
                                    data-checkin-days="<?php echo $checkin_days ?>"
                                    />
                                </div>
                            </div>

                            <div class="form-group" id="group-enddate" ng-show="!showDays">
                                <label for="ed" class="col-sm-4 control-label"><?php _e( 'Check out:', 'streamline-core' ) ?></label>
                                <div class="col-sm-8">
                                    <input type="text"
                                    ng-model="modal_checkout"
                                    id="modal_end_date"
                                    name="book_end_date"
                                    class="form-control"
                                    show-days="myShowDaysFunction(date)"
                                    update-price="updatePricePopupCalendar()"
                                    modalcheckout
                                    readonly="readonly"
                                    data-min-stay="<?php echo $min_stay ?>"
                                    data-checkin-days="<?php echo $checkin_days ?>"
                                    />
                                </div>
                            </div>
                            <div class="form-group" id="group-days" ng-show="showDays">
                                <label for="modal_days" class="col-sm-4 control-label"><?php _e( 'Nights:', 'streamline-core' ) ?></label>
                                <div class="col-sm-8">
                                    <select id="modal_days" class="form-control" ng-model="modal_nights" ng-change="setNights()">
                                        <option value=""><?php _e( 'Select # of nights', 'streamline-core' ) ?></option>
                                        <?php for($i = 1; $i <= 60; $i++): ?>
                                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" ng-show="!showDays" ng-init="modal_occupants='1'">
                                <label class="col-sm-4 control-label" for="modal_occupants"><?php _e( 'Adults', 'streamline-core' ) ?></label>
                                <div class="col-sm-8">
                                    <select
                                        id="modal_occupants"
                                        ng-model="modal_occupants"
                                        ng-change="updatePricePopupCalendar();"
                                        name="book_occupants"
                                        class="form-control">
                                        <?php
                                        $max_adults = 10;
                                        for ($i = 1; $i <= $max_adults; $i++) {
                                        echo "<option value=\"{$i}\">{$i}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" ng-show="!showDays"  ng-init="modal_occupants_small='0'">
                                <label class="col-sm-4 control-label" for="modal_occupants_small"><?php _e( 'Children', 'streamline-core' ) ?></label>
                                <div class="col-sm-8">
                                    <select
                                        id="modal_occupants_small"
                                        name="book_occupants_small"
                                        class="form-control"
                                        ng-model="modal_occupants_small"
                                        ng-change="updatePricePopupCalendar();">
                                        <?php
                                        for ($i = 0; $i <= $max_guests; $i++) {
                                            echo "<option value=\"{$i}\">{$i}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php if ($max_pets > 0): ?>

                                <div class="form-group" ng-show="!showDays" ng-init="modal_pets='0'">
                                    <label class="col-sm-4 control-label" for="modal_pets"><?php _e( 'Pets', 'streamline-core' ) ?></label>
                                    <div class="col-sm-8">
                                        <select
                                            id="modal_pets"
                                            name="book_pets"
                                            class="form-control"
                                            ng-model="modal_pets"
                                            ng-change="updatePricePopupCalendar();">
                                            <?php
                                            for ($i = 0; $i <= $max_pets; $i++) {
                                            echo "<option value=\"{$i}\">{$i}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <table ng-show="modal_total_reservation > 0"
                            class="table table-stripped table-bordered table-condensed">
                                <tr ng-if="modal_days" ng-repeat="day in modal_reservation_days">
                                    <td ng-bind="day.date"></td>
                                    <td class="text-right" ng-bind="calculateMarkup(day.price.toString()) | currency:undefined:2"></td>
                                </tr>

                                <tr style="border-top:solid 2px #333">
                                    <td><?php _e( 'Subtotal', 'streamline-core' ) ?></td>
                                    <td class="text-right" ng-bind="modal_rent + modal_coupon_discount | currency:undefined:2"></td>
                                </tr>
                                <tr ng-if="modal_coupon_discount > 0">
                                    <td><?php _e( 'Discount', 'streamline-core' ) ?></td>
                                    <td class="text-right" ng-bind="modal_coupon_discount | currency:undefined:2"></td>
                                </tr>
                                <tr>
                                    <td><?php _e( 'Taxes & Fees', 'streamline-core' ) ?></td>
                                    <td class="text-right" ng-bind="modal_taxes | currency:undefined:2"></td>
                                </tr>
                                <tr>
                                    <td><?php _e( 'Total', 'streamline-core' ) ?></td>
                                    <td class="text-right" ng-bind="modal_total_reservation | currency:undefined:2"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" ng-click="resetCalendarPopup()"><?php _e( 'Cancel', 'streamline-core' ) ?></button>

                    <button type="button" id="btn-modal-book" class="btn btn-success" ng-disabled="modal_total_reservation == 0">
                      <?php _e( 'Make Reservation', 'streamline-core' ) ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
