<div id="checkout-success" ng-controller="PropertyCheckoutController">
    <h1>Thank You</h1>
    <div class="table-responsive">
        <table class="table table-striped ">
            <tbody>
                <tr ng-repeat="(prop, val) in successCheckoutData">
                    <td>{[prop]}</td>

                    <td ng-switch on="prop">
                        <span ng-switch-when="price_common">
                            {[val | currency: "$" : 2 ]}
                        </span>
                        <span ng-switch-when="price_balance">
                            {[val | currency: "$" : 2 ]}
                        </span>
                        <span ng-switch-default>
                          {[val]}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>