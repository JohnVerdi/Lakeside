<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria - labelledby="myModalLabel">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <form class="form-horizontal" id="modal_form"
                  action="<?php echo get_permalink(get_page_by_slug('checkout')) ?>" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data - dismiss="modal" aria - label="<?php _e( 'Close', 'streamline-core' ) ?>"><span
                            aria - hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo $property['name'] ?></h4>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="resortpro_book_unit" value="<?php echo $nonce ?>"/>
                    <input type="hidden" name="book_unit" value="<?php echo $property['id'] ?>"/>

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
                                    update-price="updatePricePopupCalendar(modal,1)"
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
                                    update-price="updatePricePopupCalendar(modal,1)"
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
                                        <?php for ( $i = 1; $i <= 30; $i++ ) : ?>
                                          <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="group-adults" style="display:none">
                                <label for="modal_days" class="col-sm-4 control-label"><?php _e( 'Adults:', 'streamline-core' ) ?></label>

                                <div class="col-sm-8">
                                    <select name="book_occupants" id="modal_adults" class="form-control">
                                        <option value="1" selected>1</option>
                                        <?php
                                        for($i = 2; $i<=$property['max_occupants']; $i++){
                                            echo "<option value=\"{$i}\">{$i}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="group-childs" style="display:none">
                                <label for="ch" class="col-sm-4 control-label"><?php _e( 'Children:', 'streamline-core' )?></label>

                                <div class="col-sm-8">
                                    <select name="ch" id="modal_childs" class="form-control">
                                        <option value="1" selected>1</option>
                                        <?php
                                        for($i = 2; $i<=$property['max_occupants']; $i++){
                                            echo "<option value=\"{$i}\">{$i}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <table ng-show="modal_total_reservation > 0"
                            class="table table-stripped table-bordered table-condensed">
                            <tr ng-if="modal_days" ng-repeat="day in modal_reservation_days">
                                <td ng-cloak>{[day.date]}</td>
                                <td class="text-right" ng-cloak>{[day.price | currency:undefined:0 ]}</td>
                            </tr>

                            <tr style="border-top:solid 2px #333">
                                <td><?php _e( 'Subtotal', 'streamline-core' ) ?></td>
                                <td class="text-right" ng-cloak>{[modal_rent + modal_coupon_discount | currency:undefined:0]}</td>
                            </tr>
                            <tr ng-if="coupon_discount > 0">
                                <td><?php _e( 'Discount', 'streamline-core' ) ?></td>
                                <td class="text-right">{[modal_coupon_discount | currency:undefined:0]}
                            </tr>
                            <tr>
                                <td><?php _e( 'Taxes and fees', 'streamline-core' ) ?></td>
                                <td class="text-right" ng-cloak>{[modal_taxes | currency:undefined:0]}</td>
                            </tr>
                            <tr>
                                <td><?php _e( 'Total', 'streamline-core' ) ?></td>
                                <td class="text-right" ng-cloak>{[modal_total_reservation | currency:undefined:0]}</td>
                            </tr>
                        </table>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e( 'Cancel', 'streamline-core' ) ?></button>
                    <button type="button" id="btn-modal-book" class="btn btn-success" disabled="disabled"><?php _e( 'Make Reservation', 'streamline-core' ) ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
