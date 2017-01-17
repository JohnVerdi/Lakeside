<?php 
/**
*@since 1.1.8
**/
?>
<!-- <div class="wrap" id="st-duplicate-data-wrapper">
	<div id="col-container" class="container-upload-font">
		<div id="col-right" style="float: left;">
			<h2 class="title"><?php echo __('Upgrade Data', ST_TEXTDOMAIN); ?></h2>
			<br class="clear">
			<div class="form-group">
				<div id="message" class="updated notice notice-success is-dismissible below-h2" style="padding: 10px 5px; display: none">
				</div>
			</div>
			<p class="text-danger">
				Users who are using version below 1.1.8 are required to click 'Run' button. It helps improve speed of loading data

			</p>
			<?php
				$duplicate = get_option('st_duplicated_data', 'not_duplicate');
				if($duplicate == 'duplicated'):
			?>
			<p class="updated notice notice-warning is-dismissible" style="padding: 10px 5px">
				You did it once. If you want to do it again, click "Run" button.
			</p>
			<?php endif; ?>
			<form action="" method="post">
				<div class="form-group">
					<button id="st-duplicate-data" class="pull-left button button-primary button-large" type="submit">Run</button>
					<span class="spinner pull-left"></span>
				</div>
			</form>
		</div>
	</div>	
</div>
 -->