<?php

/**
 * Adds ResortPro_Widget widget.
 */
class ResortProWidget extends WP_Widget
{
    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct('resortpro_widget', // Base ID
            __( 'StreamlineCore Top Search', 'streamline-core' ), // Name
            array( 'description' => __( 'Search available properties via integrated ResportPro Api.', 'streamline-core' ) ) // Args
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

        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        if( version_compare ( '5.3', PHP_VERSION, '<' ) ){
            $start = new \DateTime();
            $start_date = $start->format('Y-m-d');

            $end = $start;
            $end->add(new \DateInterval('P2D'));
            $end_date = $end->format('Y-m-d');
        } else {
            $start_date = date('Y-m-d'); // now
            $end_date = date('Y-m-d', time()+ (2 * SECONDS_PER_DAY ) ); // two days from now
        }

        $endpoint = StreamlineCore_Settings::get_options( 'endpoint' );

        $areas = StreamlineCore_Wrapper::get_location_areas();
        $amenities = StreamlineCore_Wrapper::get_amenity_filters();


        /**
         * @TODO: make this a dynamically generated field from the source Api connection.
         */
        $nonce = get_option('wpt_resortpro_api_nonce');


        if (!filter_var($endpoint, FILTER_VALIDATE_URL) === false) {

          // look for template in theme
          $template = ResortPro::locate_template( 'widget.tpl.php' );
          if ( empty( $template ) ) {
            // default template
            $template = 'resortpro-widget.tpl.php';
          }

          include($template);

        } else {
            ?>
            <div class="alert alert-danger">
              <i class="fa fa-fw fa-warning"></i>
              <?php _e( 'You must define the StreamlineCore API Endpoint to use this widget!', 'streamline-core' ) ?>
            </div>
        <?php
        }

        //echo __('<div class="small resortpro-powered-by">Powered by <a target="_blank" href="http://resortpro.net">StreamlineCore</a>.</div>', 'text_domain');
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
        $title = !empty($instance['title']) ? $instance['title'] : __('COMPANY_NAME', 'streamline-core');
        $per_page = !empty($instance['per_page']) ? $instance['per_page'] : 5;
        ?>
        <div class="form-group">
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'streamline-core'); ?></label>
            <input class="widefat form-control" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </div>

        <div class="form-group">
            <label for="<?php echo $this->get_field_id('per_page'); ?>"><?php _e('Results / Page:', 'streamline-core'); ?></label>
            <input class="widefat form-control" id="<?php echo $this->get_field_id('per_page'); ?>"
                   name="<?php echo $this->get_field_name('per_page'); ?>" type="number"
                   value="<?php echo esc_attr($per_page); ?>">
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
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['per_page'] = (!empty($new_instance['per_page'])) ? strip_tags($new_instance['per_page']) : 5;

        return $instance;
    }
}



include 'class.ResortProFilterWidget.php';
include 'class.ResortProSearchWidget.php';
include 'class.ResortProMapWidget.php';
include 'class.ResortProFeaturedPropertyWidget.php';
include 'class.ResortProTestimonialWidget.php';


function resortpro_widget_init()
{
    register_widget('ResortProMapWidget');
    register_widget('ResortProFilterWidget');
    register_widget('ResortProSearchWidget');
    register_widget('ResortProSearchWidget');
    register_widget('ResortProFeaturedPropertyWidget');
    register_widget('ResortProTestimonialWidget');
}

add_action('widgets_init', 'resortpro_widget_init');
