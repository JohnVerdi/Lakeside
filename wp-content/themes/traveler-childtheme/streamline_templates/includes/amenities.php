<div id="tab-1439375290816-2-0" class="ui-tabs-panel wpb_ui-tabs-hide vc_clearfix ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-3" role="tabpanel" aria-hidden="true" style="display: none;">
    <div role="tabpanel" class="tab-pane" id="property-amenities-pane">
        <h3><?php _e( 'Amenities', 'streamline-core' ) ?></h3>
        <ul class="list-group row amenities">
            <?php
            if($property['unit_amenities']['amenity']['amenity_name']){
                ?>
                <li class="list-group-item col-xs-4">
                    <?php echo $property['unit_amenities']['amenity']['amenity_name']; ?>
                </li>
                <?php
            }else{
                foreach ($property['unit_amenities']['amenity'] as $amenity) {
                    ?>
                    <li class="list-group-item col-xs-4">
                        <?php echo $amenity['amenity_name']; ?>
                    </li>
                <?php }
            }
            ?>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>