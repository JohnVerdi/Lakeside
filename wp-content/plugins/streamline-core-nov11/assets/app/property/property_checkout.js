(function() {
'use strict';
angular.module('resortpro.property')
.controller('PropertyCheckoutController', [ '$scope', function ($scope) {

    $scope.testClick = function () {
        console.log($scope.checkForm);
    }



}]);
})();
