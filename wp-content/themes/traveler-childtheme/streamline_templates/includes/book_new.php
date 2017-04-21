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
        <h3 class="text-center book-now-title" ng-cloak >{[getBookNowTitle()]}</h3>
    </div>

    <div class="inquiry right-side top">

    <div class="alert alert-{[alert.type]} animate" ng-repeat="alert in alerts">
        <div ng-bind-html="alert.message | trustedHtml"></div>
    </div>

    <form action="<?php echo $checkout_url ?>" method="post" name="resortpro_form_checkout" class="resortpro_form_checkout" novalidate>
        <input type="hidden" name="resortpro_book_unit" value="<?php echo $nonce; ?>"/>
        <input type="hidden" name="book_unit" value="<?php echo $property['id'] ?>"/>
        <?php if(!empty($hash)): ?>
            <input type="hidden" name="hash" value="<?php echo $hash; ?>" />
        <?php endif; ?>
        <h3 class="price" ng-show="res == 0" ng-cloak >{[first_day_price | currency:undefined:0]}<span
                class="text"> <?php _e( 'Per Night', 'streamline-core' ) ?></span>
        </h3>

        <h3 class="price" ng-show="res == 1 && total_reservation > 0" ng-cloak >{[total_reservation |
            currency:undefined:0]}
            <span class="text" style="font-size: 0.6em"><?php _e( 'including taxes and fees', 'streamline-core' ) ?></span>
        </h3>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group fa-calendar-wrapper">
                    <label for="book_start_date"><?php _e( 'Check in', 'streamline-core' ) ?></label>
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                    <input type="text"
                       ng-model="book.checkin"
                       id="book_start_date"
                       name="book_start_date"
                       class="form-control"
                       show-days="renderCalendar(date, false)"
                       update-price="getPreReservationPrice(book,1)"
                       update-checkout="setCheckoutDate(date)"
                       bookcheckin
                       data-min-stay="<?php echo $min_stay ?>"
                       data-checkin-days="<?php echo $checkin_days ?>"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group fa-calendar-wrapper">
                    <label for="book_end_date"><?php _e( 'Check out', 'streamline-core' ) ?></label>
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                    <input type="text"
                           ng-model="book.checkout"
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

        <div class="form-group">
            <h4 data-toggle="collapse"
                href="#extras_top"
                aria-expanded="true"
                aria-controls="extras_top"
                ng-click="isTopExtrasArrowActive = !isTopExtrasArrowActive">
                <?php _e( 'Extras', 'streamline-core' ) ?>
                <i class="fa fa-angle-up fa-angle-button" aria-hidden="true" ng-show="isTopExtrasArrowActive" ng-cloak></i>
                <i class="fa fa-angle-down fa-angle-button" aria-hidden="true" ng-hide="isTopExtrasArrowActive" ng-cloak></i>
            </h4>
            <div id="extras_top" class="collapse">
                <table>
                    <tr ng-repeat="extra_top in extras_top">
                        <td><label for="{[$index+1]}">{[extra_top.title]}:</label></td>
                        <td><input id="{[$index+1]}" ng-model="extra_top.value" type="number"/></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="form-group">
            <h4 data-toggle="collapse"
                href="#price_breakdown"
                aria-expanded="true"
                aria-controls="price_breakdown"
                ng-click="isPriceBreakArrowActive = !isPriceBreakArrowActive">
                <?php _e( 'Price breakdown', 'streamline-core' ) ?>
                <i class="fa fa-angle-up fa-angle-button" aria-hidden="true" ng-show="isPriceBreakArrowActive" ng-cloak></i>
                <i class="fa fa-angle-down fa-angle-button" aria-hidden="true" ng-hide="isPriceBreakArrowActive" ng-cloak></i>
            </h4>
            <div id="price_breakdown" class="collapse">
                <table>
                    <tr>
                        <td>03/24/2017</td>
                        <td>$279.00</td>
                    </tr>
                    <tr>
                        <td>03/24/2017</td>
                        <td>$279.00</td>
                    </tr>
                    <tr>
                        <td>03/24/2017</td>
                        <td>$279.00</td>
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
                        <span>$99.99</span>
                    </div>
                    <div id="extras_bot" class="collapse">
                        <table>
                            <tr>
                                <td>Child Lift Ticket, Snowbasin</td>
                                <td>$279.00</td>
                            </tr>
                            <tr>
                                <td>Humidifier</td>
                                <td>$279.00</td>
                            </tr>
                            <tr>
                                <td>In Room Massage</td>
                                <td>$279.00</td>
                            </tr>
                            <tr>
                                <td>Snowbasin Lift Ticket</td>
                                <td>$279.00</td>
                            </tr>
                            <tr>
                                <td>CSA Trip Insurance</td>
                                <td>$279.00</td>
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
                        <span>$99.99</span>
                    </div>
                    <div id="taxes_fees" class="collapse">
                        <table>
                            <tr>
                                <td>Lodging Tax(fees)</td>
                                <td>$279.00</td>
                            </tr>
                            <tr>
                                <td>Lodging Tax</td>
                                <td>$279.00</td>
                            </tr>
                            <tr>
                                <td>Sales Tax</td>
                                <td>$279.00</td>
                            </tr>
                            <tr>
                                <td>Cleaning Fees (Taxed)</td>
                                <td>$279.00</td>
                            </tr>
                            <tr>
                                <td>Processing Fee (Taxed)</td>
                                <td>$279.00</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" id="coupon-code-wrap">
            <p>Coupon code</p>
            <input class="counpon counpon-line" ng-model="couponCode" type="text">
            <button class="check-counpon counpon-line">Redeem</button>
        </div>

        <?php if(!(is_numeric($property['online_bookings']) && $property['online_bookings'] == 0)): ?>
            <div class="form-group">
                <button ng-disabled="isDisabled" id="resortpro_unit_submit_blue" href="this.submit()"
                        class="btn btn-lg btn-block btn-success">
                    <i class="glyphicon glyphicon-check"></i>
                    <?php _e( 'Book Now', 'streamline-core' ) ?>
                </button>
            </div>
        <?php endif; ?>
    </form>
</div>