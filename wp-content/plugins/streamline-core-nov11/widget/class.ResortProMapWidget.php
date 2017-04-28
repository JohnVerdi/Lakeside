<?php

class ResortProMapWidget extends WP_Widget
{
    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct('resortpro_map_widget', // Base ID
            __('StreamlineCore Map Search', 'streamline-core'), // Name
            array('description' => __('Search available properties using google maps.', 'streamline-core')) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        $height = (is_numeric($instance['height']))? $instance['height'] : 320 ;
        $latitude = (is_numeric($instance['latitude']))? $instance['latitude'] : 33.397 ;
        $longitude = (is_numeric($instance['longitude']))? $instance['longitude'] : - 111.944;
        $zoom = (is_numeric($instance['zoom']))? $instance['zoom'] : 12 ;
        $type = $instance['type'];
        $autozoom = (is_numeric($instance['autozoom']))? $instance['autozoom'] : 0 ;

        /**
         * @TODO: make this a dynamically generated field from the source Api connection.
         */
        $nonce = get_option('wpt_resortpro_api_nonce');

        // look for template in theme
        $template = ResortPro::locate_template( 'map-widget.tpl.php' );
        if ( empty( $template ) ) {
          // default template
          $template = 'resortpro-map-widget.tpl.php';
        }

        include($template);

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        $map_height = !empty($instance['height']) ? $instance['height'] : 320;
        $latitude = !empty($instance['latitude']) ? $instance['latitude'] : 33.397;
        $longitude = !empty($instance['longitude']) ? $instance['longitude'] : -111.944;
        $zoom = !empty($instance['zoom']) ? $instance['zoom'] : 12;
        $type = !empty($instance['type']) ? $instance['type'] : 'ROADMAP';
        $autozoom = !empty($instance['autozoom']) ? $instance['autozoom'] : 0;

        ?>
        <div class="form-group">
            <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height in pixels:', 'streamline-core'); ?></label>
            <input class="widefat form-control" id="<?php echo $this->get_field_id('height'); ?>"
                   name="<?php echo $this->get_field_name('height'); ?>" type="text"
                   value="<?php echo esc_attr($map_height); ?>">
        </div>

        <div class="form-group">
            <label for="<?php echo $this->get_field_id('latitude'); ?>"><?php _e('Initial Latitude:', 'streamline-core'); ?></label>
            <input class="widefat form-control" id="<?php echo $this->get_field_id('latitude'); ?>"
                   name="<?php echo $this->get_field_name('latitude'); ?>" type="text"
                   value="<?php echo esc_attr($latitude); ?>">
        </div>

        <div class="form-group">
            <label for="<?php echo $this->get_field_id('longitude'); ?>"><?php _e('Initial Longitude:', 'streamline-core'); ?></label>
            <input class="widefat form-control" id="<?php echo $this->get_field_id('longitude'); ?>"
                   name="<?php echo $this->get_field_name('longitude'); ?>" type="text"
                   value="<?php echo esc_attr($longitude); ?>">
        </div>

        <div class="form-group">
            <label for="<?php echo $this->get_field_id('zoom'); ?>"><?php _e('Initial Zoom:', 'streamline-core'); ?></label>
            <input class="widefat form-control" id="<?php echo $this->get_field_id('zoom'); ?>"
                   name="<?php echo $this->get_field_name('zoom'); ?>" type="text"
                   value="<?php echo esc_attr($zoom); ?>">
        </div>

        <div class="form-group">
            <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Map Type:', 'streamline-core'); ?></label>

            <select name="<?php echo $this->get_field_name('type'); ?>" id="<?php echo $this->get_field_id('type'); ?>" class="widefat form-control">
                <option value="ROADMAP" <?php if($type == 'ROADMAP') echo 'selected="selected"' ?>><?php _e( 'Roadmap', 'streamline-core' ) ?></option>
                <option value="SATELLITE" <?php if($type == 'SATELLITE') echo 'selected="selected"' ?>><?php _e( 'Satellite', 'streamline-core' ) ?></option>
                <option value="HYBRID" <?php if($type == 'HYBRID') echo 'selected="selected"' ?>><?php _e( 'Hybrid', 'streamline-core' ) ?></option>
            </select>
        </div>

        <div class="form-group">
            <label for="<?php echo $this->get_field_id('autozoom'); ?>"><?php _e('Autozoom:', 'streamline-core'); ?></label>

            <select name="<?php echo $this->get_field_name('autozoom'); ?>" id="<?php echo $this->get_field_id('autozoom'); ?>" class="widefat form-control">
                <option value="0" <?php if($autozoom == '0') echo 'selected="selected"' ?>><?php _e( 'No', 'streamline-core' ) ?></option>
                <option value="1" <?php if($autozoom == '1') echo 'selected="selected"' ?>><?php _e( 'Yes', 'streamline-core' ) ?></option>
            </select>
        </div>

    <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {

        $instance = array();
        $instance['height'] = (!empty($new_instance['height'])) ? strip_tags($new_instance['height']) : '';
        $instance['latitude'] = (!empty($new_instance['latitude'])) ? strip_tags($new_instance['latitude']) : 33.397;
        $instance['longitude'] = (!empty($new_instance['longitude'])) ? strip_tags($new_instance['longitude']) : -111.944;
        $instance['zoom'] = (!empty($new_instance['zoom'])) ? strip_tags($new_instance['zoom']) : 12;
        $instance['type'] = (!empty($new_instance['type'])) ? strip_tags($new_instance['type']) : 'ROADMAP';
        $instance['autozoom'] = (!empty($new_instance['autozoom'])) ? strip_tags($new_instance['autozoom']) : 0;
        return $instance;
    }
}
