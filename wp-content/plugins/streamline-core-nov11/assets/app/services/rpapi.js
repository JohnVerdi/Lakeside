/****************************
 *
 * Base API methods.  Should only return the promise and for basic functions
 *
 ***************************/
;
(function () {

    var app = angular.module('resortpro.services');

    app.factory('rpapi', function($http, $rootScope) {
        return {
            get: function(method) {

                var request = {};
                var params = {};

                params.company_code = $rootScope.companyCode;

                request.methodName = method;
                request.params = params;

                var _obj = JSON.stringify(request);
                //console.log('noparams'+_obj);
                $http.defaults.useXDomain = true;

                return $http.post($rootScope.APIServer, JSON.stringify(request));
            },
            getWithParams: function(method, params) {

                var request = {};

                request.methodName = method;
                //request.ROOT = resortpro_settings.root;

                params.company_code = $rootScope.companyCode;
                request.params = params;

                var _obj = JSON.stringify(request);

                $http.defaults.useXDomain = true;
                delete $http.defaults.headers.common['X-Requested-With'];


                //{ headers: {'Access-Control-Allow-Origin:': '*' }
                return $http.post($rootScope.APIServer, JSON.stringify(request));
            }
        }
    });

    app.factory('rpuri', function($http, $rootScope) {
        return {
            getQueryStringParam: function(sParam) {
                var sPageUrl = window.location.search.substring(1);
                var sURLVariables = sPageUrl.split('&');

                for (var i = 0; i < sURLVariables.length; i++) {
                    var sParameterName = sURLVariables[i].split('=');
                    if (sParameterName[0] == sParam) {
                        return sParameterName[1];
                    }
                }
            }
        }
    });

})();