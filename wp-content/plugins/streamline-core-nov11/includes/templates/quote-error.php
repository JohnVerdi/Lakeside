<?php
/**
 * quote-error - The template for reporting errors retrieving quotes
 *
 * This is the template that displays if there is an error in retrieving a quote from ResortPro.net.
 * You can override this template by creating your own "my_theme/streamline/quote-error.php" file
 *
 * @package    Streamline
 * @since      v1.0
 */

?>
<div id="custom-quote">
	<div class="container-fluid">
		<div class="alert alert-danger">
			<?php echo $reservations_quote['status']['description']; ?>
		</div>
	</div>
</div>
