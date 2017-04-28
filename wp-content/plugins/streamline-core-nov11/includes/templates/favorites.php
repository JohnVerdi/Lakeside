<div id="primary" class="content-area" ng-controller="PropertyController as pCtrl">
	<div ng-init="loadFavorites();" class="row">
		
		<div class="col-md-12 loading" ng-show="loading">
            <i class="fa fa-circle-o-notch fa-spin"></i> <?php _e('Loading...', 'streamline-core') ?>
        </div>
        
		<div ng-repeat="property in favoritesObj">
        	<?php include($template); ?>
        </div>
	</div>
</div>