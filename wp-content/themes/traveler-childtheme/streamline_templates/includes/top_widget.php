<div class="booking-item-details no-border-top">
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <h2 class="title"><?php echo $property['name'];?></h2>
            <div class="booking-item-rating">
                <div class="pull-left" style="margin: 20px 0;">
                    <strong><a href="#" title="locateion"><?php echo $property['city'];?></a></strong>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6 col-sm-3">
                    <div class="facility-item" rel="tooltip" data-placement="top" title="" data-original-title="Sleeps">
                        <i class="fa fa-male"></i>
                        <h5 class="booking-item-feature-sign"><?php echo $property['max_adults'];?> Sleeps</h5>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="facility-item" rel="tooltip" data-placement="top" title="" data-original-title="Beds">
                        <i class="im im-bed"></i>
                        <h5 class="booking-item-feature-sign"><?php echo $property['max_occupants'];?> Beds</h5>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="facility-item" rel="tooltip" data-placement="top" title="" data-original-title="Baths">
                        <i class="fa fa-tint" aria-hidden="true"></i>
                        <h5 class="booking-item-feature-sign"><?php echo $property['bathrooms_number'];?> Baths</h5>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="facility-item" rel="tooltip" data-placement="top" title="" data-original-title="Max pets">
                        <i class="fa fa-paw" aria-hidden="true"></i>
                        <h5 class="booking-item-feature-sign"><?php echo ( isset($property['max_pets']) && $property['max_pets'] != false  ) ? $property['max_pets'] : 'No pets';?></h5>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>