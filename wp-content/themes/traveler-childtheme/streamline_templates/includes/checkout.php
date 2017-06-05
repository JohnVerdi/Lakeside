<div id="checkout-wrapper" ng-controller="PropertyCheckoutController">
    <h1>Checkout</h1>
    <form name="checkForm" novalidate>
        <div class="row">
            <div class="col-md-12">
                <h5 class="border-bottom">Personal information</h5>
            </div>

            <div class="form-group col-md-6">
                <label>
                    <span>First name:</span>
                    <input type="text" name="userFirstName" required ng-minlength="2" class="form-control" placeholder="First name" ng-model="user.firstName">
                </label>
                <div ng-if="checkForm.userFirstName.$dirty" class="cust-alert alert-danger">
                    <ng-messages for="checkForm.userFirstName.$error">
                        <ng-message when="required">Please fill in this field.</ng-message>
                        <ng-message when="minlength">Too short name.</ng-message>
                    </ng-messages>
                </div>
            </div>

            <div class="form-group col-md-6">
                <label>
                    <span>Last name:</span>
                    <input type="text" name="userLastName" required ng-minlength="2" class="form-control" placeholder="Last name" ng-model="user.lastName">
                </label>
                <div ng-if="checkForm.userLastName.$dirty" class="cust-alert alert-danger">
                    <ng-messages for="checkForm.userLastName.$error">
                        <ng-message when="required">Please fill in this field.</ng-message>
                        <ng-message when="minlength">Too short name.</ng-message>
                    </ng-messages>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label>
                    <span>Email:</span>
                    <input type="email"
                           class="form-control"
                           placeholder="Email"
                           ng-model="user.email"
                           name="userEmail"
                           ng-minlength="5"
                           required
                    >
                </label>
                <div ng-if="checkForm.userEmail.$dirty" class="cust-alert alert-danger">
                    <ng-messages for="checkForm.userEmail.$error">
                        <ng-message when="required">Please fill in this field.</ng-message>
                        <ng-message when="email">Please enter valid e-mail.</ng-message>
                        <ng-message when="minlength">Too short e-mail.</ng-message>
                    </ng-messages>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label>
                    <span>Phone:</span>
                    <input type="tel"
                           name="userPhone"
                           required
                           class="form-control"
                           ng-minlength="10"
                           placeholder="(###) ###-####"
                           ng-model="user.phone"
                           ng-pattern="/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/"
                    >
                </label>
                <div ng-if="checkForm.userPhone.$dirty" class="cust-alert alert-danger">
                    <ng-messages for="checkForm.userPhone.$error">
                        <ng-message when="required">Please fill in this field.</ng-message>
                        <ng-message when="pattern">Please enter valid phone number.</ng-message>
                        <ng-message when="minlength">Too short phone number.</ng-message>
                    </ng-messages>
                </div>
            </div>

            <div class="col-md-12">
                <h5 class="border-bottom">Payment information</h5>
            </div>

            <div class="form-group col-md-4">
                <label>
                    <span>Adress:</span>
                    <input type="text" class="form-control" placeholder="Adress name" required ng-model="user.adress">
                </label>
            </div>
            <div class="form-group col-md-4">
                <label>
                    <span>Adress 2:</span>
                    <input type="text" class="form-control" placeholder="Optional" ng-model="user.adress2">
                </label>
            </div>
            <div class="form-group col-md-4">
                <label>
                    <span>Country:</span>
                    <select class="form-control" type="text" class="form-control" ng-model="user.country">
                        <option>United states</option>
                        <option>United states</option>
                        <option>Canada</option>
                        <option>Canada</option>
                    </select>
                </label>
            </div>

            <div class="form-group col-md-6">
                <label>
                    <span>City:</span>
                    <input type="text" class="form-control" placeholder="City" ng-model="user.city">
                </label>
            </div>
            <div class="form-group col-md-4">
                <label>
                    <span>State:</span>
                    <input type="text" class="form-control" placeholder="State" ng-model="user.state">
                </label>
            </div>
            <div class="form-group col-md-2">
                <label>
                    <span>Postal code:</span>
                    <input type="text" class="form-control" placeholder="Postal code" ng-model="user.postalCode">
                </label>
            </div>

            <div class="form-group col-md-3">
                <label>
                    <span>Cart Type:</span>
                    <input type="text" class="form-control" placeholder="Cart Type" ng-model="user.cardType">
                </label>
            </div>
            <div class="form-group col-md-3">
                <label>
                    <span>Card number:</span>
                    <input type="text" class="form-control" placeholder="Card number" ng-model="user.cardNumber">
                </label>
            </div>
            <div class="form-group col-md-2">
                <label>
                    <span>Exp Month:</span>
                    <input type="number" class="form-control" placeholder="Exp Month" ng-model="user.expMonth">
                </label>
            </div>
            <div class="form-group col-md-2">
                <label>
                    <span>Exp year:</span>
                    <input type="number" class="form-control" placeholder="Exp year" ng-model="user.expYear">
                </label>
            </div>
            <div class="form-group col-md-2">
                <label>
                    <span>CVV:</span>
                    <input type="text" class="form-control" placeholder="CVV" ng-model="user.svv">
                </label>
            </div>

            <ul ng-repeat="u in user">
                <li>{[u]}</li>
            </ul>

            <button ng-click="testClick()">Press</button>
        </div>
    </form>
</div>