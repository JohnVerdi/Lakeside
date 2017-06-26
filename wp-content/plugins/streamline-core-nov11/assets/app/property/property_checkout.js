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
        $scope.stateDictionaryUSA = [
            {
                "name": "Alabama",
                "abbreviation": "AL"
            },
            {
                "name": "Alaska",
                "abbreviation": "AK"
            },
            {
                "name": "American Samoa",
                "abbreviation": "AS"
            },
            {
                "name": "Arizona",
                "abbreviation": "AZ"
            },
            {
                "name": "Arkansas",
                "abbreviation": "AR"
            },
            {
                "name": "California",
                "abbreviation": "CA"
            },
            {
                "name": "Colorado",
                "abbreviation": "CO"
            },
            {
                "name": "Connecticut",
                "abbreviation": "CT"
            },
            {
                "name": "Delaware",
                "abbreviation": "DE"
            },
            {
                "name": "District Of Columbia",
                "abbreviation": "DC"
            },
            {
                "name": "Federated States Of Micronesia",
                "abbreviation": "FM"
            },
            {
                "name": "Florida",
                "abbreviation": "FL"
            },
            {
                "name": "Georgia",
                "abbreviation": "GA"
            },
            {
                "name": "Guam",
                "abbreviation": "GU"
            },
            {
                "name": "Hawaii",
                "abbreviation": "HI"
            },
            {
                "name": "Idaho",
                "abbreviation": "ID"
            },
            {
                "name": "Illinois",
                "abbreviation": "IL"
            },
            {
                "name": "Indiana",
                "abbreviation": "IN"
            },
            {
                "name": "Iowa",
                "abbreviation": "IA"
            },
            {
                "name": "Kansas",
                "abbreviation": "KS"
            },
            {
                "name": "Kentucky",
                "abbreviation": "KY"
            },
            {
                "name": "Louisiana",
                "abbreviation": "LA"
            },
            {
                "name": "Maine",
                "abbreviation": "ME"
            },
            {
                "name": "Marshall Islands",
                "abbreviation": "MH"
            },
            {
                "name": "Maryland",
                "abbreviation": "MD"
            },
            {
                "name": "Massachusetts",
                "abbreviation": "MA"
            },
            {
                "name": "Michigan",
                "abbreviation": "MI"
            },
            {
                "name": "Minnesota",
                "abbreviation": "MN"
            },
            {
                "name": "Mississippi",
                "abbreviation": "MS"
            },
            {
                "name": "Missouri",
                "abbreviation": "MO"
            },
            {
                "name": "Montana",
                "abbreviation": "MT"
            },
            {
                "name": "Nebraska",
                "abbreviation": "NE"
            },
            {
                "name": "Nevada",
                "abbreviation": "NV"
            },
            {
                "name": "New Hampshire",
                "abbreviation": "NH"
            },
            {
                "name": "New Jersey",
                "abbreviation": "NJ"
            },
            {
                "name": "New Mexico",
                "abbreviation": "NM"
            },
            {
                "name": "New York",
                "abbreviation": "NY"
            },
            {
                "name": "North Carolina",
                "abbreviation": "NC"
            },
            {
                "name": "North Dakota",
                "abbreviation": "ND"
            },
            {
                "name": "Northern Mariana Islands",
                "abbreviation": "MP"
            },
            {
                "name": "Ohio",
                "abbreviation": "OH"
            },
            {
                "name": "Oklahoma",
                "abbreviation": "OK"
            },
            {
                "name": "Oregon",
                "abbreviation": "OR"
            },
            {
                "name": "Palau",
                "abbreviation": "PW"
            },
            {
                "name": "Pennsylvania",
                "abbreviation": "PA"
            },
            {
                "name": "Puerto Rico",
                "abbreviation": "PR"
            },
            {
                "name": "Rhode Island",
                "abbreviation": "RI"
            },
            {
                "name": "South Carolina",
                "abbreviation": "SC"
            },
            {
                "name": "South Dakota",
                "abbreviation": "SD"
            },
            {
                "name": "Tennessee",
                "abbreviation": "TN"
            },
            {
                "name": "Texas",
                "abbreviation": "TX"
            },
            {
                "name": "Utah",
                "abbreviation": "UT"
            },
            {
                "name": "Vermont",
                "abbreviation": "VT"
            },
            {
                "name": "Virgin Islands",
                "abbreviation": "VI"
            },
            {
                "name": "Virginia",
                "abbreviation": "VA"
            },
            {
                "name": "Washington",
                "abbreviation": "WA"
            },
            {
                "name": "West Virginia",
                "abbreviation": "WV"
            },
            {
                "name": "Wisconsin",
                "abbreviation": "WI"
            },
            {
                "name": "Wyoming",
                "abbreviation": "WY"
            }
        ];

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
                        if(fee.count > 0){
                            var key = 'optional_fee_' + fee.id;
                            prepearedMakeReservationData[key] = fee.count;
                        }
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
