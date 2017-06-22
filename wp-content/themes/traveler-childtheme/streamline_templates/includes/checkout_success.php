<div id="checkout-success" ng-controller="PropertyCheckoutController">
    <h1>Thank You</h1>
    <div class="table-responsive">
        <table class="table table-striped ">
            <tbody>
                <tr ng-repeat="(prop, val) in successCheckoutData">
                    <td>{[prop]}</td>
                    <td>{[val]}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>