<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 11/13/15
 * Time: 11:16 AM
 */
class ResortProFeaturedPropertyWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'ResortPro_Featured_Property', // Base ID
            __( 'StreamlineCore Featured Property', 'streamline-core' ), // Name
            array( 'description' => __( 'Displays one or more random properties.', 'streamline-core' ), ) // Args
        );
    }

    public function form( $instance )
    {
        if ( $instance ) {
            $title = esc_attr( $instance[ 'title' ] );
            $number = intval( $instance[ 'number' ] );
            $ids = trim( $instance[ 'ids' ] );
            $template = esc_attr( $instance[ 'template' ] );
            $cache = intval( $instance[ 'cache' ] );
        }
        else {
            $title = __( 'New title for Featured Property', 'streamline-core' );
            $number = 1;
            $ids = "";
            $template = "";
            $cache = 10;
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'streamline-core' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of Units:', 'streamline-core' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'ids' ); ?>"><?php _e( 'Unit IDs to rotate (empty for ALL):', 'streamline-core' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'ids' ); ?>" name="<?php echo $this->get_field_name( 'ids' ); ?>" type="text" value="<?php echo $ids; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'template' ); ?>"><?php _e( 'Override default template:', 'streamline-core' ); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id( 'template' ); ?>" name="<?php echo $this->get_field_name( 'template' ); ?>" rows="5" cols="20"><?php echo $template; ?></textarea>
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
        $instance['ids'] = trim( $new_instance['ids'] );
        $instance['template'] = trim( $new_instance['template'] );
        $instance['cache'] = intval( $new_instance['cache'] );

        return $instance;
    }

    public function widget( $args, $instance )
    {

        $title = $args['before_title'] . apply_filters( 'widget_title', $instance['title'], 'streamline-featured-property' ) . $args['after_title'];


        if (!$instance['cache']) {
            $units = apply_filters( 'streamline-featured-units', StreamlineCore_Wrapper::get_random_units( $instance['number'], $instance['ids'] ), $instance );

        }else
        {
            $transient = "resortpro-".$this->id;
            if ( false === ( $units = get_transient( $transient ) ) )
            {
                $units = apply_filters( 'streamline-featured-units', StreamlineCore_Wrapper::get_random_units( $instance['number'], $instance['ids'] ), $instance );
                set_transient( $transient, $units, $instance['cache'] * 60);
            }
        }

        $custom_widget = str_replace('-','_',$this->id);
        if (function_exists($custom_widget))
        {

            $widget_contents = call_user_func($custom_widget,$units);
        }
        else
        {

            $widget_contents = "<ul class='featured-units'>";
            $i = 0;
            foreach ($units as $unit)
            {
                $classes = "featured-unit";
                if ($i == 0)
                    $classes .= " featured-unit-first";
                if ($i == count($units)-1)
                    $classes .= " featured-unit-last";
                $widget_contents .= "<li class='$classes'>".$this->parse_template($instance['template'], $unit)."</li>";
                $i++;
            }
            $widget_contents .= "</ul>";
        }
        echo $args['before_widget'], $widget_contents, $args['after_widget'];
    }

    private function default_template()
    {
        return "\t\t\t<div class='featured-unit-image'><a href=\"%permalink%\"><img src=\"%default_thumbnail_path%\" style='width:100%' /></a></div>\n\t\t\t<div class='featured-unit-title'><a href=\"%permalink%\">%location_name%</a></div>\n\t\t\t<div class='featured-unit-details'>
<div class='featured-unit-bedrooms'>" . __( 'Bedrooms:', 'streamline-core' ) . " %bedrooms_number%</div>
<div class='featured-unit-bathrooms'>" . __( 'Bathrooms:', 'streamline-core' ) . " %bathrooms_number%</div>
<div class='featured-unit-occupants'>" . __( 'Occupants, max:', 'streamline-core' ) . " %max_occupants%</div>
<div class='featured-unit-adults'>" . __( 'Adults, max:', 'streamline-core' ) . " %max_adults%</div>
            </div>\n";
    }

    private function parse_template($template, $unit)
    {
        if (!$template)
            $template = $this->default_template();

        foreach ($unit as $key=>$value){
            $value = (is_array($value)) ? "" : $value;
            $template = str_replace("%$key%",$value,$template);
        }

        return ResortProHelperStrings::same_protocol($template);
    }


}

class ResortProHelperStrings
{
    static function same_protocol($s)
    {
        return str_ireplace("http://","//",$s);
    }
}
