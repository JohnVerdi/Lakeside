/****************************
*
* Filters for ResortPro plugin.  nothing but filters here!
*
***************************/
(function () {
  var Filters = angular.module('resortpro.filters', []);

  Filters.filter('trustedHtml', ['$sce', function ($sce) {
    return function (text) {
      return $sce.trustAsHtml(text);
    }
  }]);

  Filters.filter('pluralizeRating', ['$sce', function ($sce) {
    return function (rating) {
      return rating > 1 ? rating + ' reviews' : rating + ' review';
    }
  }]);
})();
