<div class="pbg_info" ng-if="pbgEnabled" style="display:inline";
    data-purchase-image-url="<?php echo $thumbnail; ?>"
    data-purchase-start-date="<?php echo $start_date; ?>"
    data-purchase-end-date="<?php echo $end_date; ?>"
    data-purchase-inventory-id="<?php echo $unit_id ?>"
    data-optional-fees="{[optionalItems]}"
    data-promo-code="{[checkout.promo_code]}">

    <button type="button" class="btn btn-lg btn-success">
        <?php _e( 'Split the cost', 'streamline-core' ) ?>
        <span style="font-size:0.8em" ng-if="reservationDetails.total > 0" ng-bind="' - ' + ((reservationDetails.total / occupants) | currency:undefined:0) + ' per guest'"></span>
    </button>
</div>
