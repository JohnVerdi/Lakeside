/****************************
 *
 * Checkout funcitons.  Only add functions related to checkout here
 *
 ***************************/
(function () {
  var app = angular.module('resortpro.checkout', ['resortpro.services']);


  app.directive('checkRequired', function () {
    return {
      restrict: 'A',
      require: 'ngModel',
      link: function (scope, element, attrs, ngModel) {
        ngModel.$validators.checkRequired = function (modelValue, viewValue) {
         var value = modelValue || viewValue;
         var match = scope.$eval(attrs.ngTrueValue) || true;
         return value && match === value;
       };
      }
    }
  });

  app.controller('CheckoutController', ['$scope', '$rootScope', '$sce', '$http', '$window', '$filter', '$location', 'Alert', 'rpapi', 'rpuri',
    function ($scope, $rootScope, $sce, $http, $window, $filter, $location, Alert, rpapi, rpuri) {
      $scope.error = false;
      $scope.startDate = $filter('date')(rpuri.getQueryStringParam('sd'), 'MM/dd/yyyy');
      $scope.endDate = $filter('date')(rpuri.getQueryStringParam('ed'), 'MM/dd/yyyy');
      $scope.unit = rpuri.getQueryStringParam('unit');
      $scope.occupants = rpuri.getQueryStringParam('oc');
      $scope.occupants_small = rpuri.getQueryStringParam('os');
      $scope.pets = rpuri.getQueryStringParam('pets');
      $scope.amenities = [];
      $scope.hasAddOns = false;
      $scope.hasTravelInsurance = false;
      $scope.hasDamageProtection = false;
      $scope.hasCfar = false;
      $scope.protectionError = false;
      $scope.confirmationId = 0;
      $scope.referrer_url = '';

      // load year drop down
      var year = new Date().getFullYear();
      var range = [];
      range.push(year);

      for (var i = 1; i < 10; i++) {
        range.push(year + i);
      }

      $scope.years = range;
      $scope.stepOneDisabled = true;
      $scope.stepTwoDisabled = true;
      //$scope.chkTravelInsuranceR = false;
      $scope.chkTravelInsurance = false;
      $scope.chkCfar = false;
      $scope.chkTravelInsuranceR = {selectedOption: false};
      $scope.chkDamageWaiverR = {selectedOption: false};
      $scope.chkCfarR = {selectedOption: false};
      $scope.validateStepOne = function (checkout) {
        if (checkout) {
          if (checkout.fname && checkout.lname && checkout.email && checkout.phone) {
            $scope.stepOneDisabled = false;
          } else {
            $scope.stepOneDisabled = true;
          }
        }
      }

      $scope.validateStepTwo = function () {
        var travelOk = true;
        var damageOk = true;

        if (($scope.hasTravelInsurance || $scope.hasCfar) && !($scope.chkCfar || $scope.chkTravelInsurance || $scope.chkTravelInsuranceNo)) {
          travelOk = false;
        }

        if (($scope.hasDamageProtection && !($scope.chkDamageWaiver || $scope.chkDamageWaiverNo))) {
          damageOk = false;
        }

        if (travelOk && damageOk) {

          $scope.stepTwoDisabled = false;
        } else {

          $scope.stepTwoDisabled = true;
        }

        $scope.getPreReservation();
      }

      $scope.calculateMarkup = function(strPrice){
        var price = parseInt(strPrice.replace('$','').replace(',',''));
        if($rootScope.rateMarkup > 0){
          var pct = 1 + (parseInt($rootScope.rateMarkup) / 100);                  
          price = price * pct;                
        }
        
        return price;
      }

      $scope.toggleCfar = function (feeId) {

        if ($scope.chkCfarR.selectedOption) {
          $scope.chkCfar = true;
          $scope.chkTravelInsuranceNo = false;
          $scope.chkTravelInsurance = false;
          $scope.chkTravelInsuranceR.selectedOption = false;

        } else {
          $scope.chkCfar = false;
        }

        setTimeout(function () {
          $scope.validateStepTwo();
        }, 100);
      }

      $scope.toggleTravelInsurance = function (feeId) {
        if ($scope.chkTravelInsuranceR.selectedOption) {
          $scope.chkTravelInsurance = true;
          $scope.chkCfar = false;
          $scope.chkCfarR.selectedOption = false;
          $scope.chkTravelInsuranceNo = false;
        } else {
          $scope.chkTravelInsurance = false;
        }

        setTimeout(function () {
          $scope.validateStepTwo();
        }, 100);
      }

      $scope.acceptCfar = function () {

        if ($scope.chkCfar) {
          $scope.chkTravelInsurance = false;
          $scope.chkTravelInsuranceNo = false;
          $scope.chkCfarR.selectedOption = true;
          $scope.chkTravelInsuranceR.selectedOption = false;
        } else {

          $scope.chkCfarR.selectedOption = false;
        }

        setTimeout(function () {
          $scope.validateStepTwo();
        }, 100);
      }

      $scope.acceptTravelInsurance = function () {

        if ($scope.chkTravelInsurance) {
          $scope.chkCfar = false;
          $scope.chkTravelInsuranceNo = false;
          $scope.chkTravelInsuranceR.selectedOption = true;
          $scope.chkCfarR.selectedOption = false;
        } else {
          $scope.chkTravelInsuranceR.selectedOption = false;
        }

        setTimeout(function () {
          $scope.validateStepTwo();
        }, 100);
      }

      $scope.rejectTravelInsurance = function () {
        if ($scope.chkTravelInsuranceNo) {
          $scope.chkTravelInsurance = false;
          $scope.chkTravelInsuranceR.selectedOption = false;

          $scope.chkCfar = false;
          $scope.chkCfarR.selectedOption = false;
        }

        setTimeout(function () {
          $scope.validateStepTwo();
        }, 100);
      }

      $scope.getAmenitiesWithRates = function () {
        params = {};
        params.startdate = $scope.startDate;
        params.enddate = $scope.endDate;
        params.unit_id = $scope.unit;

        if ($scope.unit && $scope.startDate && $scope.endDate && $scope.occupants) {
          rpapi.getWithParams('GetPropertyAmenitiesWithRates', params).success(function (obj) {
            if (obj.data.amenity_addon) {
              $scope.hasAddOns = true;
              if (obj.data.amenity_addon.amenity_id) {
                var amenitiesArray = [];
                amenitiesArray.push(obj.data.amenity_addon);
                $scope.amenities = amenitiesArray;
              } else {
                $scope.amenities = obj.data.amenity_addon;
              }
            }
          });
        }
      }

      $scope.addToReservation = function () {
        jQuery(".addOn:checked").each(function (index) {
          if (jQuery(this).prop('checked')) {
            //params['optional_fee_' + jQuery(this).val()] = 'yes';

            jQuery('#optional-fee-' + jQuery(this).val()).prop('checked', true);
          }
        });

        $scope.getPreReservation();
        jQuery('#modalAmenities').modal('hide')
      }

      $scope.toggleDamageWaiver = function (feeId) {
        if ($scope.chkDamageWaiverR.selectedOption) {
          $scope.chkDamageWaiver = true;
          $scope.chkDamageWaiverNo = false;
        } else {
          $scope.chkDamageWaiver = false;
        }

        setTimeout(function () {
          $scope.validateStepTwo();
        }, 100);
      }

      $scope.acceptDamageWaiver = function () {
        if ($scope.chkDamageWaiver) {
          $scope.chkDamageWaiverNo = false;
          $scope.chkDamageWaiverR.selectedOption = true;
        } else {
          $scope.chkDamageWaiverR.selectedOption = false;
        }

        setTimeout(function () {
          $scope.validateStepTwo();
        }, 100);
      }

      $scope.rejectDamageWaiver = function () {
        if ($scope.chkDamageWaiverNo) {
          $scope.chkDamageWaiver = false;
          $scope.chkDamageWaiverR.selectedOption = false;
        }

        setTimeout(function () {
          $scope.validateStepTwo();
        }, 100);
      }

      $scope.goToStepOne = function(){
          jQuery('#step2').collapse('hide');
          jQuery('#step1').collapse('show');
          jQuery('#step3').collapse('hide');
      };

      $scope.goToStep2 = function(){

        if($scope.formStep1.$valid){
          if($rootScope.checkoutSettings && $rootScope.checkoutSettings.createLeads == 1 && !$scope.confirmationId > 0){
            //create lead
            var params = {
              not_blocked_request: 'yes',
              startdate: $scope.startDate,
              enddate: $scope.endDate,
              occupants: $scope.occupants,
              occupants_small: $scope.occupants_small,
              first_name: $scope.checkout.fname,
              last_name: $scope.checkout.lname,
              email: $scope.checkout.email,
              mobile_phone: $scope.checkout.phone,
              pets: $scope.pets,
              disable_minimal_days: 'yes'
            };

            if($rootScope.bookingSettings && $rootScope.bookingSettings.hearedAboutId > 0)
              params['hear_about_id'] = $rootScope.bookingSettings.hearedAboutId;

            if($scope.referrer_url != '')
              params['referrer_url'] = $scope.referrer_url;
            
            if($rootScope.roomTypeLogic == 1){
              params['use_room_type_logic'] = 1;
              params['condo_type_id'] = $scope.checkout.condo_type_id;
              params['location_id'] = $scope.checkout.location_id;
            }else{
              params['unit_id'] = $scope.unit;
            }

            params['flags'] = [{flag_id : 2027}];

            rpapi.getWithParams('MakeReservation', params).success(function (obj) {
              if (obj.status) {
                Alert.add(Alert.errorType, obj.status.description);
              } else {
                $scope.confirmationId = obj.data.reservation.confirmation_id;
              }
            });
          }

          if($scope.reservationDetails.optional_fees.length > 0){
            $scope.goToStepTwo();
          }else{
            $scope.goToStepThree();
          }
        }
      }

      $scope.goToStepTwo = function () {

        jQuery('#step2').collapse('show');
        jQuery('#step1').collapse('hide');
        jQuery('#step3').collapse('hide');

        if ($scope.hasAddOns && $rootScope.checkoutSettings.useAddOns == 1) {
          jQuery('#modalAmenities').modal();
        }
      }

      $scope.goToStep3 = function(){

        if(!$scope.stepTwoDisabled){

          $scope.protectionError = false;
          $scope.goToStepThree();
        }else{

          $scope.protectionError = true;
        }
      }

      $scope.goToStepThree = function () {
        jQuery('#step3').collapse('show');
        jQuery('#step2').collapse('hide');
        jQuery('#step1').collapse('hide');
      }

      $scope.validateStep3 = function(checkout){

        if($scope.formStep3.$valid){
          $scope.processCheckout(checkout);
        }
      };

      $scope.initCheckout = function () {
        // first check if unit is available for posted dates
        if ($scope.hash !== '') {
          var params = {
            hash: $scope.hash
          };

          rpapi.getWithParams('GetReservationPrice', params).success(function (obj) {
            var res_price = obj.data;
          
            if(res_price.optional_fees.id){
              resultData = [];
              resultData.push(res_price.optional_fees);
              res_price.optional_fees = resultData;
            }

            if(res_price.required_fees.id){
              resultData = [];
              resultData.push(res_price.required_fees);
              res_price.required_fees = resultData;
            }

            if(res_price.taxes_details.id){
              resultData = [];
              resultData.push(res_price.taxes_details);
              res_price.taxes_details = resultData;
            }

            var total_taxes = 0;
            angular.forEach(res_price.taxes_details, function (value, key) {
              total_taxes += value.value;
            });
            angular.forEach(res_price.required_fees, function (value, key) {
              total_taxes += value.value;
            });
            angular.forEach(res_price.optional_fees, function (value, key) {
              if (value.damage_waiver == 1) {
                $scope.hasDamageProtection = true;
                $scope.damageProtection = value.value;
              }

              if (value.travel_insurance == 1) {
                $scope.hasTravelInsurance = true;
                $scope.travelInsurance = value.value;
              }

              if(value.cfar == 1){
                $scope.hasCfar = true;
                $scope.cfar = value.value;
              }

              if (!$scope.hasDamageProtection && !$scope.hasTravelInsurance) {
                $scope.stepTwoDisabled = false;
              }
            });

            angular.forEach(res_price.security_deposits, function (value, key) {
              $scope.securityDeposit = value.value;
            });

            $scope.subTotal = $scope.calculateMarkup((obj.data.price + obj.data.coupon_discount).toString());
            var dif = $scope.subTotal - obj.data.coupon_discount - obj.data.price;                            
            $scope.taxesAndFees = total_taxes - dif;
        
            if (res_price.reservation_id) {
              rpapi.getWithParams('GetReservationInfo', params).success(function (obj) {
                var res_info = obj.data.reservation;

                $scope.unit = res_info.unit_id;

                var params = {
                  startdate : res_info.startdate,
                  enddate : res_info.enddate,
                  occupants : res_info.occupants,                  
                  use_room_type_logic : parseInt($rootScope.roomTypeLogic),
                  page_number : 1,
                  page_results_number : 1000
                };

                if(parseInt($rootScope.roomTypeLogic) !== 1){
                  params['unit_id'] = res_info.unit_id;
                }else{
                  params['condo_type_id'] = res_info.condo_type_id;
                  params['location_id'] = res_info.location_id;
                }

                rpapi.getWithParams('GetPropertyAvailability', params).success(function (obj) {

                  if (obj.status) {
                    $scope.error = true;
                    $scope.errorMessage = obj.status.description;
                    return false;
                  } else {
                    var result = obj.data.available_properties;
                    if (result.pagination.total_units == 0) {
                      $scope.error = true;
                      $scope.errorMessage = 'Sorry, this property is not available during the selected dates.';
                      return false;
                    }
                  }
                }).error(function () {
                  $scope.error = true;
                  $scope.errorMessage = Alert.errorMessage;
                  return false;
                });

                $scope.checkout = {
                  fname : res_info.first_name,
                  lname : res_info.last_name,
                  email : res_info.email,
                  phone : $scope.isEmptyObject(res_info.phone) ? '' : res_info.phone,
                  unit  : res_info.unit_id,
                  promo_code : $scope.isEmptyObject(res_info.coupon_code) ? '' : res_info.coupon_code
                };

                if($scope.startDate == '' || $scope.startDate == undefined)
                  $scope.startDate = res_info.startdate;

                if($scope.endDate == '' || $scope.endDate == undefined)
                  $scope.endDate = res_info.enddate;

                if($scope.occupants == '' || $scope.occupants == undefined)
                  $scope.occupants = res_info.occupants;

                if($scope.occupants_small == '' || $scope.occupants_small == undefined)
                  $scope.occupants_small = res_info.occupants_small;

                if($scope.pets == '' || $scope.pets == undefined)
                  $scope.pets = res_info.pets;

                $scope.reservationDetails = res_price;
                $scope.checkout.address = res_info.address1;
                $scope.stepOneDisabled = false;
                $scope.stepTwoDisabled = false;

                $scope.getTermsAndConditions();
                $scope.getPropertyInfo();
                $scope.getCountries();
                $scope.getStates();

              });
            }
          });
        } else {
          var params = {
            startdate : $scope.startDate,
            enddate : $scope.endDate,
            occupants : $scope.occupants,
            unit_id : $scope.unit,
            page_number : 1,
            page_results_number : 1000,
            use_room_type_logic : parseInt($rootScope.roomTypeLogic)
          };

          if ($scope.unit && $scope.startDate && $scope.endDate && $scope.occupants) {
            rpapi.getWithParams('GetPropertyAvailability', params).success(function (obj) {

              if (obj.status) {
                $scope.error = true;
                $scope.errorMessage = obj.status.description;
              } else {
                var result = obj.data.available_properties;
                if (result.pagination.total_units == 0) {
                  $scope.error = true;
                  $scope.errorMessage = 'Sorry, this property is not available during the selected dates.';
                } else {

                  $scope.getPreReservation();
                  $scope.getTermsAndConditions();
                  $scope.getPropertyInfo();
                  $scope.getCountries();
                  $scope.getStates();
                }
              }
            }).error(function () {
              $scope.error = true;
              $scope.errorMessage = Alert.errorMessage;
            });
          } else {
            $scope.error = true;
            $scope.errorMessage = Alert.errorMessage;
          }
        }
      };

      $scope.isEmptyObject = function (obj) {
        return angular.equals({}, obj);
      };

      $scope.getPreReservation = function () {
        var params = {
          startdate: $scope.startDate,
          enddate: $scope.endDate,
          occupants: $scope.occupants,
          unit_id: $scope.unit,
          occupants_small : $scope.occupants_small,
          pets: $scope.pets
        };

        var method = 'GetPreReservationPrice';

        if($scope.checkout && $scope.checkout.promo_code != '')
          params['coupon_code'] = $scope.checkout.promo_code;

        var arr_fees = [];
        jQuery(".optional_fee:checked").each(function (index) {
          if (jQuery(this).prop('checked')) {
            params['optional_fee_' + jQuery(this).val()] = 'yes';
            arr_fees.push(jQuery(this).val());
          }
        });

        if ($scope.hash !== '') {
          params['hash'] = $scope.hash;
          method = 'GetReservationPrice';
        }

        run_waitMe('#step2', 'bounce', 'Updating Price');

        rpapi.getWithParams(method, params).success(function (obj) {
          hide_waitMe('#step2');

          if (obj.data.optional_fees.id) {
            resultData = [];
            resultData.push(obj.data.optional_fees);
            obj.data.optional_fees = resultData;
          }

          if (obj.data.required_fees.id) {
            resultData = [];
            resultData.push(obj.data.required_fees);
            obj.data.required_fees = resultData;
          }

          if (obj.data.taxes_details.id) {
            resultData = [];
            resultData.push(obj.data.taxes_details);
            obj.data.taxes_details = resultData;
          }

          if (obj.data.security_deposits && obj.data.security_deposits.security_deposit.ledger_id) {
            resultData = [];
            resultData.push(obj.data.security_deposits.security_deposit);
            obj.data.security_deposits.security_deposit = resultData;
          }

          var total_fees = 0;
          var total_taxes = 0;
          if(obj.data.required_fees.id){
            total_fees = obj.data.required_fees.value;
          }else{
            angular.forEach(obj.data.required_fees, function (fee, i) {
              total_fees += fee.value;
            });
          }
          if(obj.data.taxes_details.id){
            total_taxes = obj.data.taxes_details.value;
          }else{
            angular.forEach(obj.data.taxes_details, function (fee, i) {
              total_taxes += fee.value;
            });
          }
          
          $scope.subTotal = $scope.calculateMarkup((obj.data.price + obj.data.coupon_discount).toString());

          var dif = $scope.subTotal - obj.data.coupon_discount - obj.data.price;

          $scope.totalFees = total_fees;
          $scope.totalTaxes = total_taxes;

          $scope.taxesAndFees = total_taxes + total_fees - dif;

          $scope.totalPrice = obj.data.total;

          if(arr_fees.length > 0)
            params['optional_fee'] = arr_fees.join();

          params.use_room_type_logic = parseInt($rootScope.roomTypeLogic);
          rpapi.getWithParams('GetPropertyAvailabilityWithRates', params).success(function (obj2) {

            if(obj2.data){
              if(obj2.data.available_properties.property.expected_charges.type_id){
                resultData = [];
                resultData.push(obj2.data.available_properties.property.expected_charges);
                obj2.data.available_properties.property.expected_charges = resultData;
              }

              obj.data.expected_charges = obj2.data.available_properties.property.expected_charges;
              angular.forEach(obj.data.expected_charges, function(charge, i){                
                
                var strDate = charge.charge_date.split('/');

                $scope.paymentLimit = strDate[2] + '-' + strDate[0] + '-' + strDate[1];
              });
            }
          });
                
          $scope.reservationDetails = obj.data;

          angular.forEach(obj.data.optional_fees, function (value, key) {
            if (value.damage_waiver == 1) {
              $scope.hasDamageProtection = true;
              $scope.damageProtection = value.value;
            }

            if (value.travel_insurance == 1) {
              $scope.hasTravelInsurance = true;
              $scope.travelInsurance = value.value;
            }

            if(value.cfar == 1){
              $scope.hasCfar = true;
              $scope.cfar = value.value;
            }
          });

          if (!$scope.hasDamageProtection && !$scope.hasTravelInsurance && !$scope.hasCfar) {
            $scope.stepTwoDisabled = false;
          }

          var totalDeposits = 0;
          if(obj.data.security_deposits){
            angular.forEach(obj.data.security_deposits.security_deposit, function (value, key) {
              totalDeposits += value.deposit_required;
            });
          }

          $scope.securityDeposit = totalDeposits;
        });
      };

      $scope.getTermsAndConditions = function () {

        if($scope.unit > 0){
          var params = {
            trigger_id: 18,
            unit_id: $scope.unit
          };

          rpapi.getWithParams('GetPropertyDocumentHtml', params).success(function (obj) {
            if (obj.data && !jQuery.isEmptyObject(obj.data.document_html_code)) {
              $scope.terms = $sce.trustAsHtml(obj.data.document_html_code);
            } else {
              $scope.terms = '';
            }
          });
        }
      };

      $scope.getCountries = function(){
        rpapi.get('GetCountriesList').success(function(obj){
          $scope.countries = obj.data.countries;
        });
      };

      $scope.getStates = function(){
        var country = ($scope.checkout.country && $scope.checkout.country != '') ?  $scope.checkout.country : 'US';
        var params = {
          country_name : country
        }

        rpapi.getWithParams('GetStatesList', params).success(function(obj){
          $scope.states = obj.data.states;
        });
      };

      $scope.processCheckout = function (checkout) {
        run_waitMe('#step3', 'bounce', 'Processing your request');
        var params = {
          pricing_model: 0,
          startdate: $scope.startDate,
          enddate: $scope.endDate,
          occupants: $scope.occupants,
          occupants_small: $scope.occupants_small,
          first_name: checkout.fname,
          last_name: checkout.lname,
          email: checkout.email,
          phone: checkout.phone,
          mobile_phone: checkout.phone,
          address: checkout.address,
          address2: checkout.address2,
          city: checkout.city,
          zip: checkout.postal_code,
          state_name: checkout.state,
          country_name: checkout.country,
          credit_card_number: checkout.card_number,
          credit_card_expiration_month: checkout.expire_month,
          credit_card_expiration_year: checkout.expire_year,
          credit_card_type_id: checkout.card_type,
          credit_card_cid: checkout.card_cvv
        };

        if($rootScope.roomTypeLogic == 1){
          params['use_room_type_logic'] = 1;
          params['condo_type_id'] = $scope.checkout.condo_type_id;
          params['location_id'] = $scope.checkout.location_id;
        }else{
          params['unit_id'] = $scope.unit;
        }

        if($rootScope.bookingSettings){
          if($rootScope.bookingSettings.hearedAboutId > 0)
            params['hear_about_id'] = $rootScope.bookingSettings.hearedAboutId;

          if($rootScope.bookingSettings.blockedRequest == 1)
            params['status_id'] = 10;
        }
    
        if($scope.checkout && $scope.checkout.promo_code != '')
          params['coupon_code'] = $scope.checkout.promo_code;

        if($scope.pets && $scope.pets > 0)
          params['pets'] = $scope.pets;

        if($scope.hash != '')
          params['hash'] = $scope.hash;

        if($scope.referrer_url != '')
          params['referrer_url'] = $scope.referrer_url;
    
        if($scope.confirmationId > 0)
          params['confirmation_id'] = $scope.confirmationId

        jQuery(".optional_fee:checked").each(function (index) {
          if (jQuery(this).prop('checked')) {
            params['optional_fee_' + jQuery(this).val()] = 'yes';
          }
        });
      
        rpapi.getWithParams('MakeReservation', params).success(function (obj) {
          hide_waitMe('#step3');

          if (obj.status) {
            Alert.add(Alert.errorType, obj.status.description);
          } else {
            var res = obj.data.reservation;
            jQuery('#confirmation_id').val(res.confirmation_id);
            jQuery('#location_name').val(res.location_name);
            jQuery('#condo_type_name').val(res.condo_type_name);
            jQuery('#unit_name').val(res.unit_name);
            jQuery('#startdate').val(res.startdate);
            jQuery('#enddate').val(res.enddate);
            jQuery('#occupants').val(res.occupants);
            jQuery('#occupants_small').val(res.occupants_small);
            jQuery('#pets').val(res.pets);
            jQuery('#price_common').val(res.price_common);
            jQuery('#price_balance').val(res.price_balance);
            jQuery('#travelagent_name').val(res.travelagent_name);
            jQuery('#email').val(res.email);
            jQuery('#fname').val(res.fname);
            jQuery('#lname').val(res.lname);
            jQuery('#unit_id').val(res.unit_id);
            jQuery('#form_thankyou').submit();
          }
        });
      };

      $scope.getPropertyInfo = function () {
        rpapi.getWithParams('GetPropertyInfo', {'unit_id': $scope.unit}).success(function (obj) {
          $scope.property = obj.data;

          if($scope.checkout){
            $scope.checkout.country = 'US';
            $scope.checkout.condo_type_id = obj.data.condo_type_id;
            $scope.checkout.location_id = obj.data.location_id;
          }
        });
      };
    }
  ]);
})();