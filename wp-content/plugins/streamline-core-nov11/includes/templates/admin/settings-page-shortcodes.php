<div class="panel panel-info">
  <div class="panel-heading">
    <h4 class="panel-title"><?php _e( 'Shortcodes', 'streamline-core' ) ?></h4>
  </div>
  <div class="panel-body">
    <p><?php _e( 'To show all your units on any post/page, Enter the following shortcode in the editor.', 'streamline-core' ) ?></p>
    <p class="text-center"><strong>[resortpro-browse-results]</strong></p>
    <p><?php _e( 'If you need to show your units using filters, Enter the following shortcode in the editor.', 'streamline-core' ) ?></p>
    <p class="text-center"><strong>[resortpro-search-filter attr1='value1' ... attrX='valueX']</strong></p>
    <p><?php _e( 'The list of attributes, which can be used:', 'streamline-core' ) ?></p>
    <ul>
      <li>location_area_name - <?php _e( 'Name of area', 'streamline-core' ) ?></li>
      <li>location_name - <?php _e( 'Name of location', 'streamline-core' ) ?></li>
      <li>location_type_name - <?php _e( 'Name of location type', 'streamline-core' ) ?></li>
      <li>condo_type_group_name - <?php _e( 'Name of group type', 'streamline-core' ) ?></li>
      <li>occupants – <?php _e( 'Number of occupants', 'streamline-core' ) ?></li>
      <li>adults – <?php _e( 'Number of adults', 'streamline-core' ) ?></li>
      <li>pets – <?php _e( 'Number of pets', 'streamline-core' ) ?></li>
      <li>min_occupants – <?php _e( 'Minimal number of occupants', 'streamline-core' ) ?></li>
      <li>min_adults – <?php _e( 'Minimal number of adults', 'streamline-core' ) ?></li>
      <li>min_pets – <?php _e( 'Minimal number of pets', 'streamline-core' ) ?></li>
      <li>bedrooms_number – <?php _e( 'Number of bedrooms', 'streamline-core' ) ?></li>
      <li>min_bedrooms_number – <?php _e( 'Minimal number of bedrooms', 'streamline-core' ) ?></li>
      <li>bathrooms_number – <?php _e( 'Number of baths', 'streamline-core' ) ?></li>
      <li>min_bathrooms_number – <?php _e( 'Minimal number of baths', 'streamline-core' ) ?></li>
      <li>longterm_enabled - <?php _e( 'Long term unit (1 or yes)', 'streamline-core' ) ?></li>
    </ul>
  </div>
</div>
<div class="panel panel-info">
  <div class="panel-heading">
    <h4 class="panel-title"><?php _e( 'Areas', 'streamline-core' ) ?></h4>
  </div>
  <div class="panel-body">
    <?php $areas = StreamlineCore_Wrapper::get_location_areas( true ) ?>
    <?php if ( sizeof( $areas ) ) : ?>
      <ul>
        <?php foreach ( $areas as $area ) : ?>
          <li>[<?php echo $area->id ?>] <?php echo $area->name ?></li>
        <?php endforeach; ?>
      </ul>
    <?php else : ?>
      <p><?php _e('No areas found.', 'streamline-core' ) ?></p>
    <?php endif; ?>
  </div>
</div>
<div class="panel panel-info">
  <div class="panel-heading">
    <h4 class="panel-title"><?php _e( 'Locations', 'streamline-core' ) ?></h4>
  </div>
  <div class="panel-body">
    <?php $locations = StreamlineCore_Wrapper::get_locations() ?>
    <?php if ( sizeof( $locations ) ) : ?>
      <ul>
        <?php foreach ( $locations as $location ) : ?>
          <li>[<?php echo $location->id ?>] <?php echo $location->name ?></li>
        <?php endforeach; ?>
      </ul>
    <?php else : ?>
      <p><?php _e('No locations found.', 'streamline-core' ) ?></p>
    <?php endif; ?>
  </div>
</div>
<div class="panel panel-info">
  <div class="panel-heading">
    <h4 class="panel-title"><?php _e( 'Location Resorts', 'streamline-core' ) ?></h4>
  </div>
  <div class="panel-body">
    <?php $location_resorts = StreamlineCore_Wrapper::get_location_resorts() ?>
    <?php if ( sizeof( $location_resorts ) ) : ?>
      <ul>
        <?php foreach ( $location_resorts as $resort ) : ?>
          <li>[<?php echo $resort->id ?>] <?php echo $resort->name ?></li>
        <?php endforeach; ?>
      </ul>
    <?php else : ?>
      <p><?php _e('No location resorts found.', 'streamline-core' ) ?></p>
    <?php endif; ?>
  </div>
</div>
<div class="panel panel-info">
  <div class="panel-heading">
    <h4 class="panel-title"><?php _e( 'View Names', 'streamline-core' ) ?></h4>
  </div>
  <div class="panel-body">
    <?php $view_names = StreamlineCore_Wrapper::get_view_names() ?>
    <?php if ( sizeof( $view_names ) ) : ?>
      <ul>
        <?php foreach ( $view_names as $view ) : ?>
          <li>[<?php echo $view->id ?>] <?php echo $view->name ?></li>
        <?php endforeach; ?>
      </ul>
    <?php else : ?>
      <p><?php _e('No view names found.', 'streamline-core' ) ?></p>
    <?php endif; ?>
  </div>
</div>
