<?php
if(isset($_GET['latitude']) && is_numeric($_GET['latitude']) && isset($_GET['longitude']) && is_numeric($_GET['longitude'])){
    $latitude = $_GET['latitude'];
    $longitude = $_GET['longitude'];
}

if(isset($_GET['zoom']) && is_numeric($_GET['zoom'])){
    $zoom = $_GET['zoom'];
}
?>
<?php do_action( 'streamline_widget_map_before' ); ?>
<div class="map-search" ng-model="mapEnabled" style="max-width:100%;height:<?php echo $height ?>px; position: relative;">

    <map style="height:<?php echo $height ?>px;" center="<?php echo $latitude ?>, <?php echo $longitude ?>" zoom="<?php echo $zoom ?>" on-dragend="dragEnd(search)"
         on-zoom-changed="dragEnd(search)"
         ng-init="initializeMap(); autoZoom='<?php echo $autozoom; ?>'"
         map-type-id="<?php echo $type; ?>"
         scrollwheel="false"
         map-type-control-options="{
            mapTypeIds: ['SATELLITE'],
            style: 'DROPDOWN_MENU'
            }"
         class="map-canvas"></map>

    <div class="map-refresh-controls google">

        <div class="map-auto-refresh">
            <label class="">
                <input class="map-auto-refresh-checkbox" type="checkbox" ng-checked="mapSearchEnabled" ng-click="toggleMapSearch()" />
                <small> <?php _e( 'Search while moving map', 'streamline-core' ) ?></small>
            </label>
        </div>
    </div>
</div>
<?php do_action( 'streamline_widget_map_after' ); ?>
