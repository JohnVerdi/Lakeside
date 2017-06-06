(function() {
'use strict';
angular.module('resortpro.property')
    .controller('PropertyCheckoutController', [ '$scope', 'rpapi', function ($scope, rpapi) {

        $scope.checkoutCountries = [
            'United states',
            'Canada',
            'Russia',
            'Australia'
        ];

        $scope.testClick = function () {
            rpapi.getWithParams('GetPreReservationPrice', {
                'unit_id': $scope.book.unit_id,
                'startdate': $scope.book.checkin,
                'enddate': $scope.book.checkout,
                'occupants': $scope.book.occupants,
                'occupants_small' : $scope.book.occupants_small,
                'pets' : $scope.book.pets,
                'coupon_code' : $scope.couponCode
            }).success(function (obj) {
                if (obj.data !== undefined) {
                    rpapi.getWithParams('MakeReservation', {
                        'unit_id': $scope.book.unit_id,
                        'startdate': $scope.book.checkin,
                        'enddate': $scope.book.checkout,
                        'occupants': $scope.book.occupants,
                        'occupants_small' : $scope.book.occupants_small,
                        'pets' : $scope.book.pets,
                        'coupon_code' : $scope.couponCode,
                        "first_name": $scope.user.firstName,
                        "last_name": $scope.user.lastName,
                        "address": $scope.user.adress,
                        "city": $scope.user.city,
                        "state_name": $scope.user.state,
                        "zip": $scope.user.postalCode,
                        "email": $scope.user.email,
                        "client_comments":"",
                        "country_name": $scope.user.country,
                        "credit_card_type_id": $scope.user.cardType,
                        "credit_card_number": $scope.user.cardNumber,
                        "credit_card_cid": $scope.user.svv,
                        "credit_card_expiration_month": $scope.user.expMonth,
                        "credit_card_expiration_year": $scope.user.expYear,
                        "credit_card_amount":"0"
                    }).success(function (obj) {
                        console.log(obj);
                    })
                }
            });

        };



    }]);
})();
