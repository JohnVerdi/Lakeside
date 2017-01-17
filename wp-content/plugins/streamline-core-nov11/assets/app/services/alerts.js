/****************************
*
*	Alert service set up to push alerts.
*
***************************/
;
(function () {
	var app = angular.module('resortpro.services');

	app.service('Alert', function($rootScope, $timeout) {

		$rootScope.alerts = [];

		this.clear = function(){
			$rootScope.alerts = [];
		}
		this.add = function(type, message) {
			var alert = {'type': type, 'message': message};

			//push alerts to array to be displayed.  alerts will fade out.  
			//adjust the timeout to extend or shorten display time.
			$rootScope.alerts.push(alert);
			// $timeout(function() {
			// 	$rootScope.alerts.splice($rootScope.alerts.indexOf(alert), 1);
			// }, 5000);
		};

		//default messages to use.  these can be pulled when you set your alert
		this.errorType = 'danger',
		this.errorMessage = 'Sorry, there was an error while processing your request.';

		this.successType = 'success',
		this.successMessage = 'Saved successfully.';
	});

})();