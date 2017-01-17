/****************************
 *
 * ResortPro v2.0.0
 * Developed by: Hutch White
 *
 * Application specifics.  Only basic configs, root variables and initialization here!!!
 *
 ***************************/
(function () {
  // Changing interpolation to avoid conflict with other integrations
  var app = angular.module('resortpro', ['resortpro.services', 'resortpro.filters', 'resortpro.property', 'resortpro.checkout', 'angularUtils.directives.dirPagination', 'ngMap'],
    function ($interpolateProvider) {
      $interpolateProvider.startSymbol('{[');
      $interpolateProvider.endSymbol(']}');
    }
  );

  // required for angular to retrieve query strings.  this is needed for the details page
  // app.config(function($locationProvider) {
  // 	$locationProvider.html5Mode({enabled: true, requireBase: false});
  // });

  // used by the pagination to define the paging layout
  app.config(function (paginationTemplateProvider) {
    paginationTemplateProvider.setPath(assetsUrl+'app/directives/dirPagination.tpl.html');
  });

  // if you need to define rootScoped variables here, uncomment this block and do here as by the example
  // app.run(function($rootScope){
  //  $rootScope.variable_name = 'value';
  // })

  // defines angular app
  angular.element(document).ready(function () {
    angular.bootstrap(document, ['resortpro']);
  });
})();
