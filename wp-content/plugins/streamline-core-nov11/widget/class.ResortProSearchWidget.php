<?php
class ResortProSearchWidget extends WP_Widget
{
    var $criteria = array();

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
      $this->criteria = array(
        'booking_dates'   => __( 'Booking Dates', 'streamline-core' ),
        'guests_adults'   => __( 'Number of Adult Guests', 'streamline-core' ),
        'guests_children' => __( 'Number of Children Guests', 'streamline-core' ),
        'guests_pets'     => __( 'Number of Pets', 'streamline-core' ),
        'number_bedrooms' => __( 'Number of Bedrooms', 'streamline-core' ),
        'lodging_type'    => __( 'Lodging Type', 'streamline-core' ),
        'unit'            => __( 'Unit Name', 'streamline-core' ),
        'code'            => __( 'Unit Code', 'streamline-core' ),
        'area'            => __( 'Area', 'streamline-core' ),
        'location'        => __( 'Location', 'streamline-core' ),
        'location_resort' => __( 'Location/Resort', 'streamline-core' ),
        'neighborhood'    => __( 'Neighborhood', 'streamline-core' ),
        'view_name'       => __( 'View', 'streamline-core' ),
        'bedroom_type'    => __( 'Bedroom Type', 'streamline-core' ),
        'group_type'      => __( 'Group Type', 'streamline-core' ),
        'home_type'       => __( 'Home Type', 'streamline-core' ),
        'amenities'       => __( 'Amenities', 'streamline-core' ),
        'viewall_button'  => __( 'View All Units', 'streamline-core' ),
        'customgroup'     => __( 'Custom Group', 'streamline-core' ),
        'sorting_order'   => __( 'Sorting Order', 'streamline-core' ),
        'submit_button'   => __( 'Search Button', 'streamline-core' )
      );
      parent::__construct( 'resortpro_search_widget', __('StreamlineCore Search Widget', 'streamline-core'), array(
        'description' => __('Search available properties via integrated ResportPro Api.', 'streamline-core')
      ) );
    }

    private function options($field)
    {
        $labels = array(
            'guests_adults'   => __( 'Adults:', 'streamline-core' ),
            'guests_children' => __( 'Children:', 'streamline-core' ),
            'guests_pets'     => __( 'Pets:', 'streamline-core' ),
            'number_bedrooms' => __( 'Bedrooms:', 'streamline-core' ),
            'lodging_type'    => __( 'Lodging Type:', 'streamline-core' ),
            'unit'            => __( 'Unit Name:', 'streamline-core' ),
            'code'            => __( 'Unit Code:', 'streamline-core' ),
            'area'            => __( 'Area:', 'streamline-core' ),
            'neighborhood'    => __( 'Neighborhood:', 'streamline-core' ),
            'view_name'       => __( 'View:', 'streamline-core' ),
            'location'        => __( 'Location:', 'streamline-core' ),
            'location_resort' => __( 'Location/Resort:', 'streamline-core' ),
            'amenities'       => __( 'Amenities:', 'streamline-core' ),
            'bedroom_type'    => __( 'Bedroom Type:', 'streamline-core' ),
            'group_type'      => __( 'Group Type:', 'streamline-core' ),
            'home_type'       => __( 'Home Type:', 'streamline-core' )
            //'submit_button' => __( 'Submit Button:', 'streamline-core' )
        );

        if (($field == 'customgroup') && array_key_exists('customgroup', $this->criteria))
            $labels['customgroup'] = $this->criteria['customgroup'] . ':';

        $result = array();
         if (array_key_exists($field, $labels)) {

            $result['label'] = array('text', 'Label', $labels[$field]);
            $result['col_lg'] = array('integer', 'Bootstrap col-lg', 2, 12);
            $result['col_md'] = array('integer', 'Bootstrap col-md', 2, 12);
            $result['col_sm'] = array('integer', 'Bootstrap col-sm', 2, 12);
            $result['col_xs'] = array('integer', 'Bootstrap col-xs', 2, 12);
        }
        if($field == 'submit_button' || $field == 'viewall_button'){
            $result['col_lg'] = array('integer', 'Bootstrap col-lg', 2, 12);
            $result['col_md'] = array('integer', 'Bootstrap col-md', 2, 12);
            $result['col_sm'] = array('integer', 'Bootstrap col-sm', 2, 12);
            $result['col_xs'] = array('integer', 'Bootstrap col-xs', 2, 12);
        }
        switch ($field) {
            case 'booking_dates':
                $result['label_checkin'] = array('text', "Check-in Label", __( 'Arrival', 'streamline-core' ) );
                $result['label_checkout'] = array('text', "Check-out Label", __( 'Departure', 'streamline-core' ));
                $result['checkin_date'] = array('integer', "<span title='-1 FOR BLANK DEFAULTS'>" . __( 'Check-in Today + Days', 'streamline-core' ) . "</span>", 1, 7, -1);
                $result['booking_nights'] = array('integer', __( 'Default Stay', 'streamline-core' ), 2, 90);

                $result['col_lg'] = array('integer', 'Bootstrap col-lg', 1, 12);
                $result['col_md'] = array('integer', 'Bootstrap col-md', 1, 12);
                $result['col_sm'] = array('integer', 'Bootstrap col-sm', 1, 12);
                $result['col_xs'] = array('integer', 'Bootstrap col-xs', 1, 12);

                break;
            case 'guests_adults':
                $result['max'] = array('integer', __( 'Maximum number', 'streamline-core' ), 10, 100);
                break;
            case 'guests_children':
                $result['max'] = array('integer', __( 'Maximum number', 'streamline-core' ), 0, 100, 0);
                break;
            case 'guests_pets':
                $result['max'] = array('integer', __( 'Maximum number', 'streamline-core' ), 0, 2, 0);
                break;
            case 'number_bedrooms':
                $result['min'] = array('integer', __( 'Minimum number', 'streamline-core' ), 0, 10, 0);
                $result['max'] = array('integer', __( 'Maximum number', 'streamline-core' ), 4, 10, 1);
                $result['plus'] = array('checkbox', __( 'Use plus logic', 'streamline-core' ), 0, 1);
                break;
            case 'viewall_button':
                $result['class'] = array('text', __( 'CSS class', 'streamline-core' ), "btn");
                $result['url'] = array('text', __( 'Button URL', 'streamline-core' ), "");
                $result['text'] = array('text', __( 'Text in button', 'streamline-core' ), "");
                break;
            case 'submit_button':
                $result['class'] = array('text', __( 'CSS class', 'streamline-core' ), "btn");
                $result['text'] = array('text', __( 'Button text', 'streamline-core' ), "");
                break;
            case 'amenities':
                $result['group'] = array('checkbox', __( 'Organize by group', 'streamline-core' ), 1, 1);
                break;
            case 'sorting_order':
                $options = array(
                    "max_occupants_desc"  => __( 'Max occupants', 'streamline-core' ),
                    "max_occupants_asc"   => __( 'Min occupants', 'streamline-core' ),
                    "bedrooms_number"     => __( 'Bedrooms number', 'streamline-core' ),
                    "min_bedrooms_number" => __( 'Min bedrooms number', 'streamline-core' ),
                    "area"                => __( 'Square feet', 'streamline-core' ),
                    "area_low"            => __( 'Square feet low', 'streamline-core' ),
                    "view"                => __( 'View order', 'streamline-core' ),
                    "price"               => __( 'Price descending', 'streamline-core' ),
                    "price_low"           => __( 'Price Ascending', 'streamline-core' ),
                    "pets"                => __( 'Pets', 'streamline-core' ),
                    "rotation"            => __( 'StreamlineCore Unit Rotate', 'streamline-core' ),
                    "random"              => __( 'Random', 'streamline-core' )
                  );
                $result['by'] = array('dropdown', __( 'Sort units by', 'streamline-core' ), $options);
                break;
        };
        return $result;
    }

    private function filters_options()
    {
        $result = array('template', 'submit_id', 'submit_url');
        foreach (array_keys($this->criteria) as $fid) {
            $options = array_keys($this->options($fid));
            foreach ($options as $oid)
                $result[] = "$fid-$oid";
        }
        return $result;
    }

    private function show_option_checkbox($id, $label, $default, $checked)
    {

        $checked = ($checked === null) ? $default : $checked;
        $_checked = ($checked) ? " checked=\"checked\"" : "";
        return "<label for='$id-tmp'><input id=\"$id-tmp\" name=\"$id-tmp\" type=\"checkbox\" style=\"width:auto\" value=\"1\" $_checked /> $label </label>";
    }

    private function show_option_text($id, $label, $value, $default)
    {
        $value = ($value === null) ? $default : $value;
        return "<label for='$id-tmp'>$label:</label><input id=\"$id-tmp\" name=\"$id-tmp\" type=\"text\" value=\"" . htmlspecialchars($value) . "\" />";
    }

    private function show_option_integer($id, $label, $value, $default, $max_value, $min_value = 1)
    {
        $value = ($value === null) ? $default : $value;
        return "<label for='$id-tmp'>$label:</label>" . ResortPro::dropdown("$id-tmp", array_combine(range($min_value, $max_value), range($min_value, $max_value)), $value);
    }

    private function show_option_dropdown($id, $label, $values, $selected)
    {
        return "<label for='$id-tmp'>$label:</label>" . ResortPro::dropdown("$id-tmp", $values, $selected);
    }

    private function show_options($fid, $options, &$instance)
    {
        $result = "";
        foreach ($options as $oid => $option) {
            $type = $option[0];
            $label = $option[1];
            $default = $option[2];
            $internal = "$fid-$oid";
            $id = $this->get_field_id($internal);
            switch ($type) {
                case "text":
                    $result .= $this->show_option_text($id, $label, $instance[$internal], $default);
                    break;
                case "integer":
                    $min_value = array_key_exists(4, $option) ? $option[4] : 1;
                    $result .= $this->show_option_integer($id, $label, $instance[$internal], $default, $option[3], $min_value);
                    break;
                case "checkbox":
                    $result .= $this->show_option_checkbox($id, $label, $default, $instance[$internal]);
                    break;
                case "dropdown":
                    $result .= $this->show_option_dropdown($id, $label, $option[2], $instance[$internal]);
                    break;
            }
            $result .= "<br />";
        }
        return $result;
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

        $widget_title = ( empty($instance['title']) ? '' : $widget_title = $args['before_title'] . apply_filters('widget_title', $instance['title'], 'streamline-search-widget' ) . $args['after_title'] );

        // use a template for the output so that it can easily be overridden by theme

        // check for template in active theme
        $templ = ($instance['template']) ? $instance['template'] : $this->default_template($instance);


        $search_template = $this->parse_template( apply_filters( 'streamline-search-widget-template', $templ, $instance ), $instance );

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
        $nonce = wp_create_nonce('resortpro_nonce');

        if (!filter_var($endpoint, FILTER_VALIDATE_URL) === false) {

          // look for template in theme
          $template = ResortPro::locate_template( 'search-widget.tpl.php' );
          if ( empty( $template ) ) {
            // default template
            $template = 'resortpro-search-widget.tpl.php';
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
        if ($instance) {
            $title = esc_attr($instance['title']);
            $fields = ($instance['fields']) ? explode(",", $instance['fields']) : array();
        } else {
            $title = __('New title for Complete Search', 'text_domain');
            $fields = array("booking_dates", "guests_adults", "submit_button");
        }

        $hidden_field_id = $this->get_field_id('fields');
        $modal_div_id = $this->get_field_id('modal');

        $title = !empty($instance['title']) ? $instance['title'] : '';
        $per_page = !empty($instance['per_page']) ? $instance['per_page'] : 5;
        ?>


        <div class="resortpro-widget-dialog wp-dialog" id="<?php echo $modal_div_id?>">
          <div class="resortpro-widget-selected-filters"><?php _e( 'Selected Filters', 'streamline-core' ) ?></div>
          <div class="resortpro-widget-available-filters"><?php _e( 'Available Filters', 'streamline-core' ) ?></div>
          <div class="resortpro-widget-filters">
            <ul id="<?php echo $modal_div_id?>-sortable2" class="connectedSortable connectedSortable-right"></ul>
            <ul id="<?php echo $modal_div_id?>-sortable1" class="connectedSortable connectedSortable-left">
                <?php
                foreach ($this->criteria as $id => $name) {
                    echo "<li class=\"ui-state-default\" field=\"$id\">
                        <div class='resortpro-switcher'>&dArr;</div>
                        <span class='resortpro-filtername'>$name</span>
                        <div class='resortpro-switchable'>" . $this->show_options($id, $this->options($id), $instance) . "</div></li>";
                }
                ?>
            </ul>
          </div>
          <div align="left" style="clear: both">
              <?php _e( 'Choose submit button from the list:', 'streamline-core' ) ?>
              <select id="<?php echo $this->get_field_id('submit_id')?>-tmp">
                  <option value=''>---</option>
                  <?php for ($i = 1; $i <= 14; $i++) {
                      $selected = ($i == $instance['submit_id']) ? "selected='selected'" : "";
                      echo "<option value=\"$i\" $selected>$i</option>";
                  }
                  ?>
              </select>
              <?php _e( 'or put button URL here:', 'streamline-core' ) ?>
              <input style='width:200px' type="text" id="<?php echo $this->get_field_id('submit_url')?>-tmp"
                     value="<?php echo htmlspecialchars($instance['submit_url'])?>"/>
          </div>
          <div align="center"><textarea id="<?php echo $this->get_field_id('template')?>-tmp" rows="6" cols="20"
                                        class="widefat"><?php echo $instance['template']?></textarea>
              <small><?php _e( 'put custom template for this widget in the area above', 'streamline-core' ) ?></small>
          </div>
        </div>

        <p>
            <input type="hidden" id="<?php echo $hidden_field_id ?>"
                   name="<?php echo $this->get_field_name('fields'); ?>" type="text"
                   value="<?php echo implode(",", $fields); ?>"/>
            <?php
            foreach ($this->filters_options() as $fid_oid) {
                ?>
                <input class="resortpro-var-container" type="hidden" id="<?php echo $this->get_field_id($fid_oid); ?>"
                       name="<?php echo $this->get_field_name($fid_oid); ?>"
                       value="<?php echo htmlspecialchars($instance[$fid_oid]) ?>"/>
            <?php
            }
            ?>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
        </p>

        <?php
        $no = (empty($fields)) ? "block" : "none";
        $yes = (empty($fields)) ? "none" : "block";
        echo "<p class='resortpro-searching-by-no' style='display:$no;color:white;background-color:red;padding:2px'><strong>" . __( 'This widget won\'t work, because it has no search fields selected.', 'streamline-core' ) . "</strong></p>";
        $by = array();
        foreach ($fields as $field)
            $by[] = $this->criteria[$field];
        echo "<p class='resortpro-searching-by-yes' style='display:$yes;'>" . __( 'Searching by:', 'streamline-core' ) . " <span class='resortpro-searching-by'>" . implode(", ", $by) . "</span></p>";
        ?>

        <p align="center"><input type="button" value="<?php _e( 'Configure Search Fields', 'streamline-core' ) ?>"
                                 onclick="resortpro_configure_search_fields('<?php echo $modal_div_id?>','<?php echo $hidden_field_id?>')"
                                 class="button-primary"/></p>

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
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['fields'] = strip_tags($new_instance['fields']);
        $amenities = false;

        foreach ($this->filters_options() as $fid_oid) {
            $instance[$fid_oid] = $new_instance[$fid_oid];
        }

        $arr_fields = explode(',', $new_instance['fields']);
        $amenities = true;
        foreach ($arr_fields as $value) {
            if($value == 'amenities'){
                $amenities = false;
            }
        }

		update_option( 'streamline_skip_amenities', $amenities );

        return $instance;
    }

    private function default_template($instance)
    {

        if (!$instance['fields'])
            return "";
        $fields = explode(",", $instance['fields']);
        $result = "";
        for ($i = 0; $i < count($fields); $i++) {
            $field = $fields[$i];
            $class_feedback = '';
            if ($field != "viewall_button2") {
                if ($field == 'booking_dates') {
                    if (strpos($result, 'resortpro-search-checkin') !== false)
                        $field = 'checkout';
                    else {
                        $field = 'checkin';
                        $i--;
                    }

                    $class_feedback = 'has-feedback';
                    $bs_class = "col-lg-{$instance['booking_dates-col_lg']} col-md-{$instance['booking_dates-col_md']} col-sm-{$instance['booking_dates-col_sm']} col-xs-{$instance['booking_dates-col_xs']}";
                }else if($field == 'sorting_order'){
                    $bs_class = 'hidden';
                } else {
                    $bs_class = "col-lg-{$instance[$field.'-col_lg']} col-md-{$instance[$field.'-col_md']} col-sm-{$instance[$field.'-col_sm']} col-xs-{$instance[$field.'-col_xs']}";
                }
                $classname = "resortpro-search-" . str_replace("_", "-", $field);

                $result .= "<div class='$bs_class'>";
                $result .= "<div class='form-group $class_feedback $classname-block'>";

                if(!($field == 'submit_button' || $field == 'sorting_order'))
                    $result .= "<label for=\"{$field}\">%{$field}_label%</label>";

                $result .= "%{$field}_field%";

                $result .= "</div>";
                $result .= "</div>";
            }
        }

        return $result;
    }

    private function parse_template($template, $instance)
    {
        if (!$instance['fields'])
            return $template;
        $replacements = array();
        $fields = explode(",", $instance['fields']);
        foreach ($fields as $field) {
            if ($field != 'booking_dates')
                $replacements[$field . '_label'] = ltrim($instance["$field-label"], "!");
            else {
                $replacements['checkin_label'] = $instance["booking_dates-label_checkin"];
                $replacements['checkout_label'] = $instance["booking_dates-label_checkout"];
            }
        }

        if (strpos($template, "%checkin_field%") !== false) {
            $checkin_ts = time() + 86400 * $instance['booking_dates-checkin_date'];
            $checkin = ($instance['booking_dates-checkin_date'] == -1) ? '' : date("m/d/Y", $checkin_ts);
            $checkin_date = array_key_exists('resortpro_sw_checkin', $_REQUEST) ? $_REQUEST['resortpro_sw_checkin'] : $checkin;

            $min_stay = '';
            $checkin_days = '';
            if($instance['booking_dates-booking_nights'] > 0)
                $min_stay = 'data-min-stay="'.$instance['booking_dates-booking_nights'].'"';

            if($instance['booking_dates-checkin_date'] > 0)
                $checkin_days = 'data-checkin-days="'.$instance['booking_dates-checkin_date'].'"';

            $replacements['checkin_field'] = '<input id="search_start_date" '.$min_stay.' '.$checkin_days.' data-bindpicker="#search_end_date" class="form-control datepicker" type="text" name="start_date" readonly="readonly" placeholder="'.$instance["booking_dates-label_checkin"].'" /><span class="glyphicon glyphicon-calendar form-control-feedback"></span>';

            if ($label = $this->_label($instance['booking_dates-label_checkin']))
                $replacements['checkin_field'] = "<label class='resortpro-input'><span>$label</span>{$replacements['checkin_field']}</label>";

            $checkout = ($instance['booking_dates-checkin_date'] == -1) ? '' : date("m/d/Y", $checkin_ts + 86400 * $instance['booking_dates-booking_nights']);
            $checkout_date = array_key_exists('resortpro_sw_checkout', $_REQUEST) ? $_REQUEST['resortpro_sw_checkout'] : $checkout;
            //$replacements['checkout_field'] = '<input type="text" name="resortpro_sw_checkout" id="resortpro_sw_checkout" value="'.$checkout_date.'" class="resortpro_sw-date form-control">';
            $replacements['checkout_field'] = '<input id="search_end_date" class="form-control datepicker" type="text" name="end_date" readonly="readonly" placeholder="'.$instance["booking_dates-label_checkout"].'" /><span class="glyphicon glyphicon-calendar form-control-feedback"></span>';

            if ($label = $this->_label($instance['booking_dates-label_checkout']))
                $replacements['checkout_field'] = "<label class='resortpro-input'><span>$label</span>{$replacements['checkout_field']}</label>";
        }
        if (strpos($template, "%guests_adults_field%") !== false) {
            $max_value = $instance['guests_adults-max'];
            //$zero_option = array("", "Select Adults");
            //if ($label = $this->_label($instance['guests_adults-label']))
            $label = $instance['guests_adults-label'];
            if(!empty($label))
                $zero_option = array("", $label);

            $replacements['guests_adults_field'] = ResortPro::dropdown("resortpro_sw_adults", ResortPro::range(1, $max_value), $this->is_var_set('oc', 'intval'), $zero_option);
        }
        if (strpos($template, "%guests_children_field%") !== false) {
            $max_value = $instance['guests_children-max'];
            //$zero_option = array("", "Select Children");
            //if ($label = $this->_label($instance['guests_children-label']))
            $label = $instance['guests_children-label'];
            if(!empty($label))
                $zero_option = array("", $label);
            $replacements['guests_children_field'] = ResortPro::dropdown("resortpro_sw_children", ResortPro::range(0, $max_value), $this->is_var_set('ch', 'intval'), $zero_option);
        }
        if (strpos($template, "%guests_pets_field%") !== false) {
            $max_value = $instance['guests_pets-max'];
            //$zero_option = array("", "Select Pets");
            //if ($label = $this->_label($instance['guests_pets-label']))
            $label = $instance['guests_pets-label'];
            if(!empty($label))
                $zero_option = array("", $label);
            $replacements['guests_pets_field'] = ResortPro::dropdown("resortpro_sw_pets", ResortPro::range(0, $max_value), $this->is_var_set('pets', 'intval'), $zero_option);
        }
        if (strpos($template, "%number_bedrooms_field%") !== false) {
            
            $label = $instance['number_bedrooms-label'];
            if(!empty($label))
                $zero_option = array("", $label);

            $bedrooms = StreamlineCore_Wrapper::get_bedrooms();
        
            if(!empty($label))
                $options = array("" => $label);
            
            foreach ($bedrooms as $bedroom){                
                if(strpos($bedroom->name, 'N/A') === false){
                    $options[(int)$bedroom->value] = (string)$bedroom->name;    
                }                
            }
            
            $replacements['number_bedrooms_field'] = ResortPro::dropdown("resortpro_sw_bedrooms_number", $options, $this->is_var_set('value', 'intval' ) );
        }
        if (strpos($template, "%unit_field%") !== false) {
            $replacements['unit_field'] = '<input type="text" id="resortpro_sw_lodging_unit" placeholder="'.$instance['unit-label'].'"  name="resortpro_sw_lodging_unit" class="resortpro_sw-date form-control" value="' . $this->is_var_set('resortpro_sw_lodging_unit', 'htmlspecialchars' ) . '">';
            if ($label = $this->_label($instance['unit-label']))
                $replacements['unit_field'] = "<label class='resortpro-input'><span>$label</span>{$replacements['unit_field']}</label>";
        }
        if (strpos($template, "%code_field%") !== false) {
            $replacements['code_field'] = '<input type="text" id="resortpro_sw_lodging_code" placeholder="'.$instance['code-label'].'" name="resortpro_sw_lodging_code" class="resortpro_sw-date form-control" value="' . $this->is_var_set('resortpro_sw_lodging_code', 'htmlspecialchars' ) . '">';
            if ($label = $this->_label($instance['code-label']))
                $replacements['code_field'] = "<label class='resortpro-input'><span>$label</span>{$replacements['code_field']}</label>";
        }
        if (strpos($template, "%sorting_order_field%") !== false) {
            $replacements['sorting_order_field'] = "<input type='hidden' name='resortpro_sw_filter' value='{$instance['sorting_order-by']}' />";
        }
        if (strpos($template, "%lodging_type_field%") !== false) {
            $options = array(0 => __( 'Lodging Types', 'streamline-core' ), 3 => __( 'Homes', 'streamline-core' ), "1" => __( 'Resorts', 'streamline-core' ));
            $replacements['lodging_type_field'] = ResortPro::dropdown("resortpro_sw_lodging_type_id", $options, $_REQUEST['lodging_type_id'], null);
        }
        if (strpos($template, "%bedroom_type_field%") !== false) {

            $arr_filter_location_areas = StreamlineCore_Settings::get_options( 'filter_location_areas' );
            
            $arr_areas = array();
            foreach($arr_filter_location_areas as $id => $value){
                $arr_areas[] = $id;
            }

            $params = array();
            if(count($arr_areas) > 0)
                $params['location_area_id_filter'] = implode( ',', $arr_areas);

            $beds = StreamlineCore_Wrapper::get_bedroom_types($params);

            
            $arr_skip_units = explode( ',', StreamlineCore_Settings::get_options( 'property_delete_units' ) );

            
            foreach ($beds as $bed){
                if (!in_array($bed->id, $arr_skip_units))
                    $options[(int)$bed->id] = (string)$bed->property_name;
            }

            $label = $instance['bedroom_type-label'];
            if(!empty($label))
                $zero_option = array("", $label);

            $replacements['bedroom_type_field'] = ResortPro::dropdown("resortpro_sw_bed", $options, $this->is_var_set('resortpro_sw_bed', 'intval'), $zero_option);
        }
        if (strpos($template, "%area_field%") !== false) {
            $areas = StreamlineCore_Wrapper::get_location_areas();
            $label = $instance['area-label'];

            if(!empty($label))
                $options = array("" => $label);

            foreach ($areas as $area)
                $options[(int)$area->id] = (string)$area->name;

            $replacements['area_field'] = ResortPro::dropdown("resortpro_sw_area", $options, $this->is_var_set('area_id', 'intval' ) );
        }
        if (strpos($template, "%neighborhood_field%") !== false) {
            $neighborhoods = StreamlineCore_Wrapper::get_neighborhoods();

            $label = $instance['neighborhood-label'];

            if(!empty($label))
                $options = array("" => $label);

            //$options = array(0 => "All Neighborhoods");
            foreach ($neighborhoods as $neighborhood)
                $options[(int)$neighborhood->id] = (string)$neighborhood->name;
            $replacements['neighborhood_field'] = ResortPro::dropdown("resortpro_sw_neighborhood_id", $options, $this->is_var_set('neighborhood_area_id', 'intval' ) );
        }
        if (strpos($template, "%view_name_field%") !== false) {
            $viewnames = StreamlineCore_Wrapper::get_view_names();
            //$options = array(0 => "Search Views");

            $label = $instance['view_name-label'];

            if(!empty($label))
                $options = array("" => $label);

            foreach ($viewnames as $viewname)
                $options[(string)$viewname->name] = (string)$viewname->name;

            $replacements['view_name_field'] = ResortPro::dropdown("resortpro_sw_view_name", $options, $this->is_var_set('view_name', 'htmlspecialchars' ) );
        }

        if (strpos($template, "%group_type_field%") !== false) {
            $groups = StreamlineCore_Wrapper::get_group_types();
            //$options = array(0 => "Search Group Types");

            $label = $instance['group_type-label'];

            if(!empty($label))
                $options = array("" => $label);

            foreach ($groups as $group)
                $options[(int)$group->id] = (string)$group->name;
            if (function_exists("resortpro_filter_group_types"))
                $options = resortpro_filter_group_types($options);
            $replacements['group_type_field'] = ResortPro::dropdown("resortpro_sw_grp", $options, $this->is_var_set('group_id', 'intval' ) );
        }

        if (strpos($template, "%home_type_field%") !== false) {
            $types = StreamlineCore_Wrapper::get_home_types();
            //$options = array(0 => "All Home Types");

            $label = $instance['home_type-label'];

            if(!empty($label))
                $options = array("" => $label);

            foreach ($types as $type)
                $options[(int)$type->id] = (string)$type->name;
            $replacements['home_type_field'] = ResortPro::dropdown("resortpro_sw_home_type_id", $options, $this->is_var_set('property_type_id', 'intval' ) );
        }

        if (strpos($template, "%location_field%") !== false) {
            $locations = StreamlineCore_Wrapper::get_locations();
            //$options = array(0 => "Search All");


            $label = $instance['location-label'];

            if(!empty($label))
                $options = array("" => $label);

            foreach ($locations as $location)
                $options[(int)$location->id] = (string)$location->name;
            $replacements['location_field'] = ResortPro::dropdown("resortpro_sw_loc", $options, $this->is_var_set('location_id', 'intval' ) );
        }
        if (strpos($template, "%location_resort_field%") !== false) {
            $locationresorts = StreamlineCore_Wrapper::get_location_resorts();
            //$options = array(0 => "All Locations");

            $label = $instance['location_resort-label'];

            if(!empty($label))
                $options = array("" => $label);

            foreach ($locationresorts as $locationresort)
                $options[(int)$locationresort->id] = (string)$locationresort->name;
            $replacements['location_resort_field'] = ResortPro::dropdown("resortpro_sw_ra_id", $options, $this->is_var_set('resort_area_id', 'intval' ) );
        }

        if (strpos($template,"%amenities_field%")!==false)
        {

            $amenities = StreamlineCore_Wrapper::get_amenity_filters();

            $html = '';
            $a_groups = array();
            foreach ($amenities as $amenity) {
                
                if (isset($amenity['group_name']) && !in_array($amenity['group_name'], $a_groups)) {
                    $a_groups[] = $amenity['group_name'];
                }
            }

            foreach ($a_groups as $group) {
                $html .= ' <div class="col-md-12 amenity_group" style="margin-bottom:24px">
                        <label>'.$group.'</label><br />';

                foreach ($amenities as $amenity) {
                    if ($group == $amenity['group_name']) {
                        $html .= '<input type="checkbox" name="resortpro_sw_amenities[]" value="'.$amenity['id'].'"  /> ' . $amenity['name'] . '<br />';
                    }
                }
                $html .= '</div>';
            }

            $replacements['amenities_field'] = $html;
        }

        $replacements['submit_button'] = '';
        $replacements['submit_button_label'] = '';
        $replacements['submit_button_field'] = "<button class=\"{$instance['submit_button-class']}\" type=\"submit\">{$instance['submit_button-text']}</button>";
if( function_exists('kickout') ) kickout( 'instance', $instance );
        $replacements['viewall_button_field'] = "<a class=\"{$instance['viewall_button-class']}\" href=\"{$instance['viewall_button-url']}\">{$instance['viewall_button-text']}</a>";

        foreach ($replacements as $key => $value)
            $template = str_replace("%$key%", $value, $template);
        return $template;
    }

    private function _label($label, $default = '')
    {
        return (substr($label, 0, 1) == '!') ? substr($label, 1) : $default;
    }

    private function is_var_set( $var = '', $sanitize_callback = '' ){

    	if( isset( $_REQUEST ) && isset( $_REQUEST[ $var ] ) ){

    		if( function_exists( $sanitize_callback ) )
    			return call_user_func ( $sanitize_callback, $_REQUEST[ $var ] );

    		return $_REQUEST[ $var ];

    	}

    	return ''; // $_REQUEST[ $var ] is not set
    }
}
