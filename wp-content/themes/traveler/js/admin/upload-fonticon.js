jQuery(document).ready(function($) {

	$('a#delete-custom-font').click(function(event) {
		$confirm = confirm("Do you want to delete it?. 'Cancel' to stop, 'OK' to delete.");
		if($confirm == true)
			return true;
		else
			return false;
	});
});