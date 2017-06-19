<div id="checkout-success" ng-controller="PropertyCheckoutController">
    <h1 class="text-center">Checkout success</h1>
    <div class="table-responsive">
        <table class="table table-striped ">
            <tbody>
                <tr ng-repeat="(prop, val) in successCheckoutData">
                    <td class="text-center" ng-bind="prop"></td>
                    <td class="text-center" ng-bind="val"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>