<?php
    /**
     * @package WordPress
     * @subpackage Traveler
     * @since 1.0
     *
     * Class STCabin
     *
     * Created by ShineTheme
     *
     */

    if (!class_exists('STCabin')) {
        class STCabin extends TravelerObject
        {
            protected $post_type = "cruise_cabin";


            function init()
            {
                parent::init();
                $this->init_metabox();
            }

            function init_metabox()
            {
                $this->metabox[] = array(
                    'id'       => 'st_cruise_cabin',
                    'title'    => __('Cabin Detail', ST_TEXTDOMAIN),
                    'desc'     => '',
                    'pages'    => array($this->post_type),
                    'context'  => 'normal',
                    'priority' => 'high',
                    'fields'   => array(
                        array(
                            'label' => __('Cabin Information', ST_TEXTDOMAIN),
                            'id'    => 'cabin_tab',
                            'type'  => 'tab'
                        ), array(
                            'id'        => 'cruise_id',
                            'label'     => __('Cruise', ST_TEXTDOMAIN),
                            'type'      => 'post-select-ajax',
                            'post_type' => 'cruise',
                            'desc'      => __('Select cruise that cabin belong to', ST_TEXTDOMAIN)
                        ),
                        array(
                            'id'           => 'max_adult',
                            'label'        => __('Max adult', ST_TEXTDOMAIN),
                            'type'         => 'numeric-slider',
                            'min_max_step' => '1,14,1',
                            'desc'         => __('Max adult', ST_TEXTDOMAIN)
                        ),
                        array(
                            'id'           => 'max_children',
                            'label'        => __('Max children', ST_TEXTDOMAIN),
                            'type'         => 'numeric-slider',
                            'min_max_step' => '1,14,1',
                            'desc'         => __('Max children', ST_TEXTDOMAIN)
                        ),

                        array(
                            'id'    => 'bed_size',
                            'label' => __('Beb size', ST_TEXTDOMAIN),
                            'type'  => 'text',
                            'desc'  => __('Beb size', ST_TEXTDOMAIN)
                        ),
                        array(
                            'id'    => 'cabin_size',
                            'label' => __('Cabin Size', ST_TEXTDOMAIN),
                            'type'  => 'text',
                            'desc'  => __('Cabin Size', ST_TEXTDOMAIN)
                        ),
                        array(
                            'label'    => __('Features', ST_TEXTDOMAIN),
                            'id'       => 'fetures',
                            'type'     => 'list-item',
                            'desc'     => __('Rental\'s Feature', ST_TEXTDOMAIN),
                            'settings' => array(
                                array(
                                    'id'    => 'number',
                                    'label' => __('Number', ST_TEXTDOMAIN),
                                    'type'  => 'text',
                                    'std'   => 1,
                                    'desc'  => __('Number of feature', ST_TEXTDOMAIN)
                                ),
                                array(
                                    'id'    => 'icon',
                                    'label' => __('Icon', ST_TEXTDOMAIN),
                                    'type'  => 'text',
                                    'desc'  => __('Feature\'s icon', ST_TEXTDOMAIN),
                                )
                            )
                        ),
                        array(
                            'label' => __('Price Information', ST_TEXTDOMAIN),
                            'id'    => 'price_tab',
                            'type'  => 'tab'
                        )
                    , array(
                            'label' => __('Price', ST_TEXTDOMAIN),
                            'id'    => 'price',
                            'type'  => 'text',
                            'desc'  => __('Regular Price', ST_TEXTDOMAIN)
                        )
                    , array(
                            'label' => __('Discount Rate', ST_TEXTDOMAIN),
                            'id'    => 'discount_rate',
                            'type'  => 'text',
                            'desc'  => __('Discount Rate By %', ST_TEXTDOMAIN)
                        ),

                    )
                );
            }

            static function get_price($post_id = false)
            {
                if (!$post_id) $post_id = get_the_ID();

                $price = get_post_meta($post_id, 'price', true);
                $new_price = 0;

                $discount = get_post_meta($post_id, 'discount_rate', true);
                if ($discount) {
                    if ($discount > 100) $discount = 100;

                    $new_price = $price - ($price / 100) * $discount;
                } else {
                    $new_price = $price;
                }

                return $new_price;

            }

            static function get_orgin_price($post_id = false)
            {
                if (!$post_id) $post_id = get_the_ID();

                return $price = get_post_meta($post_id, 'price', true);

            }

            static function is_sale($post_id = false)
            {
                if (!$post_id) $post_id = get_the_ID();
                $discount = get_post_meta($post_id, 'discount_rate', true);

                if ($discount) {
                    return true;
                }

                return false;
            }
        }

        $a = new STCabin();
        $a->init();
    }
