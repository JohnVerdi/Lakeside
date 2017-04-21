/****************************
 *
 * Property funcitons.  Only add functions related to products here
 *
 ***************************/
(function () {
  var app = angular.module('resortpro.property', ['resortpro.services', 'resortpro.filters', 'angularUtils.directives.dirPagination', 'ngCookies']);

  app.directive('ngAlt', function () {
    return {
      restrict: 'A',
      link: function (scope, elem, attrs) {
        if (attrs.ngAlt) {
          elem.on('load', function (event) {
            elem[0].setAttribute("alt", attrs.ngAlt);
          });
        }
      }
    };
  });

  app.directive("calendar", function ($rootScope) {
    return {
      restrict: "A",
      require: "ngModel",
      scope: {
        showDays: '&',
        updateModalCheckin: '&'
      },
      link: function (scope, elem, attrs, pCtrl) {
        var options = {
          dateFormat: "mm/dd/yy",
          minDate: 0,
          numberOfMonths: 3,
          showButtonPanel: false,
          onSelect: function (dateText) {

            scope.updateModalCheckin({date: dateText});
            pCtrl.$setViewValue(dateText);

            var myDateArr = dateText.split('/');
            var month = myDateArr[0] - 1;
            var day = myDateArr[1];
            var numDays = 0;
            var foundDay = false;
            jQuery('.availability-calendar .ui-datepicker-calendar td').each(function () {
              if (jQuery(this).attr('data-month') == month) {
                if (parseInt(jQuery(this).find('a').html()) > parseInt(day)) {
                  foundDay = true;
                  numDays++;
                } else if (foundDay) {
                  return false;
                }
              }
            });

            jQuery('#modal_checkin').val(dateText);
            jQuery('#myModal').modal();
            setTimeout(function () {
              add_tooltip();
            }, 500);
          },
          beforeShowDay: function (date) {
            return scope.showDays({date: date});
          },
          onChangeMonthYear: function (year, month, inst) {
            setTimeout(function () {
              add_tooltip();
            }, 500);
          }
        };

        // jqueryfy the element
        elem.datepicker(options);
      }
    }
  });

  app.directive('sdpicker', function () {
    return {
      restrict: 'A',
      require: 'ngModel',
      link: function (scope, element, attrs, ngModelCtrl) {
        jQuery(function () {

          var checkin_days = 0;
          if(!isNaN(jQuery('#search_start_date').data('checkin-days'))){
            checkin_days = jQuery('#search_start_date').data('checkin-days');
          }

          element.datepicker({
            dateFormat: 'mm/dd/yy',
            minDate: "+"+checkin_days+"d",
            onSelect: function (date) {
              ngModelCtrl.$setViewValue(date);
              scope.$apply();
              var frm = new Date(date);
              nts = 1;
              if (!isNaN(jQuery('#search_start_date').attr('data-min-stay'))) {
                nts = parseInt(jQuery('#search_start_date').attr('data-min-stay'));
              }
              var the_year = frm.getFullYear();
              if (the_year < 2000) the_year = 2000 + the_year % 100;
              var to = new Date(the_year, frm.getMonth(), frm.getDate() + nts);
              jQuery('#search_end_date').datepicker('option', 'minDate', to);
              scope.search.start_date = frm.format("mm/dd/yyyy");
              scope.search.end_date = to.format("mm/dd/yyyy");
              scope.clearProperties();
              scope.availabilitySearch(scope.search);
            }
          });
        });
      }
    }
  });

  app.directive('edpicker', function () {
    return {
      restrict: 'A',
      minDate: "+1d",
      require: 'ngModel',
      link: function (scope, element, attrs, ngModelCtrl) {
        jQuery(function () {
          var startdate = jQuery('#search_start_date').val();
          var frm = new Date(startdate);
          element.datepicker({
            dateFormat: 'mm/dd/yy',
            minDate: frm,
            onSelect: function (date) {
              ngModelCtrl.$setViewValue(date);
              scope.$apply();
              var frm = new Date(date);
              var the_year = frm.getFullYear();
              if (the_year < 2000) the_year = 2000 + the_year % 100;
              var to = new Date(the_year, frm.getMonth(), frm.getDate());
              scope.search.end_date = to.format("mm/dd/yyyy");
              scope.clearProperties();
              scope.availabilitySearch(scope.search);
            }
          });
        });
      }
    }
  });

  app.directive('bookcheckin', function ($rootScope) {
    return {
      restrict: 'A',
      scope: {
        showDays: '&',
        updatePrice: '&',
        updateCheckout: '&'
      },
      require: 'ngModel',
      link: function (scope, element, attrs, ngModelCtrl) {
        jQuery(function () {

          var days = 0;
          if (!isNaN(jQuery('#book_start_date').attr('data-checkin-days'))) {
              days = jQuery('#book_start_date').attr('data-checkin-days');
          }
          element.datepicker({
            dateFormat: 'mm/dd/yy',
            minDate: "+"+days+"d",
            onSelect: function (date) {
              ngModelCtrl.$setViewValue(date);
              scope.$apply();
              var frm = new Date(date);
              nts = 1;
              if (!isNaN(jQuery('#book_start_date').attr('data-min-stay'))) {
                nts = jQuery('#book_start_date').attr('data-min-stay');
              }
              var the_year = frm.getFullYear();
              if (the_year < 2000) the_year = 2000 + the_year % 100;
              var to = new Date(the_year, frm.getMonth(), parseInt(frm.getDate()) + parseInt(nts));
              jQuery('#book_end_date').datepicker('option', 'minDate', to);
              scope.updateCheckout({date: to});
              scope.updatePrice();
            },
            onClose: function(){
              if(jQuery('#book_start_date').val() != '' && jQuery('#book_end_date').val() == '')
                jQuery('#book_end_date').datepicker( "show" );
            },
            beforeShowDay: function (date) {
              return scope.showDays({date: date});
            }
          });
        });
      }
    }
  });

  app.directive('bookcheckout', function ($rootScope) {
    return {
      restrict: 'A',
      scope: {
        showDays: '&',
        updatePrice: '&'
      },
      require: 'ngModel',
      link: function (scope, element, attrs, ngModelCtrl) {
        jQuery(function () {
          var startdate = jQuery('#book_start_date').val();
          var frm = new Date(startdate);
          element.datepicker({
            dateFormat: 'mm/dd/yy',
            minDate: frm,
            onSelect: function (date) {
              scope.$apply(function () {
                ngModelCtrl.$setViewValue(date);
              });
              scope.updatePrice();
            },
            beforeShowDay: function (date) {
              return scope.showDays({date: date});
            }
          });
        });
      }
    }
  });

  app.directive('sliderange', function ($rootScope) {
    return {
      restrict: 'A',
      scope: {
        showAvailability: '&',
        minPrice: '=',
        maxPrice: '='
      },
      require: 'ngModel',
      link: function (scope, element, attrs, ngModelCtrl) {
        jQuery(function () {
          element.slider({
            range: true,
            min: scope.minPrice,
            max: scope.maxPrice,
            step: 10,
            values: [scope.minPrice, scope.maxPrice],
            slide: function (event, ui) {
              jQuery("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
            },
            change: function (event, ui) {
              scope.showAvailability({minPrice: ui.values[0], maxPrice: ui.values[1]});
              scope.$apply();
            }
          });
          jQuery('#amount').val('$' + scope.minPrice + ' - $' + scope.maxPrice);
        });
      }
    }
  });

  app.directive('modalcheckin', function ($rootScope) {
    return {
      restrict: 'A',
      scope: {
        showDays: '&',
        updatePrice: '&'
      },
      require: 'ngModel',
      link: function (scope, element, attrs, ngModelCtrl) {
        jQuery(function () {

          element.datepicker({
            dateFormat: 'mm/dd/yy',
            minDate: "+1d",
            onSelect: function (date) {
              ngModelCtrl.$setViewValue(date);
              scope.$apply();
              var frm = new Date(date);
              nts = 1;
              if (!isNaN(jQuery('#modal_end_date').attr('data-min-stay'))) {
                nts = jQuery('#modal_end_date').attr('data-min-stay');
              }

              var the_year = frm.getFullYear();
              if (the_year < 2000) the_year = 2000 + the_year % 100;
              var to = new Date(the_year, frm.getMonth(), parseInt(frm.getDate()) + parseInt(nts));

              jQuery('#modal_end_date').datepicker('option', 'minDate', to);

              scope.updatePrice();
            },
            beforeShowDay: function (date) {
              return scope.showDays({date: date});
            }
          });
        });
      }
    }
  });

  app.directive('modalcheckout', function ($rootScope) {
    return {
      restrict: 'A',
      scope: {
        showDays: '&',
        updatePrice: '&'
      },
      require: 'ngModel',
      link: function (scope, element, attrs, ngModelCtrl) {
        jQuery(function () {


          element.datepicker({
            dateFormat: 'mm/dd/yy',
            minDate: "+1d",
            onSelect: function (date) {
              ngModelCtrl.$setViewValue(date);
              scope.$apply();
              // var frm = new Date(date);
              // nts = 1;
              // if (!isNaN(jQuery('#book_start_date').attr('data-min-stay'))) {
              //   nts = jQuery('#book_start_date').attr('data-min-stay');
              // }
              // var the_year = frm.getFullYear();
              // if (the_year < 2000) the_year = 2000 + the_year % 100;
              // var to = new Date(the_year, frm.getMonth(), parseInt(frm.getDate()) + parseInt(nts));
              // jQuery('#book_end_date').datepicker('option', 'minDate', to);
              // scope.updateCheckout({date: to});
              scope.updatePrice();
            },
            beforeShowDay: function (date) {
              return scope.showDays({date: date});
            }
          });
        });
      }
    }
  });

  app.directive('errSrc', function () {
    return {
      link: function (scope, element, attrs) {
        scope.$watch(function () {
          return attrs['ngSrc'];
        }, function (value) {
          if (!value) {
            element.attr('src', attrs.errSrc);
          }
        });

        element.bind('error', function () {
          if (attrs.src != attrs.errSrc) {
            attrs.$set('src', attrs.errSrc);
          }
        });
      }
    }
  });

  app.directive('starRating', function () {
    return {
      restrict: 'A',
      template: '<ul class="rating">' +
        '<li ng-repeat="star in stars" ng-class="star">' +
        '<i ng-if="star.filled" class="fa {[star.filled]}"></i>' +
        '</li></ul>',
      scope: {
        ratingValue: '=',
        max: '='
      },
      link: function (scope, elem, attrs) {
        scope.stars = [];
        for (var i = 0; i < scope.max; i++) {

          var star = "fa-star-o";
          if(i < scope.ratingValue){
            var modu = scope.ratingValue % 1;
            if(i+1 < scope.ratingValue){
              star = "fa-star";
            }else{
              if(modu != 0){
                if(modu <= 0.5){
                  star = "fa-star-o";
                }else{
                  star = "fa-star-half-o";
                }

              }else{
                star = "fa-star";
              }
            }

          }

          scope.stars.push({
            filled: star
          });
        }
      }
    }
  });

  app.controller('PropertyController', ['$scope', '$rootScope', '$sce', '$http', '$window', '$filter', 'Alert', 'rpapi', 'rpuri', '$cookies',
    function ($scope, $rootScope, $sce, $http, $window, $filter, Alert, rpapi, rpuri, $cookies) {
      $rootScope.properties = {};
      $rootScope.propList = {};
      $rootScope.rates = [];
      $rootScope.amenities = [];
      $rootScope.groups = [];
      $scope.loading = true;
      $scope.foundUnits = true;
      $scope.minPrice = 0;
      $scope.maxPrice = 500;
      $scope.maxOccupants = 0;
      $scope.autoZoom = 0;
      $scope.bedroomsNumber = '';
      $scope.neighborhood = '';
      $scope.viewname = '';
      $scope.locationAreaId = '';
      $scope.mapEnabled = false;
      $scope.showMoreButton = true;
      $scope.startDate = $filter('date')(rpuri.getQueryStringParam('sd'), 'MM/dd/yyyy');
      $scope.endDate = $filter('date')(rpuri.getQueryStringParam('ed'), 'MM/dd/yyyy');
      $scope.occupants = rpuri.getQueryStringParam('oc');
      $scope.plusLogic = 0;
      $scope.isFitBounds = false;
      $scope.searchNumber = 0;
      $scope.showDays = true;
      $scope.modal_total_reservation = 0;
      $scope.total_pages = 0;
      $scope.total_units = 0;
      $scope.daysDiff = 0;
      $scope.method ='';
      $scope.wishlist = [];
      
      var map;
      var markerList = {};
      var arrMarkers = [];
      var infowindow;
      var marker;
      var bounds;

      $scope.isArray = angular.isArray;

      $scope.initializeData = function () {
        $scope.initializeMap();
      };

      // will return list of properties.  pass type to determine results
      $scope.initializeMap = function () {
        $scope.mapSearchEnabled = true;
        $scope.mapEnabled = true;

        $scope.$on('mapInitialized', function (evt, evtMap) {
          map = evtMap;
          bounds = map.getBounds();
        });
      };

      $scope.toggleMapSearch = function(){
        if($scope.mapSearchEnabled == false){
          $scope.mapSearchEnabled = true;
        }else{
          $scope.mapSearchEnabled = false;
        }
      }

      $scope.goToProperty = function(seo_page_name,sd,ed,adults,children,pets){

        var url = $rootScope.propertyUrl + seo_page_name;
        if( "1" == $rootScope.useHTML ) url = url + '.html';
        var query_string = '';

        if(sd != undefined && sd != '')
          query_string += 'sd=' + encodeURIComponent(sd)+'&';

        if(ed != undefined && ed != '')
          query_string += 'ed=' + encodeURIComponent(ed)+'&';

        if(adults != undefined && adults != '')
          query_string += 'oc=' + encodeURIComponent(adults)+'&';

        if(children != undefined && children != '')
          query_string += 'ch=' + encodeURIComponent(children)+'&';

        if(pets != undefined && pets != '')
          query_string += 'pets=' + encodeURIComponent(pets)+'&';

        if(query_string != '')
          url += '?' + query_string.replace(/&+$/,'');

        return encodeURI(url);
      }

      $scope.checkSorting = function(){

        if($scope.sortBy == 'max_occupants'){
          $scope.sort = true;
        }
        if($scope.sortBy == 'min_occupants'){
          $scope.sort = false;
        }
        if($scope.sortBy == 'price'){
          $scope.sort = true;
        }
        if($scope.sortBy == 'price_low'){
          $scope.sort = false;
        }
        if($scope.sortBy == 'pets'){
          $scope.sort = true;
        }
        if($scope.sortBy == 'name'){
          $scope.sort = false;
        }
        if($scope.sortBy == 'bedrooms_number'){
          $scope.sort = true;
        }
        if($scope.sortBy == 'min_bedrooms_number'){
          $scope.sort = false;
        }
      }

      $scope.customSorting = function(property){

        if($scope.sortBy == 'max_occupants' || $scope.sortBy == 'min_occupants'){
          return property.max_occupants;
        }else if($scope.sortBy == 'bedrooms_number' || $scope.sortBy == 'min_bedrooms_number'){
          return property.bedrooms_number;
        }else if($scope.sortBy == 'bathrooms_number'){
          return property.bathrooms_number;
        }else if($scope.sortBy == 'name'){
          return property.location_name;
        }else if($scope.sortBy == 'area'){
          return property.square_foots;
        }else if($scope.sortBy == 'view'){
          return property.view_name;
        }else if($scope.sortBy == 'price_low' || $scope.sortBy == 'price'){
          if($scope.method != 'GetPropertyAvailabilityWithRatesWordPress'){
            return property.price_data.daily;
          }else{
            return property.total;
          }
        }else if($scope.sortBy == 'pets'){
          return property.max_pets;
        }else{
          return [];
        }
      }

      $scope.getUnitName = function(unit){
        if(unit.name == '' || unit.name == 'Home'){
          return unit.location_name;
        }else{
          return unit.name;
        }
      }

      $scope.getUnitPrice = function(unit){
        
      }

      $scope.calculateMarkup = function(strPrice){

        var price = strPrice;
        if(typeof strPrice == 'string'){
          price = parseFloat(strPrice.replace('$','').replace(',',''));
        }
        
        if($rootScope.rateMarkup > 0){
          var pct = 1 + (parseFloat($rootScope.rateMarkup) / 100);                  
          price = price * pct;                
        }
        
        return price;
      }

      $scope.disableMapSearch = function(){
        $scope.mapSearchEnabled = false;
        $scope.availabilitySearch($scope.search);
      }

      $scope.clearProperties = function () {
        $rootScope.propList = [];
        $rootScope.properties = [];
      };

      $scope.isEmptyString = function(obj){
        return !(obj != undefined && obj != '');
      }

      $scope.isEmptyObject = function (obj) {
        return angular.equals({}, obj);
      };

      $scope.requestPropertyList = function (method, params, size, page) {
        
        var wishlist = $cookies.getObject('streamline-favorites');

        if(wishlist){
          $scope.wishlist = wishlist;          
        }

        size = !size ? $rootScope.searchSettings.propertyPagination : size;
        page = !page ? 1 : page;

        if (params) {
          params.page_results_number = size;
          params.page_number = page;
        } else {
          params = {
            'page_results_number': size,
            'page_number': page
          }
        }

        if($rootScope.roomTypeLogic == 1)
          params.use_room_type_logic = parseInt($rootScope.roomTypeLogic);

        if($rootScope.searchSettings.additionalVariables == 1)
          params.additional_variables = 1;

        if($rootScope.searchSettings.extraCharges == 1)
          params.extra_charges = 1;

        params.sort_by = $rootScope.searchSettings.defaultFilter;

        $scope.checkSorting();
      
        if($rootScope.searchSettings.locationAreas != '')
          params.location_areas_id_filter = $rootScope.searchSettings.locationAreas;

        if($rootScope.searchSettings.locationId > 0)
          params.location_id = $rootScope.searchSettings.locationId;

        if($rootScope.searchSettings.resortAreaId > 0)
          params.resort_area_id = $rootScope.searchSettings.resortAreaId;

        if(parseInt($rootScope.searchSettings.skipAmenities) === 1){
          params.use_description = 'no';
          params.use_amenities = 'no';
        }

        if ($rootScope.searchSettings.propertyDeleteUnits)
          params.skip_units = $rootScope.searchSettings.propertyDeleteUnits;

        if ($scope.search) {

          if($scope.search.sort_by != undefined && $scope.search_sort_by != '')
            params.sort_by = $scope.search.sort_by;

          if (method == 'GetPropertyAvailabilitySimple' || method == 'GetPropertyAvailabilityWithRatesWordPress'/*$scope.search.start_date != undefined && $scope.search.start_date != ''*/) {
            params.startdate = $scope.search.start_date;
            params.enddate = $scope.search.end_date;

            var oneDay = 24*60*60*1000;
            var checkin = new Date($scope.search.start_date);
            var checkout = new Date($scope.search.end_date);

            var diffDays = Math.round(Math.abs((checkin.getTime() - checkout.getTime())/(oneDay)));
            $scope.daysDiff = diffDays;

            if (!$scope.isEmptyString($scope.search.occupants) && $scope.search.occupants > 0) {
              params.occupants = $scope.search.occupants;
            }

            if (!$scope.isEmptyString($scope.search.occupants_small) && $scope.search.occupants_small > 0) {
              params.occupants_small = $scope.search.occupants_small;
            }

            if (!$scope.isEmptyString($scope.search.pets) && $scope.search.pets > 0) {
              params.pets = $scope.search.pets;
            }
          } else {
            var min_occupants = 0;
            if (!$scope.isEmptyString($scope.search.occupants) && $scope.search.occupants > 0) {
              min_occupants += $scope.search.occupants;
            }

            if (!$scope.isEmptyString($scope.search.occupants_small) && $scope.search.occupants_small > 0) {
              min_occupants += $scope.search.occupants_small;
            }

            if (min_occupants > 0) {
              params.min_occupants = min_occupants;
            }

            if (!$scope.isEmptyString($scope.search.pets) && $scope.search.pets > 0) {
              params.min_pets = $scope.search.pets;
            }
          }

          if ((!$scope.isEmptyString($scope.search.num_bedrooms) && $scope.search.num_bedrooms >= 0)) {            
            if($scope.plusLogic === 1){
              params.min_bedrooms_number = parseInt($scope.search.num_bedrooms);              
            }else{
              params.bedrooms_number = parseInt($scope.search.num_bedrooms);              
            }
          }
          
          if (!$scope.isEmptyString($scope.search.area_id) && $scope.search.area_id > 0) {
            params.location_area_id = $scope.search.area_id;
          }

          if (!$scope.isEmptyString($scope.search.lodging_type_id) && $scope.search.lodging_type_id > 0) {
            params.lodging_type_id = $scope.search.lodging_type_id;
          }

          if (!$scope.isEmptyString($scope.search.property_type_id ) && $scope.search.property_type_id > 0) {
            params.home_type_id = $scope.search.property_type_id;
          }

          if (!$scope.isEmptyString($scope.search.location_resort_id) && $scope.search.location_resort_id > 0) {
            params.location_resort_id = $scope.search.location_resort_id;
          }

          if (!$scope.isEmptyString($scope.search.resort_area_id) && $scope.search.resort_area_id > 0) {
            params.resort_area_id = $scope.search.resort_area_id;
          }

          if (!$scope.isEmptyString($scope.search.neighborhood_id) && $scope.search.neighborhood_id > 0) {
            params.neighborhood_area_id = $scope.search.neighborhood_id;
          }

          if (!$scope.isEmptyString($scope.search.view_name) && $scope.search.view_name != '') {
            params.view_name = $scope.search.view_name;
          }

          if (!$scope.isEmptyString($scope.search.unit_name) && $scope.search.unit_name != '') {
            params.unit_name = $scope.search.unit_name;
          }

          if (!$scope.isEmptyString($scope.search.group_id) && $scope.search.group_id > 0) {
            params.condo_type_group_id = $scope.search.group_id;
          }

          if (!$scope.isEmptyString($scope.search.amenities) && $scope.search.amenities != '') {
            params.amenities_filter = $scope.search.amenities;
          }
        }
      
        if (!$rootScope.properties.length) {
          $scope.loading = true;
        }
        
        if(method != 'GetPropertyListWordPress')
          method = $rootScope.searchSettings.searchMethod;

        if(diffDays > $rootScope.searchSettings.maxSearchDays)
          method = 'GetPropertyAvailabilitySimple';

        $scope.method = method;
        var autozoom = ($scope.autoZoom == 1) ? true : false;
        
        rpapi.getWithParams(method, params).success(function (obj) {
          var tempProperties;          
          
          // if($scope.total_pages == 0)
          //   $scope.total_pages = obj.data.available_properties.pagination.total_pages;

          if($scope.total_units == 0)
            $scope.total_units = obj.data.available_properties.pagination.total_units;

          if (obj.data.available_properties.pagination.total_units == 1) {

            var propArray = [];
            if (method == "GetPropertyAvailabilityWithRatesWordPress") {
              propArray.push(obj.data.available_properties.property);
            } else {
              propArray.push(obj.data.property);
            }

            $scope.loadMarkers(propArray, autozoom);

            if($rootScope.properties.length > 0){
              $rootScope.properties = $rootScope.properties.concat(propArray);
            }else{
              $rootScope.properties = propArray;
            }

          } else {

            if (method == "GetPropertyAvailabilityWithRatesWordPress") {
              tempProperties = obj.data.available_properties.property;
            } else {
              tempProperties = obj.data.property;
            }

            $scope.loadMarkers(tempProperties, autozoom);

            if ($rootScope.properties.length > 0) {
              $rootScope.properties = $rootScope.properties.concat(tempProperties);
            } else {
              $rootScope.properties = tempProperties;
            }
          }

          if (obj.data.available_properties.pagination.total_units == 0) {
            $rootScope.propertiesObj = [];
          }else{
            $rootScope.propertiesObj = Object.keys($rootScope.properties).map(function(key) {
              return $rootScope.properties[key];
            });
          }

          if(params.sort_by == 'random'){
            angular.forEach(tempProperties, function (property) {
              if(!params.skip_units){
                params.skip_units = property.id;
              }else{
                params.skip_units += ','+property.id;
              }
            });
          }

          $rootScope.propList = $rootScope.properties;

          if (obj.data.available_properties.pagination.total_pages > page && $scope.searchNumber == 0) {
            page++;
            if(params.sort_by == 'random'){
              // if we are using random sorting, we always need to call page 1
              // but we send skipped units so we get different results
              $scope.requestPropertyList(method, params, size, 1);
            }else{
              $scope.requestPropertyList(method, params, size, page);
            }

          }

          $scope.loading = false;

        });
      };

      $scope.loadMarkers = function(properties, setBounds){
        if($scope.mapEnabled){

          angular.forEach(properties, function (property) {

            if (!isNaN(property.location_latitude) && !isNaN(property.location_longitude)) {
              var marker = {
                id: property.id,
                name: property.location_name,
                latitude: property.location_latitude,
                longitude: property.location_longitude,
                image: property.default_thumbnail_path,
                beds: property.bedrooms_number,
                baths: property.bathrooms_number,
                guests: property.max_occupants,
                seo_page_name: property.seo_page_name
              };

              if($scope.method == 'GetPropertyAvailabilityWithRatesWordPress'){
                marker['price'] = property.total;
              }else{
                marker['price'] = property.price_data;
              }

              // this looks nasty, we need to find a better way of waiting for map
              if (map != undefined) {
                var latLong = new google.maps.LatLng (property.location_latitude,property.location_longitude);
                bounds.extend (latLong);
                $scope.loadMarker(marker);
              } else {

                setTimeout(function () {
                  if (map != undefined) {
                    var latLong = new google.maps.LatLng (property.location_latitude,property.location_longitude);
                    bounds.extend (latLong);
                    $scope.loadMarker(marker);
                  }
                }, 2000);
              }
            }
          });

          if (map != undefined && setBounds) {
            $scope.isFitBounds = true;
            map.fitBounds (bounds);
            map.setCenter(bounds.getCenter());
            $scope.isFitBounds = false;
          }
        }
      }

      $scope.loadMore = function(){

        $scope.limit += $rootScope.searchSettings.propertyPagination;

        if($scope.limit > $scope.results.length){
          //$scope.showMoreButton = false;
        }
      }
      $scope.prepareMarker = function (property, marker) {
        var ne = map.getBounds().getNorthEast();
        var sw = map.getBounds().getSouthWest();

        if (property.location_latitude >= sw.lat() && property.location_latitude <= ne.lat() && property.location_longitude >= sw.lng() && property.location_longitude <= ne.lng()) {
          $scope.loadMarker(marker);
        }
      }

      $scope.getPropertyInfo = function () {
        rpapi.getWithParams('GetPropertyInfo', {'unit_id': $scope.propertyId}).success(function (obj) {
          $scope.property = obj.data;
          $scope.latitude = obj.data.latitude;
          $scope.longitude = obj.data.longitude;

          $scope.$on('mapInitialized', function (evt, evtMap) {
            map = evtMap;
            var myLatlng = {lat: obj.data.latitude, lng: obj.data.longitude};
            map.setCenter(myLatlng);
          });
          return $scope.property;
        });
      };

      $scope.getPropertyImages = function (unit_id) {
        rpapi.getWithParams('GetPropertyGalleryImages', {'unit_id': unit_id}).success(function (obj) {
          $scope.images = obj.data.image;
        });
      };

      $scope.setAmenityFilter = function(amenityId){

        run_waitMe('.listings_wrapper_box', 'bounce', 'Updating Results');
         setTimeout(function(){
            hide_waitMe('.listings_wrapper_box');
          }, 500);

        if($scope.amenity[amenityId]){
          $rootScope.amenities.push(amenityId);
        }else{
          var index = $rootScope.amenities.indexOf(amenityId);
          if(index > -1){
            $rootScope.amenities.splice(index,1);
          }
        }


      };

      $scope.setAmenityFilterOr = function(amenityId,group){

        run_waitMe('.listings_wrapper_box', 'bounce', 'Updating Results');
         setTimeout(function(){
            hide_waitMe('.listings_wrapper_box');
          }, 500);

          var amenityFound = false;
          if(!$rootScope.groups.length > 0){

            var gitem = {
              name: group,
              amenities: [amenityId]
            };

           $rootScope.groups.push(gitem);

         }else{
            var removeFromArray = false;
            var indexToRemove = 0;
            angular.forEach($rootScope.groups, function (amenity,key) {

                if($scope.amenityOr[amenityId]){
                  if(amenity.name == group){
                    amenityFound = true;
                    amenity.amenities.push(amenityId);
                  }
                }else{
                  if(amenity.name == group){
                    amenityFound = true;
                    var index = amenity.amenities.indexOf(amenityId);
                    if(index > -1){
                      amenity.amenities.splice(index,1);
                    }

                    if(amenity.amenities.length == 0){
                        removeFromArray = true;
                        indexToRemove = key;
                    }
                  }
                }
            });

            if(!amenityFound){
                var gitem = {
                  name: group,
                  amenities: [amenityId]
                };

                $rootScope.groups.push(gitem);
            }

            if(removeFromArray){
              $rootScope.groups.splice(indexToRemove,1);
            }
         }
      };

      $scope.amenityFilter = function(item){

        var totalAmenities = $rootScope.amenities.length;
        var i = 0;
        angular.forEach($rootScope.amenities, function (aId) {

            angular.forEach(item.unit_amenities.amenity, function(uaId){

              if (aId == uaId.amenity_id){
                i++;
              }
            });
        });

        if(totalAmenities == i){
          return true;
        }else{
          return false;
        }

      }

      $scope.amenityFilterOr = function(item){

        var result = true;

        if($rootScope.groups.length > 0){
            result = false;
        }

        var totalGroups = $rootScope.groups.length;
        var groupsFound = 0;
        angular.forEach($rootScope.groups, function (group) {
            var keepGoing = true;
            angular.forEach(group.amenities, function(amenity){
                angular.forEach(item.unit_amenities.amenity, function(ua){
                    if(keepGoing) {
                      if(ua.amenity_id == amenity){
                        groupsFound++;
                        keepGoing = false;
                      }
                    }
                });
            });

        });

        return (totalGroups == groupsFound);

      }

      $scope.getPropertyAmenities = function () {
        rpapi.getWithParams('GetPropertyAmenities', {'unit_id': $scope.propertyId}).success(function (obj) {
          $scope.amenities = obj.data.amenity;
        });
      };

      $scope.getLocations = function () {
        rpapi.get('GetLocationAreasList').success(function (obj) {
          $scope.locations = obj.data.location_area;
        });
      };

      $scope.getPropertyReviews = function () {
        rpapi.getWithParams('GetPropertyFeedbacks', {'unit_id': $scope.propertyId, 'order_by': 'newest_first'}).success(function (obj) {
          if (obj.data.feedbacks.guest_name) {
            var reviewsArray = [];
            reviewsArray.push(obj.data.feedbacks);
            $scope.reviews = reviewsArray;
          } else {
            $scope.reviews = obj.data.feedbacks;
          }
        });
      };

      $scope.getPreReservationPrice = function (booking, res) {
        if (booking.checkin && booking.checkout) {
          run_waitMe('#resortpro-book-unit form', 'bounce', 'Updating Price...');
          Alert.clear();

          var totalOccupants = parseInt(booking.occupants) + parseInt(booking.occupants_small);

          if(parseInt($scope.maxOccupants) > 0 && (parseInt(booking.occupants) + parseInt(booking.occupants_small)) > parseInt($scope.maxOccupants)){
              Alert.add(Alert.errorType, 'You have selected a total of ' + totalOccupants + ' guests which exceeds the maximum occupancy of ' + $scope.maxOccupants + ' of this property. Please adjust your selection.');
              hide_waitMe('#resortpro-book-unit form');
              $scope.isDisabled = true;
              return false;
          }

          rpapi.getWithParams('VerifyPropertyAvailability', {
            'unit_id': booking.unit_id,
            'startdate': booking.checkin,
            'enddate': booking.checkout,
            'occupants': booking.occupants,
            'occupants_small' : booking.occupants_small,
            'pets' : booking.pets,
            'use_room_type_logic' : parseInt($rootScope.roomTypeLogic)
          }).success(function (obj) {
            if (obj.status) {
              $scope.reservation_days = [];
              $scope.total_reservation = 0;
              $scope.first_day_price = 0;
              $scope.rent = 0;
              $scope.taxes = 0;

              var errorMsg = obj.status.description;
              if(obj.status.code == 'E0031' && $rootScope.searchSettings.restrictionMsg != ''){
                errorMsg = $rootScope.searchSettings.restrictionMsg;
              }

              Alert.add(Alert.errorType, errorMsg);
              hide_waitMe('#resortpro-book-unit form');
            } else {

              $scope.isDisabled = false;
              rpapi.getWithParams('GetPreReservationPrice', {
                'unit_id': booking.unit_id,
                'startdate': booking.checkin,
                'enddate': booking.checkout,
                'occupants': booking.occupants,
                'occupants_small' : booking.occupants_small,
                'pets' : booking.pets,
                'coupon_code' : booking.coupon_code
              }).success(function (obj) {
                if (obj.data != undefined) {
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

                  $scope.total_reservation = obj.data.total;
                  $scope.total_fees = total_fees;
                  $scope.total_taxes = total_taxes;
                  $scope.rent = obj.data.price;
                  
                  $scope.subTotal = $scope.calculateMarkup((obj.data.price + obj.data.coupon_discount).toString());
                  var dif = $scope.subTotal - obj.data.coupon_discount - obj.data.price;                            
                  $scope.taxes = obj.data.taxes - dif;
                
                  $scope.coupon_discount = obj.data.coupon_discount;
                  $scope.reservation_days = obj.data.reservation_days;
                  $scope.security_deposits = obj.data.security_deposits;
                  $scope.first_day_price = obj.data.first_day_price;
                  $scope.required_fees = obj.data.required_fees;
                  $scope.taxes_details = obj.data.taxes_details;
                  $scope.due_today = obj.data.due_today;
                  $scope.res = res;

                  if (obj.data.reservation_days.date != undefined) {
                    $scope.days = false;
                  } else {
                    $scope.days = true;
                  }

                  hide_waitMe('#resortpro-book-unit form');
                }
              });
            }
          });
        }
      };

      $scope.availabilitySearch = function (search) {

        run_waitMe('.listings_wrapper_box', 'bounce', 'Updating Results');
        properties = $rootScope.propList;
        size = $rootScope.searchSettings.propertyPagination;
        $scope.limit = size;

        $scope.loading = true;
        params = {
          page_number : 1,
          page_results_number : size
        };
        
        if(search.sort_by){
          params.sort_by = search.sort_by;
        }else{
          params.sort_by = $scope.sortBy;
        }

        params.use_room_type_logic = parseInt($rootScope.roomTypeLogic);        
        params.extra_charges = 1;

        if ($rootScope.searchSettings.propertyDeleteUnits)
          params.skip_units = $rootScope.searchSettings.propertyDeleteUnits;

        if($rootScope.searchSettings.locationAreas != '')
          params.location_areas_id_filter = $rootScope.searchSettings.locationAreas;

        if($rootScope.searchSettings.locationId > 0)
          params.location_id = $rootScope.searchSettings.locationId;

        if($rootScope.searchSettings.resortAreaId > 0)
          params.resort_area_id = $rootScope.searchSettings.resortAreaId;

        if($rootScope.searchSettings.additionalVariables == 1)
          params.additional_variables = 1;

        if($rootScope.searchSettings.extraCharges == 1)
          params.extra_charges = 1;

        if(parseInt($rootScope.searchSettings.skipAmenities) === 1){
          params.use_description = 'no';
          params.use_amenities = 'no';
        }

        if (search) {
          if (search.start_date != '' && search.end_date != '') {
            params.startdate = search.start_date;
            params.enddate = search.end_date;
          }

          if (!$scope.isEmptyString(search.occupants) && search.occupants > 0)
            params.occupants = parseInt(search.occupants);

          if (!$scope.isEmptyString(search.occupants_small) && search.occupants_small > 0)
            params.occupants_small = parseInt(search.occupants_small);

          if (!$scope.isEmptyString(search.pets) && search.pets > 0)
            params.pets = parseInt(search.pets);

          if (!$scope.isEmptyString(search.area) && search.area > 0)
            params.location_area_id = parseInt(search.area);

          if (!$scope.isEmptyString(search.lodging_type) && search.lodging_type > 0)
            params.lodging_type_id = parseInt(search.lodging_type);

          if (!$scope.isEmptyString(search.resort_area_id) && search.resort_area_id)
            params.resort_area_id = parseInt(search.resort_area_id);
                    
          if ((!$scope.isEmptyString(search.num_bedrooms) && search.num_bedrooms >= 0)) {            
            if($scope.plusLogic === 1){
              params.min_bedrooms_number = parseInt(search.num_bedrooms);              
            }else{
              params.bedrooms_number = parseInt(search.num_bedrooms);              
            }
          }

          if (!$scope.isEmptyString(search.location_resort) && search.location_resort > 0)
            params.resort_area_id = parseInt(search.location_resort);

          if (!$scope.isEmptyString(search.location) && search.location)
            params.location_id = parseInt(search.location);

          if(!$scope.isEmptyString(search.neighborhood_id) && search.neighborhood_id > 0)
            params.neighborhood_area_id = parseInt(search.neighborhood_id);

          if (!$scope.isEmptyString(search.viewname))
            params.view_name = search.viewname;

          if(!$scope.isEmptyString(search.bedroom_type) && search.group_type > 0)
            params.condo_type_id = parseInt(search.bedroom_type);

          if(!$scope.isEmptyString(search.group_type) && search.group_type > 0)
            params.condo_type_group_id = parseInt(search.group_type);

          if(!$scope.isEmptyString(search.home_type) && search.home_type > 0)
            params.home_type_id = parseInt(search.home_type);
        }
        
        $scope.amenities = [];
        angular.forEach($scope.selectedAmenities, function (item) {       
          if (item != false) {
            $scope.amenities.push(item);
          }
        });
              
        if ($scope.amenities.length > 0) {
          var amenities = $scope.amenities.join();
          params.amenities_filter = amenities;
        }

        if ($scope.mapSearchEnabled && angular.isNumber($scope.latNE)) {
          params.latNe = $scope.latNE;
          params.longNe = $scope.longNE;
          params.latSw = $scope.latSW;
          params.longSw = $scope.longSW;
        }

        angular.forEach(arrMarkers, function (item, i) {
          item.setMap(null);
        });

        arrMarkers = [];

        $scope.keepSearching = true;
        $scope.searchNumber++;
        
        $scope.total_units = 0;

        $scope.searchProperties(params,$scope.searchNumber,size,1, true);        
      };

      $scope.searchProperties = function(params, searchNumber, size, page, clearUnits){
        
        params.page_number = page;
        params.page_results_number = size;

        if(searchNumber == $scope.searchNumber){

          if (!(params.startdate == '' || params.startdate == undefined) && !(params.enddate == '' || params.enddate == undefined)) {
            method = $rootScope.searchSettings.searchMethod;
            var oneDay = 24*60*60*1000;
            var checkin = new Date(params.startdate);
            var checkout = new Date(params.enddate);

            var diffDays = Math.round(Math.abs((checkin.getTime() - checkout.getTime())/(oneDay)));
            $scope.daysDiff = diffDays;
            if(diffDays > $rootScope.searchSettings.maxSearchDays)
              method = 'GetPropertyAvailabilitySimple';
          }else{
            method = 'GetPropertyAvailabilitySimple';
          }

          $scope.method = method;

          rpapi.getWithParams(method, params).success(function (obj) {

            if(clearUnits){
              $rootScope.propertiesObj = [];
              $rootScope.properties = [];
              hide_waitMe('.listings_wrapper_box');
            }

            if(obj.data.available_properties.pagination.total_units > 0){
              
              if($scope.total_units == 0)
                $scope.total_units = obj.data.available_properties.pagination.total_units;

              $scope.foundUnits = true;
              var tempProperties = [];

              if (obj.data.available_properties.pagination.total_units == 1) {
                if(method == 'GetPropertyAvailabilitySimple'){
                  tempProperties.push(obj.data.property);
                }else{
                  tempProperties.push(obj.data.available_properties.property);
                }

              }else{
                if(method == 'GetPropertyAvailabilitySimple'){
                  tempProperties = obj.data.property;
                }else{
                  tempProperties = obj.data.available_properties.property;
                }
              }

              if ($rootScope.properties.length > 0) {
                $rootScope.properties = $rootScope.properties.concat(tempProperties);
              } else {
                $rootScope.properties = tempProperties;
              }

              $rootScope.propertiesObj = Object.keys($rootScope.properties).map(function(key) {
                return $rootScope.properties[key];
              });

              if(params.sort_by == 'random'){
                angular.forEach(tempProperties, function (property) {
                  if(!params.skip_units){
                    params.skip_units = property.id;
                  }else{
                    params.skip_units += ','+property.id;
                  }
                });
              }

              $scope.loadMarkers(tempProperties, false);

              if (obj.data.available_properties.pagination.total_pages > page) {
                page++;
                if(params.sort_by == 'random'){
                  // if we are using random sorting, we always need to call page 1
                  // but we send skipped units so we get different results
                  $scope.searchProperties(params, searchNumber, size, 1, false);
                }else{
                  $scope.searchProperties(params, searchNumber, size, page, false);
                }
              }
            }
          });

          $scope.loading = false;
        }
      }

      $scope.shareWithFriends = function(){

        if($scope.frmShare.$valid){
          var link = $scope.goToProperty($scope.share.seo_page_name,$scope.share.start_date,$scope.share.end_date,$scope.share.occupants,$scope.share.occupants_small,$scope.share.pets);

          var message = $scope.share.message;

          var data = {
            action : 'streamlinecore-share-with-friends',
            fnames : $scope.share.fnames,
            femails : $scope.share.femails,
            name: $scope.share.name,
            email: $scope.share.email,
            msg: message,
            slug: $scope.share.seo_page_name,
            link: link,
            nonce: $scope.share.nonce
          }
          $http({
            method: 'POST',
            headers: {
             'Content-type': 'application/json'
            },
            url: streamlinecoreConfig.ajaxUrl,
            params: data
          }).then(function successCallback(response) {

              if(response.data.success){
                Alert.add(Alert.successType, response.data.data.message);

                setTimeout(function () {
                      jQuery('#modalShare').modal('hide');
                    }, 3000);
              }else{
                Alert.add(Alert.errorType, response.data.data.message);
              }
            }, function errorCallback(response) {
              Alert.add(Alert.errorType, 'Cant send email');
            });
        }
        return false;
      };

      $scope.filterByPrice = function (minPrice, maxPrice) {
        $scope.priceRangeEnabled = true;
        $scope.minPrice = minPrice;
        $scope.maxPrice = maxPrice;
      };

      $scope.bedroomFilter = function (item) {
        var result = true;
        if($scope.bedroomsNumber.indexOf('-') !== -1){
             var beds = $scope.bedroomsNumber.split('-');
             if(item.bedrooms_number >= beds[0] && item.bedrooms_number <= beds[1]){
                return true;
             }else{
                return false;
             }
        }else{
            if (parseInt($scope.bedroomsNumber) > 0) {

                if (item.bedrooms_number == $scope.bedroomsNumber) {
                    result = true;
                } else {
                    result = false;
                }
            }
        }

        return result;
      };

      $scope.locationFilter = function (item) {
        var result = true;

        if ($scope.locationAreaId > 0) {
          if (item.location_area_id == $scope.locationAreaId) {
            result = true;
          } else {
            result = false;
          }
        }

        return result;
      };

      $scope.neighborhoodFilter = function (item) {
        var result = true;
        if ($scope.neighborhood != '') {

          if (item.neighborhood_name == $scope.neighborhood) {
            result = true;
          } else {
            result = false;
          }
        }

        return result;
      };

      $scope.viewNameFilter = function (item) {
        var result = true;
        if ($scope.viewname != '') {

          if (item.view_name == $scope.viewname) {
            result = true;
          } else {
            result = false;
          }
        }

        return result;
      };

      $scope.priceRange = function (item) {
        $scope.amenities = [];

        angular.forEach($scope.selected, function (amenity) {
          if (amenity != false) {
            $scope.amenities.push(item);
          }
        });

        var result = true;

        if ($scope.priceRangeEnabled) {
          if (item.price_data.daily >= $scope.minPrice && item.price_data.daily <= $scope.maxPrice) {
            result = true;
          } else {
            result = false;
          }
        }

        return result;
      }

      $scope.resetSearch = function () {
        $scope.properties = $scope.beforeSearchProperties;
      };

      $scope.availableProperties = function (id) {
        if ($scope.results.length >= 0) {
          if ($.inArray(id, $scope.results) >= 0) {
            return true;
          } else {
            return false;
          }
        } else {
          return true;
        }
      };

      $scope.getRoomDetails = function (unit_id) {
        $scope.room_details = [];
        rpapi.getWithParams('GetPropertyRoomsDetailsRawData', {
          'unit_id': unit_id
        })
        .success(function (obj) {
          if(obj.data.roomsdetails){
            if(obj.data.roomsdetails.name){
              var results = [];
              results.push(obj.data.roomsdetails);
              $scope.room_details = results;
            }else{
              $scope.room_details = obj.data.roomsdetails;
            }
          }
        });
      };

      $scope.getRatesDetails = function (unit_id) {
        rpapi.getWithParams('GetPropertyRatesRawData', {
          'unit_id': unit_id
        })
        .success(function (obj) {
          if(obj.data.rates.period_begin){
            var results = [];
            results.push(obj.data.rates);
            $scope.rates_details = results;
          }else{
            $scope.rates_details = obj.data.rates;
          }
        });
      };

      $scope.getPropertyRatesAndStay = function (unit_id) {
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();

        if(dd<10) {
          dd='0'+dd
        }

        if(mm<10) {
          mm='0'+mm
        }

        today = mm+'/'+dd+'/'+yyyy;

        var d = new Date();
        d.setFullYear(yyyy+1);
        dd = d.getDate();
        mm = d.getMonth()+1;
        yyyy = d.getFullYear();

        var next_year = mm+'/'+dd+'/'+yyyy;


        var params = {
          'unit_id': unit_id,
          'startdate': today,
          'enddate': next_year          
        };

        if($rootScope.roomTypeLogic == '1')
          params['use_room_type_logic'] = 'yes';

        rpapi.getWithParams('GetPropertyRates', params).success(function (obj) {
          if(obj.data.season){
            $rootScope.rates.push(obj.data);
          }else{
            angular.forEach(obj.data, function (rate, index) {
              $rootScope.rates.push(rate);
            });
          }
          jQuery('.availability-calendar').datepicker('refresh');
          add_tooltip();
        });
      };

      $scope.renderCalendar = function (date, checkout) {

        var title = '';
        var booked = false;
        var strClass = 'available';

        angular.forEach($rootScope.rates, function (rateObj, index) {
          var arrDate = rateObj.date.split("-");
          var dateFormat = arrDate[1]+'/'+arrDate[2]+'/'+arrDate[0];
          var rDate = new Date(dateFormat);
          var rate = $filter('currency')($scope.calculateMarkup(rateObj.rate), undefined, 2);
          if (rDate.toDateString() == date.toDateString()) {
            
            title = 'Season: ' + rateObj.season + ', Rate: ' + rate + ', Min. Stay: ' + rateObj.minStay;
            if (rateObj.booked == '1') {
              if(rateObj.changeOver == 'I' && checkout){
                booked = false;
               } else {
                booked = true;
                strClass = 'booked';
              }
            }
          }
        });

        return [!booked, strClass, title];
      }

      $scope.myShowDaysFunction = function (date) {

        var title = '';
        var booked = false;
        var strClass = 'available';

        angular.forEach($rootScope.rates, function (rateObj, index) {
          var arrDate = rateObj.date.split("-");
          var dateFormat = arrDate[1]+'/'+arrDate[2]+'/'+arrDate[0];
          var rDate = new Date(dateFormat);
          var rate = $filter('currency')(rateObj.rate, undefined, 0);
          if (rDate.toDateString() == date.toDateString()) {
            rate = $scope.calculateMarkup(rate);
            title = 'Season: ' + rateObj.season + ', Rate: ' + rate + ', Min. Stay: ' + rateObj.minStay;
            if (rateObj.booked == '1') {
              booked = true;
              strClass = 'booked';
            }
          }
        });

        return [!booked, strClass, title];
      }

      $scope.myShowDaysFunctionCheckout = function (date) {
        var title = '';
        var booked = false;
        var strClass = 'available';

        angular.forEach($rootScope.rates, function (rateObj, index) {
          var arrDate = rateObj.date.split("-");
          var dateFormat = arrDate[1]+'/'+arrDate[2]+'/'+arrDate[0];
          var rDate = new Date(dateFormat);

          if (rDate.toDateString() == date.toDateString()) {
            title = 'Season: ' + rateObj.season + ', Rate: ' + rateObj.rate + ', Min. Stay: ' + rateObj.minStay;
            if (rateObj.booked == '1') {
              if(rateObj.changeOver == 'I'){
                booked = false;
               } else {
                booked = true;
                strClass = 'booked';
              }
            }
          }
        });

        return [!booked, strClass, title];
      }

      $scope.dragEnd = function (search) {
        if ($scope.mapSearchEnabled && !$scope.isFitBounds) {
          var ne = map.getBounds().getNorthEast();
          var sw = map.getBounds().getSouthWest();

          $scope.latNE = ne.lat();
          $scope.longNE = ne.lng();

          $scope.latSW = sw.lat();
          $scope.longSW = sw.lng();

          $scope.availabilitySearch($scope.search);
        }
      };

      $scope.isSimplePricing = function(property){
        return (!property.price) ? true : false;
      }

      $scope.getTotalPrice = function(property, decimals){
        var price = 'N/A';
        if($rootScope.searchSettings.searchMethod == 'GetPropertyAvailabilityWithRatesWordPress'){
          if($rootScope.searchSettings.priceDisplay == 'price' && property.price > 0){             
            price = $filter('currency')(property.price,undefined,decimals);             
          }else{
            price = $filter('currency')(property.total,undefined,decimals); 
          }
        }

        return price;
      }

      $scope.getTotalAppend = function(property){
        if($rootScope.searchSettings.priceDisplay == 'price' && property.total && property.total > 0){
          return 'excluding taxes & fees';
        }else{
          return 'including taxes & fees';
        }
      }

      $scope.getSimplePrice = function(price_data, decimals){
        var priceText = 'N/A';
        
        if($rootScope.searchSettings.useDailyPricing == 1 && price_data.daily && price_data.daily > 0){
          priceText = $filter('currency')(price_data.daily,undefined,decimals);  
        }else if($rootScope.searchSettings.useWeeklyPricing == 1 && price_data.weekly && price_data.weekly > 0){
          priceText = $filter('currency')(price_data.weekly,undefined,decimals);  
        }else if($rootScope.searchSettings.useMonthlyPricing == 1 && price_data.monthly && price_data.monthly > 0){
          priceText = $filter('currency')(price_data.monthly,undefined,decimals);  
        }
      
        return priceText;
      }

      $scope.getPrependTex = function(price_data){
        var prependText = '';
        
        if($rootScope.searchSettings.useDailyPricing == 1 && price_data.daily && price_data.daily > 0){
          prependText = $rootScope.searchSettings.dailyPrepend;
        }else if($rootScope.searchSettings.useWeeklyPricing == 1 && price_data.weekly && price_data.weekly > 0){
          prependText = $rootScope.searchSettings.weeklyPrepend;
        }else if($rootScope.searchSettings.useMonthlyPricing == 1 && price_data.monthly && price_data.monthly > 0){
          prependText = $rootScope.searchSettings.monthlyPrepend;
        }

        return prependText;
      }

      $scope.getAppendTex = function(price_data){
        var appendText = '';

        if($rootScope.searchSettings.useDailyPricing == 1 && price_data.daily && price_data.daily > 0){
          appendText = $rootScope.searchSettings.dailyAppend;
        }else if($rootScope.searchSettings.useWeeklyPricing == 1 && price_data.weekly && price_data.weekly > 0){
          appendText = $rootScope.searchSettings.weeklyAppend;
        }else if($rootScope.searchSettings.useMonthlyPricing == 1 && price_data.monthly && price_data.monthly > 0){
          appendText = $rootScope.searchSettings.monthlyAppend;
        }

        return appendText;
      }

      $scope.loadMarker = function (markerData) {
        
        var myLatlng = new google.maps.LatLng(markerData.latitude, markerData.longitude);

        var price = '';
        
        if($scope.method == 'GetPropertyAvailabilityWithRatesWordPress'){
          price = $filter('currency')(markerData.price,undefined,0);
        }else{
          var price = 'N/A';
          if($rootScope.searchSettings.useDailyPricing == 1 && markerData.price.daily && markerData.price.daily > 0){
            price = $filter('currency')(markerData.price.daily,undefined,0);  
          }else if($rootScope.searchSettings.useWeeklyPricing == 1 && markerData.price.weekly && markerData.price.weekly > 0){
            price = $filter('currency')(markerData.price.weekly, undefined,0);
          }else if($rootScope.searchSettings.useMonthlyPricing == 1 && markerData.price.monthly && markerData.price.monthly > 0){
            price = $filter('currency')(markerData.price.monthly, undefined,0);
          }        
        }

        var marker = new RichMarker({
          id: markerData.id,
          map: map,
          title: markerData.name,
          position: myLatlng,
          shadow: 'none',
          content: '<div class="arrow_box">' + price + '</div>'
        });

        infowindow = new google.maps.InfoWindow();
        //var url = $rootScope.propertyUrl;
        var start_date = '';
        var end_date = '';
        var occupants = '';
        var occupants_small = '';
        var pets = '';
        if($scope.search){
          start_date = $scope.search.start_date;
          end_date = $scope.search.end_date;
          occupants = $scope.search.occupants;
          occupants_small = $scope.search.occupants_small;
          pets = $scope.search.pets;
        }
        var url = $scope.goToProperty(markerData.seo_page_name, start_date, end_date, occupants, occupants_small, pets);
        google.maps.event.addListener(marker, 'click', (function (marker) {
          return function () {
            var content = '<a href="'+ url +'"><img src="' + markerData.image + '" alt="' + markerData.name + '" class="marker-image" /></a><h3 class="marker-title">' + markerData.name + '</h3>';
            content += '<table cellpadding="0" cellspacing="0"><tr><td>Beds: ' + markerData.beds + '</td><td>Baths: ' + markerData.baths + '</td><td>Guests: ' + markerData.guests + '</td></tr></table>';
            infowindow.setContent(content);
            infowindow.open(map, marker);
          }
        })(marker));
        arrMarkers.push(marker);
      };

      $scope.normalIcon = function () {
        return {
          url: 'http://1.bp.blogspot.com/_GZzKwf6g1o8/S6xwK6CSghI/AAAAAAAAA98/_iA3r4Ehclk/s1600/marker-green.png'
        };
      };

      $scope.highlightedIcon = function () {
        return {
          url: 'http://steeplemedia.com/images/markers/markerGreen.png'
        };
      };

      $scope.highlightIcon = function (unit_id) {        
        angular.forEach(arrMarkers, function (item) {
          if (item.id == unit_id) {            
            item.setContent(item.getContent().replace('arrow_box', 'arrow_box_hover'));
          }
        });
      };

      $scope.restoreIcon = function (unit_id) {
        angular.forEach(arrMarkers, function (item) {
          if (item.id == unit_id) {            
            item.setContent(item.getContent().replace('arrow_box_hover', 'arrow_box'));
          }
        });
      };

      $scope.setModalCheckin = function (date) {
        $scope.modal_checkin = date;
      };

      $scope.resetCalendarPopup = function(){
        $scope.showDays = true;
        $scope.modal_total_reservation = 0;
        $scope.modal_nights = '';
      }

      $scope.setNights = function(){

        var frm = new Date($scope.modal_checkin);


        nts = parseInt($scope.modal_nights);
        var the_year = frm.getFullYear();
        if (the_year < 2000) the_year = 2000 + the_year % 100;
        var to = new Date(the_year, frm.getMonth(), frm.getDate() + nts);

        $scope.modal_checkout = to.format("mm/dd/yyyy");
        var booking = {
          checkin  : frm.format("mm/dd/yyyy"),
          checkout : to.format("mm/dd/yyyy"),
          unit_id : $scope.book.unit_id,
          occupants : 1,
          occupants_small : 0,
          pets: 0
        };

        jQuery('#modal_end_date').datepicker('option', 'minDate', frm);

        $scope.modal_checkin = frm.format("mm/dd/yyyy");
        $scope.modal_checkout = to.format("mm/dd/yyyy");
        $scope.updatePricePopupCalendar();

      }

      $scope.updatePricePopupCalendar = function(){
        //run_waitMe('#resortpro-book-unit form', 'bounce', 'Updating Price...');
        run_waitMe('#myModal .modal-content', 'bounce');
        Alert.clear();

        rpapi.getWithParams('VerifyPropertyAvailability', {
          'unit_id': $scope.book.unit_id,
          'startdate': $scope.modal_checkin,
          'enddate': $scope.modal_checkout,
          'occupants': $scope.modal_occupants,
          'occupants_small' : $scope.modal_occupants_small,
          'pets' : $scope.modal_pets
        }).success(function (obj) {
          if (obj.status) {
            $scope.reservation_days = [];
            $scope.total_reservation = 0;
            $scope.first_day_price = 0;
            $scope.rent = 0;
            $scope.taxes = 0;
            Alert.add(Alert.errorType, obj.status.description);
            hide_waitMe('#myModal .modal-content');
          } else {

            rpapi.getWithParams('GetPreReservationPrice', {
              'unit_id': $scope.book.unit_id,
              'startdate': $scope.modal_checkin,
              'enddate': $scope.modal_checkout,
              'occupants': $scope.modal_occupants,
              'occupants_small' : $scope.modal_occupants_small,
              'pets' : $scope.modal_pets
            }).success(function (obj) {
              if (obj.data != undefined) {

                $scope.showDays = false;


                $scope.modal_total_reservation = obj.data.total;
                $scope.modal_rent = obj.data.price;
                $scope.modal_taxes = obj.data.taxes;
                $scope.modal_coupon_discount = obj.data.coupon_discount;
                $scope.modal_reservation_days = obj.data.reservation_days;
                $scope.modal_security_deposits = obj.data.security_deposits;
                $scope.modal_first_day_price = obj.data.first_day_price;
                $scope.modal_required_fees = obj.data.required_fees;
                $scope.modal_taxes_details = obj.data.taxes_details;


                if (obj.data.reservation_days.date != undefined) {
                  $scope.modal_days = false;
                } else {
                  $scope.modal_days = true;
                }

                hide_waitMe('#myModal .modal-content');
              }
            });
          }
        });
      }

      $scope.setCheckoutDate = function (date) {
        if($scope.book.checkout){
          $scope.book.checkout = date.format("mm/dd/yyyy");  
        }        
      };

      $scope.resetInquiry = function (inquiry) {
        $scope.inquiry.unit_id = 0;
        $scope.inquiry.startDate = '';
        $scope.inquiry.endDate = '';
        $scope.inquiry.email = '';
        $scope.inquiry.occupants = '';
        $scope.inquiry.occupantsSmall = '';
        $scope.inquiry.first_name = '';
        $scope.inquiry.last_name = '';
        $scope.inquiry.phone = '';
        $scope.inquiry.message = '';
        $scope.resortpro_inquiry.$setPristine();
        $scope.resortpro_inquiry.$setUntouched();
      };

      $scope.setUnitForInquiry = function (unit_id) {
        if (typeof $scope.inquiry == 'undefined') {
          $scope.inquiry = {};
        }
        $scope.inquiry.unit_id = unit_id;
      }

      $scope.validateInquiry = function(inquiry, popup){

        var error = false;
        if(($scope.resortpro_inquiry.inquiry_email.$error.required && $scope.resortpro_inquiry.inquiry_phone.$error.required)){
          error = true;
        }

        if($scope.resortpro_inquiry.inquiry_first_name.$error.required ||
          $scope.resortpro_inquiry.inquiry_last_name.$error.required ||
          $scope.resortpro_inquiry.inquiry_startdate.$error.required ||
          $scope.resortpro_inquiry.inquiry_enddate.$error.required){
          error = true;
        }

        if(!error){
          $scope.propertyInquiry(inquiry, popup);
        }
      }

      $scope.loadFavorites = function(){
        $scope.loading = true;
        var fav_ids = $cookies.getObject('streamline-favorites');

        var params = {
          include_units : fav_ids.join()
        };

        rpapi.getWithParams('GetPropertyListWordPress', params).success(function (obj) {
          $scope.loading = false;
          
          if(obj.data.property.id){
            $scope.favoritesObj = [];
            $scope.favoritesObj.push(obj.data.property);
          }else{
            $scope.favoritesObj = Object.keys(obj.data.property).map(function(key) {
              return obj.data.property[key];
            });
          }        
        });        
      }

      $scope.checkFavorites = function(property){
        var favorites = $cookies.getObject('streamline-favorites');
        var found = false;
        if(favorites){          
          angular.forEach(favorites, function(value, key) {           
            if(property.id == value){
              found = true;
            }
          });        
        }

        return found;
      }
      
      $scope.removeFromFavorites = function(property){
        var favorites = $cookies.getObject('streamline-favorites');
                
        if(favorites){
          
          angular.forEach(favorites, function(value, key) {            
            if(property.id == value){              
              favorites.splice(key, 1);
            }
          });

          if(favorites.length == 0){            
            $cookies.remove('streamline-favorites', { path : '/'});
            $scope.wishlist = [];
          }else{
            $scope.wishlist = favorites;


            $cookies.putObject('streamline-favorites', favorites, { path : '/'});
          }        
        }
      }

      $scope.addToFavorites = function(property){
                      
        var favorites = $cookies.getObject('streamline-favorites');
        
        if(favorites){
          
          var foundUnit = false;
          angular.forEach(favorites, function(value, key) {
            if(property.id == value){
              foundUnit = true;
            }          
          });
          
          favorites.push(property.id);    
                        
        }else{        
          favorites = [];
          favorites.push(property.id);
        }
      
        $scope.wishlist = favorites;
        $cookies.putObject('streamline-favorites', favorites, { path : '/'});      
      }

      $scope.propertyInquiry = function (inquiry, popup) {

        run_waitMe('#myModal2 .modal-dialog, #inquiry_box', 'bounce', 'Please wait, sending inquiry...');
        setTimeout(function () {

          var params = {
            unit_id: inquiry.unit_id,
            not_blocked_request: 'yes',
            startdate: inquiry.startDate,
            enddate: inquiry.endDate,
            occupants: inquiry.occupants,
            occupants_small: inquiry.occupantsSmall,
            first_name: inquiry.first_name,
            last_name: inquiry.last_name,
            email: inquiry.email,
            mobile_phone: inquiry.phone,
            extra_notes: inquiry.message,
            pets: inquiry.pets,
            disable_minimal_days: 'yes'
          };

          rpapi.getWithParams('MakeReservation', params).success(function (obj) {
            hide_waitMe('#myModal2 .modal-dialog, #inquiry_box');
            if (obj.status) {
              Alert.add(Alert.errorType, obj.status.description);
            } else {
              $scope.resetInquiry();
              if($rootScope.bookingSettings && $rootScope.bookingSettings.inquiryThankUrl != ''){
               $window.location.href = $rootScope.bookingSettings.inquiryThankUrl
              } else {
                Alert.add(Alert.successType, $rootScope.bookingSettings.inquiryThankMsg);
                jQuery('#resortpro_btn_inquiry').prop('disabled', true);

                if(popup){
                  setTimeout(function () {
                    jQuery('#myModal2').modal('hide');
                  }, 3000);
                }
              }
            }
          });
        }, 500);

        return false;
      }

    // Sibers

    $scope.getBookNowTitle = function () {
        return '$' + $scope.bookNowPrice + '/night';
    }

    $scope.extras_top = [
      { title: 'Child lift ticket, snowbasin - 99$', value: 1 },
      { title: 'humidifier - 45$', value: 2 },
      { title: 'in room massage - 99$', value: 3 },
      { title: 'snowbasin lift ticket', value: 4 },
      { title: 'CSA trip insurance', value: 5 },
      { title: 'Child lift ticket, snowbasin - 99$', value: 6 }
    ];

    }
  ]);
})();
