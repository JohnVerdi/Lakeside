<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 11/13/15
 * Time: 11:16 AM
 */
class ResortProTestimonialWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'ResortPro_Testimonial', // Base ID
            __( 'StreamlineCore Testimonial', 'streamline-core' ), // Name
            array( 'description' => __( 'Displays one or more review.', 'streamline-core' ) ) // Args
        );
    }

    public function form( $instance )
    {
        if ( $instance ) {
            $title = esc_attr( $instance[ 'title' ] );
            $number = intval( $instance[ 'number' ] );
            $min_points = trim( $instance[ 'min_points' ] );
            $max_points = esc_attr( $instance[ 'max_points' ] );
            $sorting = esc_attr( $instance[ 'sorting' ] );
            $cache = intval( $instance[ 'cache' ] );
        }
        else {
            $title = __( 'New title for Testimonials', 'streamline-core' );
            $number = 1;
            $min_points = "1";
            $max_points = "5";
            $sorting = 'newest_first';
            $cache = 10;
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'streamline-core' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of Reviews:', 'streamline-core' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'min_points' ); ?>"><?php _e( 'Minimum Points:', 'streamline-core' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'min_points' ); ?>" name="<?php echo $this->get_field_name( 'min_points' ); ?>" type="text" value="<?php echo $min_points; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'max_points' ); ?>"><?php _e( 'Maximum Points:', 'streamline-core' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'max_points' ); ?>" name="<?php echo $this->get_field_name( 'max_points' ); ?>" type="text" value="<?php echo $max_points; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'sorting' ); ?>"><?php _e( 'Sorting:', 'streamline-core' ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'sorting' ); ?>" name="<?php echo $this->get_field_name( 'sorting' ); ?>">
                <option value="newest_first" <?php if($sorting == 'newest_first') echo 'selected="selected"'; ?>>Newest First</option>
                <option value="oldest_first" <?php if($sorting == 'oldest_first') echo 'selected="selected"'; ?>>Oldest First</option>
                <option value="random" <?php if($sorting == 'random') echo 'selected="selected"'; ?>>Random</option>                
            </select>
            
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'cache' ); ?>"><?php _e( 'Cache for ... Minutes (0 == no cache):', 'streamline-core' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'cache' ); ?>" name="<?php echo $this->get_field_name( 'cache' ); ?>" type="text" value="<?php echo $cache; ?>" />
        </p>
        <?php

    }

    public function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['number'] = intval( $new_instance['number'] );
        $instance['min_points'] = trim( $new_instance['min_points'] );
        $instance['max_points'] = trim( $new_instance['max_points'] );
        $instance['cache'] = intval( $new_instance['cache'] );
        $instance['sorting'] = trim( $new_instance['sorting'] );

        return $instance;
    }

    public function widget( $args, $instance )
    {

        $title = $args['before_title'] . apply_filters('widget_title', $instance['title'], 'streamline-testimonial-widget' ) . $args['after_title'];

        if (!$instance['cache']) {
            $feedbacks = apply_filters( 'streamline-feedbacks', StreamlineCore_Wrapper::get_feedback( array( 'limit' => $instance['number'], 'min_points' => $instance['min_points'], 'order_by' => $instance['sorting'] ) ), $instance );

        }else{

            $transient = "resortpro-".$this->id;
            if ( false === ( $feedbacks = get_transient( $transient ) ) )
            {
            	$feedbacks = apply_filters( 'streamline-feedbacks', StreamlineCore_Wrapper::get_feedback( array( 'limit' => $instance['number'], 'min_points' => $instance['min_points'], 'order_by' => $instance['sorting'] ) ), $instance );
                set_transient( $transient, $feedbacks, $instance['cache'] * 60);
            }
        }

        $options = StreamlineCore_Settings::get_options();
        $property_link = get_bloginfo("url");
        if (!empty($options['prepend_property_page'])) {
            $property_link .= "/" . $options['prepend_property_page'];
        }

        // look for template in theme
        $template = ResortPro::locate_template( 'testimonial-widget.tpl.php' );
        if ( empty( $template ) ) {
          // default template
          $template = 'resortpro-testimonial-widget.tpl.php';
        }

        echo $args['before_widget'];

        include($template);

        echo $args['after_widget'];

    }
}
