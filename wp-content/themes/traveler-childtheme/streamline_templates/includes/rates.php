<div id="tab-1439375290816-2-1" class="ui-tabs-panel wpb_ui-tabs-hide vc_clearfix ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-4" role="tabpanel" aria-hidden="true" style="display: none;">
    <div id="property-rates-pane" role="tabpanel" class="tab-pane">
        <h3><?php _e( 'Rates', 'streamline-core' ) ?></h3>
        <div id="property-rates">
            <div id="rates-details">

                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                    <tr>
                        <th><?php _e( 'Season', 'streamline-core' ) ?></th>
                        <th><?php _e( 'Period', 'streamline-core' ) ?></th>
                        <th><?php _e( 'Min. Stay', 'streamline-core' ) ?></th>
                        <th><?php _e( 'Nightly Rate', 'streamline-core' ) ?></th>
                        <th><?php _e( 'Weekly Rate', 'streamline-core' ) ?></th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr class="row-rate" ng-repeat="rate in rates_details">
                        <td>{[rate.season_name]}</td>
                        <td>{[rate.period_begin]} - {[rate.period_end]}</td>
                        <td>{[rate.narrow_defined_days]}</td>
                        <td class="text-center"><span ng-if="rate.daily_first_interval_price" ng-bind="calculateMarkup(rate.daily_first_interval_price) | currency"></span></td>
                        <td class="text-center"><span ng-if="rate.weekly_price" ng-bind="calculateMarkup(rate.weekly_price) | currency"></span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>