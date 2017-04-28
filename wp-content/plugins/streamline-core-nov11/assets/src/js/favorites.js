(function($) {
	console.log('wtf');
  	$(document).ready(function () {
  		alert('welcome');
  	});
  	$( window ).unload(function() {
  		return "Bye now!";
  	});
});