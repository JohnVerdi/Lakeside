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
                    <input type="text" name="userFirstName" required ng-minlength="2" class="form-control"
                           placeholder="First name" ng-model="user.firstName">
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
                    <input type="text" name="userLastName" required ng-minlength="2" class="form-control"
                           placeholder="Last name" ng-model="user.lastName">
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
                    <input type="text" class="form-control" required ng-minlength="2" name="userAdress"
                           placeholder="Adress name" ng-model="user.adress">
                </label>
                <div ng-if="checkForm.userAdress.$dirty" class="cust-alert alert-danger">
                    <ng-messages for="checkForm.userAdress.$error">
                        <ng-message when="required">Please fill in this field.</ng-message>
                        <ng-message when="minlength">Too short adress.</ng-message>
                    </ng-messages>
                </div>
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
                    <select class="form-control" required ng-model="user.country" name="checkoutCountries">
                        <option value="" disabled >Select a Country</option>-->
                        <option ng-repeat="country in checkoutCountries" value="{[country.code]}">{[country.name]}</option>
                    </select>
                </label>
            </div>

            <div class="form-group col-md-6">
                <label>
                    <span>City:</span>
                    <input type="text" name="userCity" required ng-minlength="2" class="form-control" placeholder="City"
                           ng-model="user.city">
                </label>
                <div ng-if="checkForm.userCity.$dirty" class="cust-alert alert-danger">
                    <ng-messages for="checkForm.userCity.$error">
                        <ng-message when="required">Please fill in this field.</ng-message>
                        <ng-message when="minlength">Too short city.</ng-message>
                    </ng-messages>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label>
                    <span>State:</span>
                    <select required class="form-control" name="userCartType" ng-model="user.state"
                            ng-options="obj.abbreviation as obj.name for obj in stateDictionaryUSA">
                        <option value="" disabled >Select state</option>
                    </select>
                </label>
                <div ng-if="checkForm.userState.$dirty" class="cust-alert alert-danger">
                    <ng-messages for="checkForm.userState.$error">
                        <ng-message when="required">Please fill in this field.</ng-message>
                    </ng-messages>
                </div>
            </div>
            <div class="form-group col-md-2">
                <label>
                    <span>Postal code:</span>
                    <input type="text"
                           class="form-control"
                           placeholder="Postal code"
                           ng-model="user.postalCode"
                           ng-pattern="/^\d{5,6}?$/"
                           required
                           name="userPostCode"
                    >
                </label>
                <div ng-if="checkForm.userPostCode.$dirty" class="cust-alert alert-danger">
                    <ng-messages for="checkForm.userPostCode.$error">
                        <ng-message when="required">Please fill in this field.</ng-message>
                        <ng-message when="pattern">Wrong postal code.</ng-message>
                    </ng-messages>
                </div>
            </div>

            <div class="form-group col-md-3">
                <label>
                    <span>Cart Type:</span>
                    <select required class="form-control" name="userCartType" ng-model="user.cardType"
                            ng-options="obj.id as obj.value for obj in cardTypes">
                        <option value="" disabled >Type of cart</option>
                    </select>
                </label>
                <div ng-if="checkForm.userCartType.$touched" class="cust-alert alert-danger">
                    <ng-messages for="checkForm.userCartType.$error">
                        <ng-message when="required">Please fill in this field.</ng-message>
                    </ng-messages>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label>
                    <span>Card number:</span>
                    <input type="text" name="userCartNumber" required ng-minlength="2" class="form-control"
                           placeholder="Card number" ng-model="user.cardNumber">
                </label>
                <div ng-if="checkForm.userCartNumber.$dirty" class="cust-alert alert-danger">
                    <ng-messages for="checkForm.userCartNumber.$error">
                        <ng-message when="required">Please fill in this field.</ng-message>
                        <ng-message when="minlength">Too short Cart Number.</ng-message>
                    </ng-messages>
                </div>
            </div>
            <div class="form-group col-md-2">
                <label>
                    <span>Exp Month:</span>
                    <input type="number" name="userExpMonth" required ng-minlength="1" class="form-control"
                           placeholder="Exp Month" ng-model="user.expMonth">
                </label>
                <div ng-if="checkForm.userExpMonth.$dirty" class="cust-alert alert-danger">
                    <ng-messages for="checkForm.userExpMonth.$error">
                        <ng-message when="required">Please fill in this field.</ng-message>
                        <ng-message when="minlength">Too short Exp Month.</ng-message>
                    </ng-messages>
                </div>
            </div>
            <div class="form-group col-md-2">
                <label>
                    <span>Exp year:</span>
                    <input type="number" name="userExpYear" required ng-minlength="4" ng-maxlength="4" class="form-control"
                           placeholder="Exp year" ng-model="user.expYear">
                </label>
                <div ng-if="checkForm.userExpYear.$dirty" class="cust-alert alert-danger">
                    <ng-messages for="checkForm.userExpYear.$error">
                        <ng-message when="required">Please fill in this field.</ng-message>
                        <ng-message when="minlength">Too short Exp year.</ng-message>
                        <ng-message when="minlength">Wrong Exp year.</ng-message>
                    </ng-messages>
                </div>
            </div>
            <div class="form-group col-md-2">
                <label>
                    <span>CVV:</span>
                    <input type="text" name="userCVV" required ng-minlength="3" class="form-control" placeholder="CVV"
                           ng-model="user.svv">
                </label>
                <div ng-if="checkForm.userCVV.$dirty" class="cust-alert alert-danger">
                    <ng-messages for="checkForm.userCVV.$error">
                        <ng-message when="required">Please fill in this field.</ng-message>
                        <ng-message when="minlength">Too short Exp year.</ng-message>
                    </ng-messages>
                </div>
            </div>

            <div class="col-md-12">
                <h5 class="border-bottom">Comments/Requests</h5>
            </div>

            <div class="form-group col-md-12">
                <label>
                    <span>You can leave a comment:</span>
                    <textarea name="userComments" class="form-control user-comments" ng-model="user.comments"></textarea>
                </label>
            </div>

            <div class="alert alert-danger checkout-err" ng-if="checkoutErrorMessages.length > 0" ng-cloak ng-repeat="errorObj in checkoutErrorMessages">
                <span ng-bind-html="errorObj.message" compile-template></span>
            </div>

            <button ng-disabled="checkForm.$invalid" class="btn btn-lg btn-block btn-success resortpro_unit_submit_blue"
                    ng-click="submitCheckout()">
                <span class="resortpro_unit_submit_blue_message">Checkout</span>
                <i class="fa fa-refresh fa-spin fa-fw margin-bottom resortpro_unit_submit_blue_spinner"></i>
            </button>

        </div>
    </form>
</div>