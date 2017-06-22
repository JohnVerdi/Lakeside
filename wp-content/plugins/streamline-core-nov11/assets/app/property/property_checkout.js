(function() {
'use strict';
angular.module('resortpro.property')
    .directive('compileTemplate', function($compile, $parse){
        return {
            link: function(scope, element, attr){
                var parsed = $parse(attr.ngBindHtml);
                function getStringValue() { return (parsed(scope) || '').toString(); }
                scope.$watch(getStringValue, function() {
                    $compile(element, null, -9999)(scope);  //The -9999 makes it skip directives so that we do not recompile ourselves
                });
            }
        }
    })
    .controller('PropertyCheckoutController', [ '$scope', 'rpapi', '$sce', '$timeout', '$http', '$compile',
        function ($scope, rpapi, $sce, $timeout, $http ,$compile) {
        $scope.checkoutErrorMessages = [];
        $scope.stateDictionaryUSA = {
            "AK" : "Alaska",
            "AL" : "Alabama",
            "AR" : "Arkansas",
            "AS" : "American Samoa",
            "AZ" : "Arizona",
            "CA" : "California",
            "CO" : "Colorado",
            "CT" : "Connecticut",
            "DC" : "District of Columbia",
            "DE" : "Delaware",
            "FL" : "Florida",
            "GA" : "Georgia",
            "GU" : "Guam",
            "HI" : "Hawaii",
            "IA" : "Iowa",
            "ID" : "Idaho",
            "IL" : "Illinois",
            "IN" : "Indiana",
            "KS" : "Kansas",
            "KY" : "Kentucky",
            "LA" : "Louisiana",
            "MA" : "Massachusetts",
            "MD" : "Maryland",
            "ME" : "Maine",
            "MI" : "Michigan",
            "MN" : "Minnesota",
            "MO" : "Missouri",
            "MS" : "Mississippi",
            "MT" : "Montana",
            "NC" : "North Carolina",
            "ND" : "North Dakota",
            "NE" : "Nebraska",
            "NH" : "New Hampshire",
            "NJ" : "New Jersey",
            "NM" : "New Mexico",
            "NV" : "Nevada",
            "NY" : "New York",
            "OH" : "Ohio",
            "OK" : "Oklahoma",
            "OR" : "Oregon",
            "PA" : "Pennsylvania",
            "PR" : "Puerto Rico",
            "RI" : "Rhode Island",
            "SC" : "South Carolina",
            "SD" : "South Dakota",
            "TN" : "Tennessee",
            "TX" : "Texas",
            "UT" : "Utah",
            "VA" : "Virginia",
            "VI" : "Virgin Islands",
            "VT" : "Vermont",
            "WA" : "Washington",
            "WI" : "Wisconsin",
            "WV" : "West Virginia",
            "WY" : "Wyoming"
        };

        $scope.checkoutCountries = [
            'United states',
            'Canada',
            'Russia',
            'Australia'
        ];

        $scope.cardTypes = [
            {
                id: 1,
                value: "Visa"
            },
            {
                id: 2,
                value: "MasterCard"
            },
            {
                id: 3,
                value: "AmericanExpress"
            },
            {
                id: 4,
                value: "Discover"
            }
        ];

        $scope.submitCheckout = function () {
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

                    var prepearedMakeReservationData = {
                        'unit_id': $scope.book.unit_id || '',
                        'startdate': $scope.book.checkin || '',
                        'enddate': $scope.book.checkout || '',
                        'occupants': $scope.book.occupants || '',
                        'occupants_small' : $scope.book.occupants_small || '',
                        'pets' : $scope.book.pets || '',
                        'coupon_code' : $scope.couponCode || '',
                        "first_name": $scope.user.firstName || '',
                        "last_name": $scope.user.lastName || '',
                        "address": $scope.user.adress || '',
                        "city": $scope.user.city || '',
                        "state_name": $scope.user.state || '',
                        "zip": $scope.user.postalCode || '',
                        "email": $scope.user.email || '',
                        "client_comments": $scope.user.comments || '',
                        "country_name": $scope.user.country || '',
                        "credit_card_type_id": $scope.user.cardType || '',
                        "credit_card_number": $scope.user.cardNumber || '',
                        "credit_card_cid": $scope.user.svv || '',
                        "credit_card_expiration_month": $scope.user.expMonth || '',
                        "credit_card_expiration_year": $scope.user.expYear || '',
                        "credit_card_amount": $scope.total_reservation || ''
                    };

                    // Send optional fees cound data, but API doesn't process yet.
                    angular.forEach($scope.optional_fees, function (fee) {
                        var key = 'optional_fee_' + fee.id;
                        prepearedMakeReservationData[key] = fee.count;
                    });

                    rpapi.getWithParams('MakeReservation', prepearedMakeReservationData).success(function (obj) {

                        if (obj.status && obj.status.description){
                            $scope.checkoutErrorMessages.push({message : $sce.trustAsHtml(obj.status.description) });
                            $scope.clearCheckoutErrorMessage();
                        }

                        if (obj.data && obj.data.reservation && obj.data.reservation.confirmation_id) {
                            try{
                                $http.get($scope.listIncludePages.checkoutSuccessTemplateDestination).then(function (response) {
                                    if (response.statusText === 'OK') {
                                        $scope.successCheckoutData = obj.data.reservation;
                                        delete $scope.successCheckoutData.travelagent_name;

                                        var compiledeHTML = $compile(response.data)($scope);

                                        jQuery("#single-room")
                                            .hide(0)
                                            .empty()
                                            .append(compiledeHTML)
                                            .fadeIn(600);
                                    }
                                });
                            }catch(e){
                                console.log('ERROR: Cant include checkout success template!');
                            }
                        }
                    })
                } else {
                    $scope.checkoutErrorMessages.push({message : 'Server does not respond, try again later or contact the administrator.'});
                    $scope.clearCheckoutErrorMessage();
                }
            });
        };

        $scope.clearCheckoutErrorMessage = function () {
            $timeout( function(){
                $scope.checkoutErrorMessages.shift();
            }, 15000 );
        };

    }]);
})();
