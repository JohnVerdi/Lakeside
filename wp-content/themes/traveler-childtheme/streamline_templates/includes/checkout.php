<h1>Checkout</h1>
<form name="checkout-form" novalidate>
    <div class="row">
        <div class="col-md-12">
            <h5 class="border-bottom">Personal information</h5>
        </div>

        <div class="form-group col-md-6">
            <label for="checkout-first-name">First name:</label>
            <input type="text" class="form-control" id="checkout-first-name" name="checkout-first-name" placeholder="First name">
        </div>
        <div class="form-group col-md-6">
            <label for="checkout-last-name">Last name:</label>
            <input type="text" class="form-control" id="checkout-last-name" name="checkout-last-name" placeholder="Last name">
        </div>
        <div class="form-group col-md-6">
            <label for="checkout-email">Email:</label>
            <input type="email" class="form-control" id="checkout-email" name="checkout-email" placeholder="Email">
        </div>
        <div class="form-group col-md-6">
            <label for="checkout-phone">Phone:</label>
            <input type="text" class="form-control" id="checkout-phone" name="checkout-phone" placeholder="(###) ###-####">
        </div>

        <div class="col-md-12">
            <h5 class="border-bottom">Payment information</h5>
        </div>

        <div class="form-group col-md-4">
            <label for="checkout-adress">Adress:</label>
            <input type="text" class="form-control" id="checkout-adress" name="checkout-adress" placeholder="Adress name" required>
        </div>
        <div class="form-group col-md-4">
            <label for="checkout-adress2">Adress 2:</label>
            <input type="text" class="form-control" id="checkout-adress2" name="checkout-adress2" placeholder="Optional">
        </div>
        <div class="form-group col-md-4">
            <label for="checkout-country">Country:</label>
            <select class="form-control" type="text" class="form-control" id="checkout-country" name="checkout-country">
                <option>United states</option>
                <option>United states</option>
                <option>Canada</option>
                <option>Canada</option>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="checkout-first-name">City:</label>
            <input type="text" class="form-control" id="checkout-city" name="checkout-city" placeholder="City">
        </div>
        <div class="form-group col-md-4">
            <label for="checkout-first-name">State:</label>
            <input type="text" class="form-control" id="checkout-state" name="checkout-state" placeholder="State">
        </div>
        <div class="form-group col-md-2">
            <label for="checkout-first-name">Postal code:</label>
            <input type="text" class="form-control" id="checkout-postal-code" name="checkout-postal-code" placeholder="Postal code">
        </div>

        <div class="form-group col-md-3">
            <label for="checkout-first-name">Cart Type:</label>
            <input type="text" class="form-control" id="checkout-card-type" name="checkout-card-type" placeholder="Cart Type">
        </div>
        <div class="form-group col-md-3">
            <label for="checkout-first-name">Card number:</label>
            <input type="text" class="form-control" id="checkout-card-number" name="checkout-card-number" placeholder="Card number">
        </div>
        <div class="form-group col-md-2">
            <label for="checkout-first-name">Exp Month:</label>
            <input type="number" class="form-control" id="checkout-exp-month" name="checkout-exp-month" placeholder="Exp Month">
        </div>
        <div class="form-group col-md-2">
            <label for="checkout-first-name">Exp year:</label>
            <input type="number" class="form-control" id="checkout-exp-year" name="checkout-exp-year" placeholder="Exp year">
        </div>
        <div class="form-group col-md-2">
            <label for="checkout-first-name">CVV:</label>
            <input type="text" class="form-control" id="checkout-cvv" name="checkout-cvv" placeholder="CVV">
        </div>

    </div>

</form>