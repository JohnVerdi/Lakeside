<div
    class="col-md-4" id="resortpro-book-unit"
     ng-init="maxOccupants='<?php echo $max_children; ?>';
     isDisabled=true;
     total_reservation=0;
     book.unit_id=<?php echo $property['id'] ?>;
     book.checkin='<?php echo $start_date; ?>';
     bookNowPrice=<?php echo $property['price_data']['daily'];?>;
     book.checkout='<?php echo $end_date ?>';
     getPreReservationPrice(book,<?php echo $res ?>)"
>
    <div class="book-now-title-wrap">
        <h3 class="text-center book-now-title" ng-cloak >${[ bookNowPrice ]}/night</h3>
    </div>

    <div class="inquiry right-side top">



    <form action="<?php echo $checkout_url ?>" method="post" name="resortpro_form_checkout" class="resortpro_form_checkout" novalidate>
        <fieldset ng-disabled="isCheckoutProcess">
            <input type="hidden" name="resortpro_book_unit" value="<?php echo $nonce; ?>"/>
            <input type="hidden" name="book_unit" value="<?php echo $property['id'] ?>"/>
            <?php if(!empty($hash)): ?>
                <input type="hidden" name="hash" value="<?php echo $hash; ?>" />
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group fa-calendar-wrapper">
                        <label for="book_start_date"><?php _e( 'Check in', 'streamline-core' ) ?></label>
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <input type="text"
                           ng-model="book.checkin"
                           ng-change="checkInMoreThenCheckOut = false"
                           id="book_start_date"
                           name="book_start_date"
                           class="form-control"
                           show-days="renderCalendar(date, false)"
                           update-price="getPreReservationPrice(book,1)"
                           update-checkout="setCheckoutDate(date)"
                           bookcheckin
                           data-min-stay="<?php echo $min_stay ?>"
                           data-checkin-days="<?php echo $checkin_days ?>"
                        />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group fa-calendar-wrapper">
                        <label for="book_end_date"><?php _e( 'Check out', 'streamline-core' ) ?></label>
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <input type="text"
                               ng-model="book.checkout"
                               ng-change="checkInMoreThenCheckOut = false"
                               id="book_end_date"
                               name="book_end_date"
                               class="form-control"
                               show-days="renderCalendar(date, true)"
                               update-price="getPreReservationPrice(book,1)"
                               bookcheckout
                        />
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group" ng-init="book.occupants='<?php echo $occupants; ?>'">
                        <label for="book_occupants"><?php echo $adults_label ?></label>
                        <select
                            id="book_occupants"
                            ng-model="book.occupants"
                            ng-change="getPreReservationPrice(book,1);"
                            name="book_occupants"
                            class="form-control brad-5">
                            <?php
                            for ($i = 1; $i <= $max_adults; $i++) {
                                echo "<option value=\"{$i}\">{$i}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <?php if ($max_children > 0): ?>
                    <div class="col-md-6">
                        <div class="form-group" ng-init="book.occupants_small='<?php echo $occupants_small; ?>'">
                            <label for="book_occupants_small"><?php echo $children_label ?></label>
                            <select
                                id="book_occupants_small"
                                name="book_occupants_small"
                                class="form-control brad-5"
                                ng-model="book.occupants_small"
                                ng-change="getPreReservationPrice(book,1);">
                                <?php
                                for ($i = 0; $i <= $max_children; $i++) {
                                    echo "<option value=\"{$i}\">{$i}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group extras-wrap" ng-cloak ng-show="optional_fees.length">
                <h4 data-toggle="collapse"
                    data-target="#extras_top"
                    aria-expanded="true"
                    aria-controls="extras_top"
                    ng-click="isTopExtrasArrowActive = !isTopExtrasArrowActive">
                    <?php _e( 'Extras', 'streamline-core' ) ?>
                    <i class="fa fa-angle-up fa-angle-button" aria-hidden="true" ng-show="isTopExtrasArrowActive" ng-cloak></i>
                    <i class="fa fa-angle-down fa-angle-button" aria-hidden="true" ng-hide="isTopExtrasArrowActive" ng-cloak></i>
                </h4>
                <div id="extras_top" class="collapse in">
                    <table>
                        <tr ng-repeat="optional_fee in optional_fees">
                            <td>
                                <span>{[optional_fee.name]} - </span>
                                <span class="fr"
                                    ng-init="optional_fee.result_value = optional_fee.value">
                                    {[ optional_fee.value | currency: "$" : 2  ]}
                                </span>
                            </td>
                            <td class="optional-fee-container">
                                <input
                                        type="number"
                                        min="0"
                                        ng-model="optional_fee.count"
                                        ng-change="optional_fee.result_value = optional_fee.value * optional_fee.count"
                                        ng-init="optional_fee.count = 0"
                                        ng-click="changeRequiredFees(optional_fee)"
                                        class="max-optional-fees"
                                        onkeydown="return false"
                                >
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="form-group price-breakdown-wrap" ng-cloak ng-show="reservation_days.length">
                <h4 data-toggle="collapse"
                    data-target="#price_breakdown"
                    aria-expanded="true"
                    aria-controls="price_breakdown"
                    ng-click="isPriceBreakArrowActive = !isPriceBreakArrowActive">
                    <?php _e( 'Price breakdown', 'streamline-core' ) ?>
                    <i class="fa fa-angle-up fa-angle-button" aria-hidden="true" ng-show="isPriceBreakArrowActive" ng-cloak></i>
                    <i class="fa fa-angle-down fa-angle-button" aria-hidden="true" ng-hide="isPriceBreakArrowActive" ng-cloak></i>
                </h4>
                <div id="price_breakdown" class="collapse">
                    <table>
                        <tr ng-repeat="reservation_day in reservation_days">
                            <td>{[reservation_day.date]}</td>
                            <td>{[reservation_day.price | currency: "$" : 2 ]}</td>
                        </tr>
                    </table>

                    <div class="form-group sub-collapse clearfix">
                        <h4 data-toggle="collapse"
                            href="#extras_bot"
                            aria-expanded="true"
                            aria-controls="extras_bot"
                            ng-click="isBotExtrasArrowActive = !isBotExtrasArrowActive">
                            <?php _e( 'Extras', 'streamline-core' ) ?>
                            <i class="fa fa-angle-up fa-angle-button" aria-hidden="true" ng-show="isBotExtrasArrowActive" ng-cloak></i>
                            <i class="fa fa-angle-down fa-angle-button" aria-hidden="true" ng-hide="isBotExtrasArrowActive" ng-cloak></i>
                        </h4>
                        <div class="extras-results">
                            <span>{[totalRequiredExtras | currency: "$" : 2 ]}</span>
                        </div>
                        <div id="extras_bot" class="collapse">
                            <table>
                                <tr ng-repeat="required_fee in required_fees">
                                    <td>{[required_fee.name]}</td>
                                    <td>{[required_fee.value | currency: "$" : 2 ]}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="form-group sub-collapse clearfix">
                        <h4 data-toggle="collapse"
                            href="#taxes_fees"
                            aria-expanded="true"
                            aria-controls="taxes_fees"
                            ng-click="isTaxesFeesArrowActive = !isTaxesFeesArrowActive">
                            <?php _e( 'Taxes and fees', 'streamline-core' ) ?>
                            <i class="fa fa-angle-up fa-angle-button" aria-hidden="true" ng-show="isTaxesFeesArrowActive" ng-cloak></i>
                            <i class="fa fa-angle-down fa-angle-button" aria-hidden="true" ng-hide="isTaxesFeesArrowActive" ng-cloak></i>
                        </h4>
                        <div class="extras-results">
                            <span>{[totalTaxesAndFees | currency: "$" : 2 ]}</span>
                        </div>
                        <div id="taxes_fees" class="collapse">
                            <table>
                                <tr ng-repeat="taxes_detail in taxes_details">
                                    <td>{[taxes_detail.name]}</td>
                                    <td>{[taxes_detail.value | currency: "$" : 2 ]}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="total-price-code-wrap" ng-if="total_reservation" ng-cloak>
                <table>
                    <tr>
                        <td>Total:</td>
                        <td>{[total_reservation | currency: "$" : 2 ]}</td>
                    </tr>
                    <tr ng-if="due_today">
                        <td>Due Today:</td>
                        <td>{[due_today | currency: "$" : 2 ]}</td>
                    </tr>
                    <tr ng-if="coupon_discount > 0" class="alert alert-success">
                        <td>{[couponCode]}</td>
                        <td>{[coupon_discount | currency: "$" : 2 ]}</td>
                    </tr>
                </table>
            </div>

            <div class="form-group" id="coupon-code-wrap">
                <p>Coupon code</p>
                <input class="counpon counpon-line" ng-model="couponCode" type="text" ng-change="couponFailed = false">
                <button type="button" ng-click="getPreReservationPrice(book,1)" class="check-counpon counpon-line">Redeem</button>
            </div>

            <div class="alert alert-danger" ng-cloak ng-if="couponCode && couponFailed && total_reservation">
                <?php _e( 'Coupon is not valid.', 'streamline-core' ) ?>
            </div>

            <div class="alert alert-{[alert.type]} animate" ng-repeat="alert in alerts">
                <div ng-bind-html="alert.message | trustedHtml"></div>
            </div>

            <div class="alert alert-danger" ng-cloak ng-repeat="error in errorMessages">
                <span>{[error]}</span>
            </div>

            <div class="alert alert-success" ng-cloak ng-show="successMessage" ng-bind="successMessage"></div>

            <div class="form-group term-cond-wrap">
                <label class="term-cond-check-label">
                    <input ng-model="termAndCondCheck" ng-change="errorMessage=false" type="checkbox" class="term-cond-check">
                    <span><?php _e( 'I agree to the ', 'streamline-core' ); ?></span>
                </label>
                <span ng-click="termAndCondHandler()" class="term-cond"><?php _e( 'Terms and Conditions', 'streamline-core' ); ?></span>
            </div>

            <div class="form-group">
                <button
                        ng-click="submitCheckout()"
                        ng-disabled="isDisabled"
                        type="button"
                        class="btn btn-lg btn-block btn-success resortpro_unit_submit_blue">
                    <i class="glyphicon glyphicon-check"></i>
                    <?php _e( 'Book Now', 'streamline-core' ) ?>
                </button>
            </div>
        </fieldset>
    </form>
</div>