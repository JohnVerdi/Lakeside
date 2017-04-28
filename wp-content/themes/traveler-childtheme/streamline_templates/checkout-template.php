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
<style type="text/css">
    .error{
        color: red;
    }
</style>
<div class="container">
    <div id="primary" class="row content-area" ng-controller="CheckoutController as cCtrl">
        <div ng-init="hash='<?php echo $hash; ?>';referrer_url='<?php echo $checkout_url; ?>'">
        <main id="main" class="row site-main" role="main" ng-init="initCheckout()">

            <?php if(!$ssl_enabled): ?>
            <div class="col-md-12">
                <div class="alert alert-danger">
                    <?php _e( 'This form is being submitted insecurely. Please enable the setting on Settings -> Streamline Settings -> Checkout', 'streamline-core' ) ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="col-md-12">
                <div class="alert alert-danger" ng-show="error" ng-cloak ng-bind="errorMessage">
                </div>
            </div>
            <?php
            if($online_bookings == 0){
            ?>
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <p><?php _e( 'This unit is not availabile for online booking.', 'streamline-core'); ?>
                        <?php
                            if(!empty($options['phone'])):
                              printf( __( 'Please call %s.', 'streamline-core' ), esc_html( $options['phone'] ) );
                            endif;
                        ?>
                        </p>
                    </div>
                </div>
            <?php
            }elseif($min_days_error){?>
                <div class="col-sm-12">
                    <div class="alert alert-danger">
                        <?php esc_html_e( $options['last_minute_message'] ); ?>
                    </div>
                </div>
            <?php
            }elseif($future_days_error){?>
                <div class="col-sm-12">
                    <div class="alert alert-danger">
                        <?php esc_html_e( $options['future_booking_message'] ); ?>
                    </div>
                </div>
            <?php
            }else{
            ?>
            
            <div ng-show="!error">
                <div class="col-md-12">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    1. <?php _e( 'Guest Information', 'streamline-core' ); ?>
                                </h4>
                            </div>
                            <div id="step1" class="panel-collapse collapse in" role="tabpanel"
                                 aria-labelledby="headingOne">
                                <div class="panel-body">

                                    <form name="formStep1" class="css-form" novalidate>
                                        <div class="row">

                                            <div class="col-md-4 col-md-push-8">
                                                <div style="position:relative;">
                                                    <img ng-src="{[property.default_image_path]}" style="max-width: 100%"/>
                                                    <p ng-cloak style="margin:0; position:absolute; bottom:0; left: 0; right:0; padding:4px 8px; background: rgba(0,0,0,0.7);color:#fff;" ng-bind="reservationDetails.location_name"></p>
                                                </div>
                                            </div>

                                            <div class="col-md-8 col-md-pull-4">
                                                <div class="form-group">
                                                    <div class="row">

                                                        <div class="form-group col-sm-6">
                                                            <label><?php _e( 'First Name', 'streamline-core' ); ?>: </label>
                                                            <input type="text" name="fname"
                                                            placeholder="<?php _e( 'First Name', 'streamline-core' ); ?>"
                                                            class="form-control"
                                                                   ng-model="checkout.fname"
                                                                   ng-change="validateStepOne(checkout)"
                                                                   required>
                                                             <div ng-show="formStep1.$submitted || formStep1.fname.$touched">
                                                                <span class="error" ng-show="formStep1.fname.$error.required" ng-bind="'<?php _e('First name is required.', 'streamline-core'); ?>'"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <label><?php _e('Last Name', 'streamline-core'); ?>: </label>
                                                            <input type="text" name="lname" class="form-control"
                                                            placeholder="<?php _e('Last Name', 'streamline-core'); ?>"
                                                                   ng-model="checkout.lname"
                                                                   ng-change="validateStepOne(checkout)"
                                                                   required>
                                                            <div ng-show="formStep1.$submitted || formStep1.lname.$touched">
                                                                <span class="error" ng-show="formStep1.lname.$error.required" ng-bind="'<?php _e('Last name is required.', 'streamline-core'); ?>'"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <label><?php _e('Email', 'streamline-core'); ?>: </label>
                                                            <input type="email" name="email" class="form-control"
                                                            placeholder="<?php _e('Email', 'streamline-core'); ?>"
                                                                   ng-model="checkout.email" required />
                                                            <div ng-show="formStep1.$submitted || formStep1.email.$touched">
                                                                <span class="error" ng-show="formStep1.email.$error.required" ng-bind="'<?php _e('Tell us your email.', 'streamline-core'); ?>'"></span>
                                                                <span class="error" ng-show="formStep1.email.$error.email" ng-bind="'<?php _e('This is not a valid email.', 'streamline-core'); ?>'"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <label><?php _e('Phone', 'streamline-core'); ?>: </label>
                                                            <input type="phone" name="phone" class="form-control"
                                                                   ng-model="checkout.phone"
                                                                   required
                                                                   placeholder="(###) ###-####" />
                                                            <div ng-show="formStep1.$submitted || formStep1.phone.$touched">
                                                                <span class="error" ng-show="formStep1.phone.$error.required" ng-bind="'<?php _e('Phone is required.', 'streamline-core'); ?>'"></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-12">

                                                            <button type="submit"
                                                                    class="btn btn-success"
                                                                    ng-click="goToStep2()"><?php _e('Continue', 'streamline-core'); ?> <i
                                                                    class="glyphicon glyphicon-arrow-right"></i>
                                                            </button>

                                                            <?php if($pbg_enabled): ?>
                                                            <div
                                                            class="pbg_info"
                                                            data-purchase-image-url="<?php echo $property_data['data']['default_image_path']; ?>"
                                                            data-purchase-name="<?php echo $property_data['data']['location_name']; ?>"
                                                            data-purchase-id=""
                                                            data-purchase-cost="{[totalPrice]}"
                                                            data-purchase-link-url="<?php echo $checkout_url; ?>"
                                                            data-purchase-description="<?php echo $property_data['data']['short_description']; ?>"
                                                            data-purchase-start-date="<?php echo $str_checkin; ?>"
                                                            data-purchase-end-date="<?php echo $str_checkout; ?>"
                                                            data-purchase-deadline="{[paymentLimit]}"
                                                            > <img
                                                            src="https://cdn.paybygroup.com/images/merchant/skins/seasidemexico/seasidemexico_split_the_cost_button.png" alt="Split the Cost"></div>
                                                            <?php endif; ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default" ng-show="reservationDetails.optional_fees.length > 0">
                            <div class="panel-heading" role="tab" id="headingTwo">
                                <h4 class="panel-title">
                                    2. <?php _e('Protect Your
                                    Investment', 'streamline-core'); ?>
                                </h4>
                            </div>
                            <div id="step2" class="panel-collapse collapse" role="tabpanel"
                                 aria-labelledby="headingTwo">
                                <div class="panel-body">
                                    <div class="row">

                                        <div class="col-md-4 col-md-push-8">
                                            <div id="resortpro-reservation-details">

                                                <div class="details table-responsive">
                                                    <table
                                                        class="table table-bordered table-striped table-hover table-condensed">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2">
                                                                    <?php _e('Reservation Info', 'streamline-core'); ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2" class="text-right">
                                                                    <strong style="float: left;"><?php _e('Unit', 'streamline-core'); ?>: </strong>
                                                                    <span ng-bind="reservationDetails.location_name"></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <strong><?php _e('Arrival Date', 'streamline-core'); ?>: </strong>
                                                                </td>
                                                                <td class="text-center" ng-bind="startDate"></td>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    <strong><?php _e('Departure Date', 'streamline-core'); ?>: </strong>
                                                                </td>
                                                                <td class="text-center" ng-bind="endDate"></td>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    <strong><?php _e('Number of Nights', 'streamline-core'); ?>: </strong>
                                                                </td>
                                                                <td class="text-center"
                                                                    ng-if="!reservationDetails.reservation_days.date"
                                                                    ng-bind="reservationDetails.reservation_days.length">               
                                                                </td>
                                                                <td class="text-center"
                                                                    ng-if="reservationDetails.reservation_days.date">
                                                                    1
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <strong><?php _e('Price', 'streamline-core'); ?> </strong>
                                                                    <a ng-show="!reservationDetails.reservation_days.date"
                                                                       href="#"
                                                                       class="btn btn-default btn-xs btn-price-breakdown pull-right">
                                                                        <i class="glyphicon glyphicon-menu-hamburger"></i>
                                                                        <span><?php _e('View Breakdown', 'streamline-core'); ?></span></span>
                                                                    </a>
                                                                </td>
                                                                <td ng-if="!reservationDetails.extra > 0" class="text-right">
                                                                    <span ng-bind="subTotal | currency"></span>
                                                                </td>
                                                            </tr>
                                                            <tr ng-repeat="res in reservationDetails.reservation_days"
                                                                class="breakdown-days-hidden" data-visible="false">
                                                                <td style="text-indent: 24px">
                                                                    <strong ng-bind="res.date"></strong>
                                                                </td>
                                                                <td class="text-right">
                                                                    <span ng-bind="calculateMarkup((res.price  + res.extra).toString()) | currency"></span>    
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <strong><?php _e('Taxes &amp; Fees', 'streamline-core'); ?></strong>
                                                                    <a href="#" class="btn btn-default btn-xs btn-taxes-breakdown pull-right">
                                                                        <i class="glyphicon glyphicon-menu-hamburger"></i>
                                                                        <span><?php _e('View Breakdown', 'streamline-core'); ?></span>
                                                                    </a>
                                                                </td>
                                                                <td class="text-right">
                                                                    <span ng-bind="taxesAndFees | currency"></span>    
                                                                </td>
                                                            </tr>

                                                            <tr ng-repeat="reqFee in reservationDetails.required_fees"
                                                                class="breakdown-taxes-hidden" data-visible="false">
                                                                <td style="text-indent: 24px">
                                                                    <strong ng-bind="reqFee.name"></strong>
                                                                </td>
                                                                <td class="text-right" ng-bind="reqFee.value | currency"></td>
                                                            </tr>

                                                            <tr ng-repeat="reqFee in reservationDetails.taxes_details"
                                                                class="breakdown-taxes-hidden" data-visible="false">
                                                                <td style="text-indent: 24px">
                                                                    <strong ng-bind="reqFee.name"> </strong>
                                                                </td>
                                                                <td class="text-right" ng-bind="reqFee.value | currency"></td>
                                                            </tr>

                                                            <tr ng-if="reservationDetails.coupon_discount > 0">
                                                                <td>
                                                                    <strong><?php _e('Discount', 'streamline-core'); ?>: </strong>
                                                                </td>
                                                                <td class="text-right" ng-bind="'('+(reservationDetails.coupon_discount | currency)+')'"></td>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    <strong><?php _e('Total', 'streamline-core'); ?>:</strong>
                                                                </td>
                                                                <td class="text-right" ng-bind="reservationDetails.total | currency"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <table ng-if="reservationDetails.optional_fees.length > 0" class="table table-bordered table-striped table-hover table-condensed">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2"><?php _e('Optional Fees', 'streamline-core'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr ng-repeat="optFee in reservationDetails.optional_fees track by $index">

                                                                <td>
                                                                    <div class="checkbox2">
                                                                        <label ng-if="optFee.travel_insurance == 1">
                                                                            <input type="checkbox"
                                                                                   ng-model="chkTravelInsuranceR.selectedOption"
                                                                                   value="{[optFee.id]}"
                                                                                   class="optional_fee"
                                                                                   ng-change="toggleTravelInsurance(optFee.id)"
                                                                                   ng-checked="optFee.active == 1"/>
                                                                            {[optFee.name ]}
                                                                        </label>
                                                                        <label ng-if="optFee.cfar == 1">
                                                                            <input type="checkbox"
                                                                                   value="{[optFee.id]}"
                                                                                   class="optional_fee"
                                                                                   ng-model="chkCfarR.selectedOption"
                                                                                   ng-change="toggleCfar(optFee.id)"
                                                                                   ng-checked="optFee.active == 1"/>
                                                                            {[optFee.name ]}
                                                                        </label>
                                                                        <label ng-if="optFee.damage_waiver == 1">
                                                                            <input type="checkbox"
                                                                                   value="{[optFee.id]}"
                                                                                   class="optional_fee"
                                                                                   ng-model="chkDamageWaiverR.selectedOption"
                                                                                   ng-change="toggleDamageWaiver(optFee.id)"
                                                                                   ng-checked="optFee.active == 1"/>
                                                                            {[optFee.name ]}
                                                                        </label>
                                                                        <label
                                                                            ng-if="optFee.damage_waiver == 0 && optFee.travel_insurance == 0 && optFee.cfar == 0">
                                                                            <input type="checkbox"
                                                                                   id="optional-fee-{[optFee.id]}"
                                                                                   class="optional_fee"
                                                                                   value="{[optFee.id]}"
                                                                                   ng-model="optionalFees[$index]"
                                                                                   ng-click="getPreReservation()"
                                                                                   ng-checked="optFee.active == 1"/>
                                                                            {[optFee.name ]}
                                                                        </label>
                                                                    </div>

                                                                </td>
                                                                <td class="text-right" ng-bind="optFee.value | currency">                                             
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <table ng-if="reservationDetails.security_deposits.security_deposit.length > 0 && chkDamageWaiverNo" class="table table-bordered table-striped table-hover table-condensed">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="2">
                                                                <?php _e('Security Deposits', 'streamline-core'); ?>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr ng-repeat="deposit in reservationDetails.security_deposits.security_deposit" ng-if="deposit.deposit_required > 0">
                                                                <td ng-bind="deposit.description"></td>
                                                                <td class="text-right" ng-bind="deposit.deposit_required | currency"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-8 col-md-pull-4">

                                            <div ng-show="hasTravelInsurance || hasCfar">
                                                <p><?php _e('Travel Insurance', 'streamline-core'); ?>:

                                                    (<?php _e('Please make a selection below', 'streamline-core'); ?>)</p>

                                                <p ng-show="hasTravelInsurance">
                                                    <input type="checkbox"
                                                           ng-model="chkTravelInsurance"
                                                           ng-click="acceptTravelInsurance()" />

                                                    <?php printf( '<span style="color:green">%s</span> %s', __('PROTECT', 'streamline-core'), __('my travel investment with Travel Insurance for <strong>{[travelInsurance | currency]}</strong>. Coverage protects my vacation investment up to 100% for covered reasons like illness, injury, natural disasters, travel delays and more.', 'streamline-core') ); ?>
                                                </p>

                                                <p ng-show="hasCfar">
                                                    <input type="checkbox"
                                                           ng-model="chkCfar"
                                                           ng-click="acceptCfar()" />

                                                    <?php printf( '<span style="color:green">%s</span> %s', __('PROTECT', 'streamline-core'), __('my travel investment with Cancel For Any Reason Travel Protection for <strong>{[cfar | currency]}</strong>. Coverage protects my vacation investment up to 100% for covered reasons like illness, natural disasters, travel delays and more. I can also cancel for a reason not listed in the policy and be eligible for a 75% reimbursement (this benefit must be used 3 days or more before check-in).', 'streamline-core') ); ?>
                                                </p>

                                                <p>
                                                    <input type="checkbox"
                                                           ng-model="chkTravelInsuranceNo"
                                                           ng-click="rejectTravelInsurance()"/>
                                                    I
                                                    <?php printf( '<span style="color:red">%s</span> %s', __('DECLINE', 'streamline-core'), __('travel protection and risk losing or forfeiting all or part of my paid and future investment against this reservation.', 'streamline-core') ); ?>
                                                </p>
                                                <p ng-show="protectionError">
                                                    <span ng-if="!(chkTravelInsurance || chkCfar || chkTravelInsuranceNo)" class="error" ng-bind="'<?php _e('You must make a selection for travel insurance', 'streamline-core'); ?>'"></span>
                                                </p>


                                                <hr/>
                                            </div>


                                            <div ng-show="hasDamageProtection">
                                                <p>
                                                    <?php _e('Damage Protection', 'streamline-core'); ?>:
                                                    <strong>{[damageProtection | currency]}</strong>
                                                    (<?php _e('Please make a selection below', 'streamline-core'); ?>)
                                                </p>

                                                <p>
                                                    <input type="checkbox"
                                                           ng-model="chkDamageWaiver"
                                                           ng-click="acceptDamageWaiver() "/>
                                                    <?php printf( '<span style="color:green">%s</span> %s', __('PROTECT', 'streamline-core'), __('my rental with accidental Rental Damage Protection. Coverage includes any accidental damage to the property and it&rsquo;s contents for the duration of the rental period subject to the policy terms and conditions of rental.', 'streamline-core') ); ?>
                                                </p>

                                                <p>
                                                    <input type="checkbox"
                                                           ng-click="rejectDamageWaiver()"
                                                           ng-model="chkDamageWaiverNo"/>
                                                    I
                                                    <?php printf( '<span style="color:red">%s</span> %s %s', __('DECLINE', 'streamline-core'), __('and will pay the security deposit of {[securityDeposit | currency]}', 'streamline-core'), __( 'and accept the risk of being responsible for any damage that may exceed my security deposit amount.', 'streamline-core') ); ?>
                                                </p>
                                                <p ng-show="protectionError">
                                                    <span ng-if="!(chkDamageWaiver || chkDamageWaiverNo)" class="error" ng-bind="'<?php _e('You must make a selection for damage protection', 'streamline-core'); ?>'"></span>
                                                </p>
                                            </div>

                                            <div class="form-group col-md-12">

                                                <button type="button" class="btn btn-default"
                                                        ng-click="goToStepOne()">
                                                    <i class="glyphicon glyphicon-arrow-left"></i> <?php _e( 'Go Back', 'streamline-core' ) ?>
                                                </button>

                                                <button type="button" class="btn btn-success"
                                                        ng-click="goToStep3()">
                                                    <?php _e( 'Continue', 'streamline-core' ) ?> <i class="glyphicon glyphicon-arrow-right"></i>
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingThree" ng-if="reservationDetails.optional_fees.length > 0">
                                <h4 class="panel-title">
                                    3. <?php _e('Payment Information', 'streamline-core'); ?>
                                </h4>
                            </div>
                            <div class="panel-heading" role="tab" id="headingThree" ng-if="!reservationDetails.optional_fees.length > 0">
                                <h4 class="panel-title">
                                    2. <?php _e('Payment Information', 'streamline-core'); ?>
                                </h4>
                            </div>
                            <div id="step3" class="panel-collapse collapse" role="tabpanel"
                                 aria-labelledby="headingThree">
                                <div class="panel-body">

                                    <form name="formStep3" class="css-form" novalidate>
                                        <div class="row">

                                            <div class="col-md-4 col-md-push-8">
                                                <div id="resortpro-reservation-details">
                                                    <div class="details table-responsive">
                                                        <table
                                                            class="table table-bordered table-striped table-hover table-condensed">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="2">
                                                                        <?php _e('Reservation Info', 'streamline-core'); ?>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="2" class="text-right"><strong style="float: left;"><?php _e('Unit', 'streamline-core'); ?>: </strong ng-bind="reservationDetails.location_name"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <strong><?php _e('Arrival Date', 'streamline-core'); ?>: </strong>
                                                                    </td>
                                                                    <td class="text-center" ng-bind="startDate">                            
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <strong><?php _e('Departure Date', 'streamline-core'); ?>: </strong>
                                                                    </td>
                                                                    <td class="text-center" ng-bind="endDate">                              
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <strong><?php _e('Number of Nights', 'streamline-core'); ?>: </strong>
                                                                    </td>
                                                                    <td class="text-center" ng-bind="reservationDetails.reservation_days.length">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <strong><?php _e('Price', 'streamline-core'); ?> </strong> <a href="#"
                                                                                                   class="btn btn-default btn-xs btn-price-breakdown pull-right"><i
                                                                                class="glyphicon glyphicon-menu-hamburger"></i>
                                                                            <span><?php _e('View Breakdown', 'streamline-core'); ?></span></span></a>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <span ng-bind="subTotal | currency"></span>  
                                                                    </td>
                                                                </tr>
                                                                <tr ng-repeat="res in reservationDetails.reservation_days"
                                                                    class="breakdown-days-hidden" data-visible="false">
                                                                    <td style="text-indent: 24px"><strong ng-bind="res.date"></strong></td>
                                                                    <td class="text-right">
                                                                        <span ng-bind="calculateMarkup((res.price  + res.extra).toString()) | currency"></span> 
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <strong><?php _e('Taxes &amp; Fees', 'streamline-core'); ?></strong> <a href="#"
                                                                                                   class="btn btn-default btn-xs btn-taxes-breakdown pull-right"><i
                                                                                class="glyphicon glyphicon-menu-hamburger"></i>
                                                                            <span><?php _e('View Breakdown', 'streamline-core'); ?></span></span></a>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <span ng-bind="taxesAndFees | currency"></span>
                                                                    </td>
                                                                </tr>
                                                                <tr ng-repeat="tax in reservationDetails.taxes_details track by $index"
                                                                 class="breakdown-taxes-hidden" data-visible="false">
                                                                    <td style="text-indent: 24px">
                                                                        <strong ng-bind="tax.name"></strong>
                                                                    </td>
                                                                    <td class="text-right" ng-bind="tax.value | currency">                                                 
                                                                    </td>
                                                                </tr>
                                                                <tr ng-repeat="reqFee in reservationDetails.required_fees"
                                                                    class="breakdown-taxes-hidden" data-visible="false">
                                                                    <td style="text-indent: 24px">
                                                                        <strong ng-bind="reqFee.name"></strong>
                                                                    </td>
                                                                    <td class="text-right" ng-bind="reqFee.value | currency">
                                                                    </td>
                                                                </tr>
                                                                <tr ng-repeat="optFee in reservationDetails.optional_fees track by $index"
                                                                    ng-if="optFee.active == 1">

                                                                    <td>
                                                                        <strong ng-bind="optFee.name"></strong>
                                                                    </td>
                                                                    <td class="text-right" ng-bind="optFee.value | currency">
                                                                    </td>
                                                                </tr>
                                                                <tr ng-if="reservationDetails.coupon_discount > 0">
                                                                    <td>
                                                                        <strong><?php _e('Discount', 'streamline-core'); ?>: </strong>
                                                                    </td>
                                                                    <td class="text-right" ng-bind="'(' + (reservationDetails.coupon_discount | currency) + ')'">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <strong><?php _e('Total', 'streamline-core'); ?>: </strong>
                                                                    </td>
                                                                    <td class="text-right" ng-bind="reservationDetails.total | currency">
                                                                    </td>
                                                                </tr>

                                                                <tr ng-if="reservationDetails.due_today > 0">
                                                                    <td>
                                                                        <strong><?php _e('Due Today', 'streamline-core'); ?>: </strong>
                                                                    </td>
                                                                    <td class="text-right" ng-bind="reservationDetails.due_today | currency"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                        <table ng-if="reservationDetails.security_deposits.security_deposit.length > 0 && chkDamageWaiverNo" class="table table-bordered table-striped table-hover table-condensed">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="2">
                                                                    <?php _e('Security Deposits', 'streamline-core'); ?>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr ng-repeat="deposit in reservationDetails.security_deposits.security_deposit" ng-if="deposit.deposit_required > 0">
                                                                    <td ng-bind="deposit.description"></td>
                                                                    <td class="text-right" ng-bind="deposit.deposit_required | currency"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                        <table ng-if="reservationDetails.expected_charges.length > 0" class="table table-bordered table-striped table-hover table-condensed">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="3">
                                                                        <?php _e('Expected Charges', 'streamline-core'); ?>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <th><?php _e('Date', 'streamline-core'); ?></th>
                                                                    <th><?php _e('Description', 'streamline-core'); ?></th>
                                                                    <th><?php _e('Charge', 'streamline-core'); ?></th>
                                                                </tr>
                                                                <tr ng-repeat="charge in reservationDetails.expected_charges track by $index">
                                                                    <td ng-bind="charge.charge_date"></td>
                                                                    <td ng-bind="charge.description"></td>
                                                                    <td class="text-right" ng-bind="charge.charge_value | currency"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-8 col-md-pull-4">

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label><?php _e('Address', 'streamline-core'); ?>: </label>
                                                            <input type="text" name="address" class="form-control"
                                                                   ng-model="checkout.address"
                                                                   required/>
                                                            <div ng-show="formStep3.$submitted || formStep3.address.$touched">
                                                                <span class="error" ng-show="formStep3.address.$error.required"><?php _e('Address is required.', 'streamline-core'); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label><?php _e('Address', 'streamline-core'); ?> 2: </label>
                                                            <input type="text" name="address2" class="form-control"
                                                                   ng-model="checkout.address2" placeholder="<?php _e('Optional', 'streamline-core'); ?>"/>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label><?php _e('Country', 'streamline-core'); ?>: </label>
                                                            <select ng-model="checkout.country" ng-change="getStates()" name="country" id="country" size="1"  class="form-control required" ng-options="country.name as country.description for country in countries">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label><?php _e('City', 'streamline-core'); ?>: </label>
                                                            <input type="text" name="city" class="form-control"
                                                                   ng-model="checkout.city"
                                                                   required>
                                                            <div ng-show="formStep3.$submitted || formStep3.city.$touched">
                                                                <span class="error" ng-show="formStep3.city.$error.required" ng-bind="'<?php _e('City is required.', 'streamline-core'); ?>'"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label><?php _e('State', 'streamline-core'); ?>: </label>
                                                            <input type="text" max-length="2" name="state"
                                                                   class="form-control"
                                                                   ng-if="!states.length"
                                                                   ng-model="checkout.state" required>

                                                            <select name="state"
                                                                    class="form-control"
                                                                    ng-if="states.length"
                                                                    ng-model="checkout.state" required>
                                                                <option value=""></option>
                                                                <option ng-repeat='state in states' value='{[state.name]}'>{[state.description]}</option>
                                                            </select>
                                                            <div ng-show="formStep3.$submitted || formStep3.state.$touched">
                                                                <span class="error" ng-show="formStep3.state.$error.required" ng-bind="'<?php _e('State is required.', 'streamline-core'); ?>'"></span>
                                                            </div>

                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label><?php _e('Postal Code', 'streamline-core'); ?>: </label>
                                                            <input type="text" name="postal_code" class="form-control"
                                                                   ng-model="checkout.postal_code" required>
                                                            <div ng-show="formStep3.$submitted || formStep3.postal_code.$touched">
                                                                <span class="error" ng-show="formStep3.postal_code.$error.required" ng-bind="'<?php _e('Postal Code is required.', 'streamline-core'); ?>'"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" ng-init="checkout.card_type=''">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <label><?php _e( 'Card Type:', 'streamline-core' ) ?> </label>

                                                            <select name="card_type" class="form-control"
                                                                    ng-model="checkout.card_type"
                                                                    required>
                                                                <?php if ( (int)$options['property_card_type_visa'] === 1 ) : ?>
                                                                  <option value="1"><?php _e('Visa', 'streamline-core'); ?></option>
                                                                <?php endif;?>
                                                                <?php if ( (int)$options['property_card_type_master_card'] === 1 ) : ?>
                                                                  <option value="2"><?php _e('MasterCard', 'streamline-core'); ?></option>
                                                                <?php endif;?>
                                                                <?php if ( (int)$options['property_card_type_amex'] === 1 ) : ?>
                                                                  <option value="3"><?php _e('American Express', 'streamline-core'); ?></option>
                                                                <?php endif;?>
                                                                <?php if ( (int)$options['property_card_type_discover'] === 1 ) : ?>
                                                                  <option value="4"><?php _e('Discover', 'streamline-core'); ?></option>
                                                                <?php endif;?>
                                                            </select>
                                                            <div ng-show="formStep3.$submitted || formStep3.card_type.$touched">
                                                                <span class="error" ng-show="formStep3.card_type.$error.required" ng-bind="'<?php _e('Card Type is required.', 'streamline-core'); ?>'"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label><?php _e('Card Number', 'streamline-core'); ?>: </label>
                                                            <input type="text" maxlength="16" name="card_number"
                                                                   class="form-control"
                                                                   placeholder="<?php _e('No dashes or spaces', 'streamline-core'); ?>"
                                                                   ng-model="checkout.card_number"
                                                                   autocomplete="off"
                                                                   required>
                                                            <div ng-show="formStep3.$submitted || formStep3.card_number.$touched">
                                                                <span class="error" ng-show="formStep3.card_number.$error.required" ng-bind="'<?php _e('Card Number is required.', 'streamline-core'); ?>'"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label><?php _e('Exp Month', 'streamline-core'); ?>: </label>
                                                            <select name="expire_month" class="form-control"
                                                                    ng-model="checkout.expire_month"
                                                                    required>
                                                                <option value="01"><?php _e('Jan', 'streamline-core'); ?></option>
                                                                <option value="02"><?php _e('Feb', 'streamline-core'); ?></option>
                                                                <option value="03"><?php _e('Mar', 'streamline-core'); ?></option>
                                                                <option value="04"><?php _e('Apr', 'streamline-core'); ?></option>
                                                                <option value="05"><?php _e('May', 'streamline-core'); ?></option>
                                                                <option value="06"><?php _e('Jun', 'streamline-core'); ?></option>
                                                                <option value="07"><?php _e('Jul', 'streamline-core'); ?></option>
                                                                <option value="08"><?php _e('Aug', 'streamline-core'); ?></option>
                                                                <option value="09"><?php _e('Sep', 'streamline-core'); ?></option>
                                                                <option value="10"><?php _e('Oct', 'streamline-core'); ?></option>
                                                                <option value="11"><?php _e('Nov', 'streamline-core'); ?></option>
                                                                <option value="12"><?php _e('Dec', 'streamline-core'); ?></option>
                                                            </select>
                                                            <div ng-show="formStep3.$submitted || formStep3.expire_month.$touched">
                                                                <span class="error" ng-show="formStep3.expire_month.$error.required" ng-bind="'<?php _e('Expiration month is required.', 'streamline-core'); ?>'"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label><?php _e('Exp Year', 'streamline-core'); ?>: </label>
                                                            <select name="expire_year" class="form-control"
                                                                    ng-model="checkout.expire_year"
                                                                    ng-options="year for year in years" required>
                                                            </select>
                                                            <div ng-show="formStep3.$submitted || formStep3.expire_year.$touched">
                                                                <span class="error" ng-show="formStep3.expire_year.$error.required" ng-bind="'<?php _e('Expiration year is required.', 'streamline-core'); ?>'"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label><?php _e('CVV', 'streamline-core'); ?>: </label>
                                                            <input type="text" maxlength="4" name="card_cvv"
                                                                   class="form-control"
                                                                   ng-model="checkout.card_cvv" required>
                                                            <div ng-show="formStep3.$submitted || formStep3.card_cvv.$touched">
                                                                <span class="error" ng-show="formStep3.card_cvv.$error.required" ng-bind="'<?php _e('CVV is required.', 'streamline-core'); ?>'"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if($has_coupon): ?>
                                                <div class="form-group">
                                                    <div class="row">
                                                      <div class="col-md-6">
                                                        <div class="input-group">
                                                          <input type="text" class="form-control" placeholder="<?php _e('Enter Promo Code', 'streamline-core'); ?>" ng-model="checkout.promo_code" />
                                                          <span class="input-group-btn">
                                                            <button class="btn btn-primary" type="button" ng-click="getPreReservation()"><i class="glyphicon glyphicon-ok"></i> <?php _e('Redeem', 'streamline-core'); ?></button>
                                                          </span>
                                                        </div>
                                                      </div>
                                                    </div>
                                                </div>
                                                <?php endif; ?>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="terms" ng-model="termsConditions" ng-true-value="1" check-required><?php printf( __( 'I agree to
                                                                    the %s Terms &amp; Conditions %s',  'streamline-core'), '<a
                                                                        href="#"
                                                                        data-toggle="modal"
                                                                        data-target="#myModal1">','</a>' ); ?>
                                                                </label>
                                                            </div>
                                                            <div ng-show="formStep3.$submitted || formStep3.terms.$touched">
                                                                <span class="error" ng-show="!formStep3.terms.$valid" ng-bind="'<?php _e('You have not read the terms and conditions', 'streamline-core'); ?>'"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="alert alert-{[alert.type]} animate"
                                                     ng-repeat="alert in alerts">
                                                    <div ng-bind-html="alert.message | trustedHtml"></div>
                                                </div>

                                                <div class="form-group">

                                                    <button type="button" class="btn btn-default"
                                                            ng-click="goToStepTwo()"
                                                            ng-if="reservationDetails.optional_fees.length > 0">
                                                        <i class="glyphicon glyphicon-arrow-left"></i> <?php _e( 'Go Back', 'streamline-core' ) ?>
                                                    </button>
                                                    <button type="button" class="btn btn-default"
                                                            ng-click="goToStepOne()"
                                                            ng-if="!reservationDetails.optional_fees.length > 0">
                                                        <i class="glyphicon glyphicon-arrow-left"></i> <?php _e( 'Go Back', 'streamline-core' ) ?>
                                                    </button>

                                                    <button id="btn-checkout"
                                                            type="submit"
                                                            class="btn btn-lg btn-primary"
                                                            ng-click="validateStep3(checkout)">
                                                        <i class="glyphicon glyphicon-log-in"></i>
                                                        <?php _e('Complete my Booking', 'streamline-core'); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </main>
        </div>
        <!-- .site-main -->
        <div class="modal fade" id="myModal1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><?php _e('Terms &amp; Conditions', 'streamline-core'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div ng-bind-html="terms"></div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div class="modal fade" id="modalAmenities" ng-init="getAmenitiesWithRates()">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><?php _e('Add Ons', 'streamline-core'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div>
                            <table cellspacing="0" cellpadding="0" border="0"
                                   class="table table-striped table-condensed table-bordered">
                                <tr ng-repeat="amenity in amenities">
                                    <td style="width: 75px">
                                        <img ng-src="{[amenity.amenity_thumbnail]}" style="max-width: 100%;"
                                             alt="{[amenity.amenity_name]}"/>
                                    </td>
                                    <td>

                                        <h3 style="margin:0">
                                            <input type="checkbox" class="addOn" value="{[amenity.amenity_id]}"/>
                                            <span ng-bind="amenity.amenity_name"></span>
                                        </h3>

                                        <p ng-if="!isEmptyObject(amenity.amenity_description)" ng-bind="amenity.amenity_description"></p>
                                    </td>
                                    <td class="text-center" style="max-width: 75px;">
                                        <input type="text" class="form-control text-right" value="1"/>
                                    </td>
                                    <td ng-bind="amenity.amenity_cost">                                        
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal"><?php echo _x('Close', 'close modal window', 'streamline-core'); ?></button>
                        <button type="button" class="btn btn-primary" ng-click="addToReservation()"><?php _e('Add to reservation', 'streamline-core'); ?>
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- .content-area -->
</div>
<form method="post" id="form_thankyou" action="<?php echo get_permalink(get_page_by_slug('thank-you')) ?>">
    <input type="hidden" id="confirmation_id" name="confirmation_id" value="" />
    <input type="hidden" id="location_name" name="location_name" value="" />
    <input type="hidden" id="condo_type_name" name="condo_type_name" value="" />
    <input type="hidden" id="unit_name" name="unit_name" value="" />
    <input type="hidden" id="startdate" name="startdate" value="" />
    <input type="hidden" id="enddate" name="enddate" value="" />
    <input type="hidden" id="occupants" name="occupants" value="" />
    <input type="hidden" id="occupants_small" name="occupants_small" value="" />
    <input type="hidden" id="pets" name="pets" value="" />
    <input type="hidden" id="price_common" name="price_common" value="" />
    <input type="hidden" id="price_balance" name="price_balance" value="" />
    <input type="hidden" id="travelagent_name" name="travelagent_name" value="" />
    <input type="hidden" id="email" name="email" value="" />
    <input type="hidden" id="fname" name="fname" value = "" />
    <input type="hidden" id="lname" name= "lname" value= ""/>
    <input type="hidden" id="unit_id" name= "unit_id" value= ""/>
</form>