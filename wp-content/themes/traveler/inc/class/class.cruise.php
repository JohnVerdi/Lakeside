<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STCruise
 *
 * Created by ShineTheme
 *
 */

if(!class_exists('STCruise'))
{
    class STCruise extends TravelerObject{
        protected $post_type="cruise";


        function init()
        {
            //parent::init();
            //$this->init_metabox();

            //Filter change layout of rental detail if choose in metabox
            //add_filter('st_cruise_detail_layout',array($this,'custom_rental_layout'));

            //add_filter('template_include', array($this,'choose_search_template'));

            //add Widget Area
            //add_action('widgets_init',array($this,'add_sidebar'));

            //Sidebar Pos for SEARCH
            //add_filter('st_cruise_sidebar',array($this,'change_sidebar'));

            //Filter the search hotel
            //add_action('pre_get_posts',array($this,'change_search_arg'));

            //add_action('save_post', array($this,'update_sale_price'));

            //add_action('init',array($this,'add_to_cart'));
        }
        function get_cart_item_html($item_id){
            return st()->load_template('cruise/cart_item_html',null,array('item_id'=>$item_id));

        }

        function add_to_cart()
        {
//        if(STInput::get('action')=='rental_add_cart'){
//            $this->do_add_to_cart(array(
//                'item_id'=>STInput::get('item_id'),
//                'number_room'=>STInput::get('number_room'),
//                'price'=>STInput::get('price'),
//                'check_in'=>STInput::get('start'),
//                'check_out'=>STInput::get('end'),
//                'adult'             =>STInput::get('adult'),
//                'children'          =>STInput::get('children')
//            ));
//
//            $link=STCart::get_cart_link();
//            wp_safe_redirect($link);
//            die;
//        }

        }
        function do_add_to_cart($array=array()){
            $default=array(
                'item_id'=>'',
                'number_room'=>1,
                'price'=>'',
                'check_in'=>'',
                'check_out'=>'',
                'adult'         =>1,
                'children'      =>0
            );


            extract(wp_parse_args($array,$default));

            $data=array(
                'check_in'=>$check_in,
                'check_out'=>$check_out,
                'currency'=>TravelHelper::get_default_currency('symbol'),
                'adult'                     =>$adult,
                'children'                  =>$children
            );

            STCart::add_cart($item_id,$number_room,$price,$data);

        }

        function update_sale_price($post_id)
        {
            if(get_post_type($post_id)==$this->post_type)
            {
                $price=STRental::get_price($post_id);
                update_post_meta($post_id,'sale_price',$price);
            }
        }

        function get_search_fields()
        {
            $fields=st()->get_option('rental_search_fields');

            return $fields;
        }
        function get_search_adv_fields()
        {
            $fields=st()->get_option('rental_search_advance');

            return $fields;
        }
        static function get_search_fields_name()
        {
            return array(
                'location'=>array(
                    'value'=>'location',
                    'label'=>__('Location',ST_TEXTDOMAIN)
                ),
                'checkin'=>array(
                    'value'=>'checkin',
                    'label'=>__('Check in',ST_TEXTDOMAIN)
                ),
                'checkout'=>array(
                    'value'=>'checkout',
                    'label'=>__('Check out',ST_TEXTDOMAIN)
                ),
                'adult'=>array(
                    'value'=>'adult',
                    'label'=>__('Adult',ST_TEXTDOMAIN)
                ),
                'children'=>array(
                    'value'=>'children',
                    'label'=>__('Children',ST_TEXTDOMAIN)
                ),
                'room_num'=>array(
                    'value'=>'room_num',
                    'label'=>__('Room(s)',ST_TEXTDOMAIN)
                )
            );
        }

        function change_search_arg($query)
        {
            $post_type = get_query_var('post_type');

            $meta_query=array();

            if($query->is_search && $post_type == 'st_rental') {
                $tax = STInput::get('taxonomy');

                if (!empty($tax) and is_array($tax)) {
                    $tax_query = array();
                    foreach ($tax as $key => $value) {
                        if ($value) {
                            $tax_query[] = array(
                                'taxonomy' => $key,
                                'terms' => explode(',', $value),
                                'COMPARE' => "IN"
                            );
                        }
                    }

                    $query->set('tax_query', $tax_query);
                }

                if ($location_id = STInput::get('location_id')) {
                    $meta_query[] = array(
                        'key' => 'location_id',
                        'value' => $location_id
                    );

                    $query->set('s', '');
                }
                if ($orderby = STInput::get('orderby')) {
                    switch ($orderby) {
                        case "price_asc":
                            $query->set('meta_key', 'sale_price');
                            $query->set('orderby', 'meta_value');
                            $query->set('order', 'asc');

                            break;
                        case "price_desc":
                            $query->set('meta_key', 'sale_price');
                            $query->set('orderby', 'meta_value');
                            $query->set('order', 'desc');

                            break;
                        case "avg_rate":
                            $query->set('meta_key', 'rate_review');
                            $query->set('orderby', 'meta_value');
                            $query->set('order', 'desc');

                            break;
                    }
                }

                if ($star = STInput::get('star_rate')) {
                    $meta_query[] = array(
                        'key' => 'rate_review',
                        'value' => explode(',', $star),
                        'compare' => "IN"
                    );
                }
                if ($price = STInput::get('price_range')) {
                    $priceobj = explode(';', $price);
                    $meta_query[] = array(
                        'key' => 'sale_price',
                        'value' => $priceobj[0],
                        'compare' => '>=',
                        'type' => "NUMERIC"
                    );
                    if (isset($priceobj[1])) {
                        $meta_query[] = array(
                            'key' => 'sale_price',
                            'value' => $priceobj[1],
                            'compare' => '<=',
                            'type' => "NUMERIC"
                        );
                    }

                    $meta_query['relation'] = 'and';
                }
                if (!empty($meta_query)) {
                    $query->set('meta_query', $meta_query);
                }
            }

        }
        function change_sidebar()
        {
            return st()->get_option('rental_sidebar_pos','left');
        }

        function choose_search_template($template)
        {
            global $wp_query;
            $post_type = get_query_var('post_type');
            if( $wp_query->is_search && $post_type == $this->post_type )
            {
                return locate_template('search-cruise.php');  //  redirect to archive-search.php
            }
            return $template;
        }
        function add_sidebar()
        {
//            register_sidebar( array(
//                'name' => __( 'Cruise Sidebar', ST_TEXTDOMAIN ),
//                'id' => 'cruise-sidebar',
//                'description' => __( 'Widgets in this area will be shown on Cruise', ST_TEXTDOMAIN),
//                'before_title' => '<h4>',
//                'after_title' => '</h4>',
//                'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
//                'after_widget'  => '</div>',
//            ) );
        }



        function  get_result_string()
        {
            global $wp_query;
            $result_string='';

            if($wp_query->found_posts){
                $result_string.=sprintf(_n('%s vacation rental','%s vacation rentals',$wp_query->found_posts,ST_TEXTDOMAIN),$wp_query->found_posts);
            }else{
                $result_string.=__('No rental found',ST_TEXTDOMAIN);
            }

            $location_id=STInput::get('location_id');
            if($location_id and $location=get_post($location_id))
            {
                $result_string.=' in '.get_the_title($location_id);
            }

            $start=STInput::get('start');
            $end=STInput::get('end');

            $start=strtotime($start);

            $end=strtotime($end);

            if($start and $end)
            {
                $result_string.=__(' on ',ST_TEXTDOMAIN).date('M d',$start).' - '.date('M d',$end);
            }

            if($adult_num=STInput::get('adult_number')){
                if($adult_num>1){
                    $result_string.=sprintf(__(' for %s adults',ST_TEXTDOMAIN),$adult_num);
                }else{

                    $result_string.=sprintf(__(' for %s adult',ST_TEXTDOMAIN),$adult_num);
                }

            }

            return $result_string;
        }


        function custom_rental_layout($old_layout_id=false)
        {
            if(is_singular($this->post_type))
            {
                $meta=get_post_meta(get_the_ID(),'layout_id',true);

                if($meta)
                {
                    return $meta;
                }
            }
            return $old_layout_id;
        }

        function get_near_by($post_id=false,$range=20,$limit=5)
        {
            return parent::get_near_by($post_id,$range,$limit);
        }
        function get_review_stats()
        {
            $review_stat=st()->get_option('rental_review_stats');

            return $review_stat;
        }

        function init_metabox()
        {
            $this->metabox[] = array(
                'id'          => 'st_cruise',
                'title'       => __( 'Cruise Detail', ST_TEXTDOMAIN),
                'desc'        => '',
                'pages'       => array( $this->post_type ),
                'context'     => 'normal',
                'priority'    => 'high',
                'fields'      => array(
                    array(
                        'label'       => __( 'Cruise Information', ST_TEXTDOMAIN),
                        'id'          => 'cruise_tab',
                        'type'        => 'tab'
                    ),
                    array(
                        'id'                    =>'layout_id',
                        'label'                 =>__('Custom Layout',ST_TEXTDOMAIN),
                        'type'                  =>'custom-post-type-select',
                        'desc'                  =>__("Set as Featured Cruise",ST_TEXTDOMAIN),
                        'post_type'             =>'st_layouts'
                    ),array(
                        'id'                    =>'is_feature',
                        'label'                 =>__('Set as Featured',ST_TEXTDOMAIN),
                        'type'                  =>'on-off',
                        'desc'                  =>__("Set as Featured Cruise",ST_TEXTDOMAIN),
                        'std'                   =>'off'
                    ),
                    array(
                        'id'                    =>'st_children_free',
                        'label'                 =>__('Number of children stay free',ST_TEXTDOMAIN),
                        'type'                  =>'numeric-slider',
                        'min_max_step'            =>'0,14,1',
                        'desc'                  =>__('Number of children stay free',ST_TEXTDOMAIN)
                    ),
                    array(
                        'id'                    =>'st_is_price_per_persion',
                        'label'                 =>__('Price per person?',ST_TEXTDOMAIN),
                        'type'                  =>'on-off',
                        'desc'                  =>__('Price per person?',ST_TEXTDOMAIN)
                    ),
                    array(
                        'id'                    =>'gallery',
                        'label'                 =>__('Gallery',ST_TEXTDOMAIN),
                        'type'                  =>'gallery',
                        'desc'                  =>__('Gallery',ST_TEXTDOMAIN)
                    ),
                    array(
                        'id'                    =>'video',
                        'label'                 =>__('Video',ST_TEXTDOMAIN),
                        'type'                  =>'text',
                        'desc'                  =>__('Video',ST_TEXTDOMAIN)
                    ),

                    array(
                        'id'                    =>'programes',
                        'label'                 =>__('Programes',ST_TEXTDOMAIN),
                        'type'                  =>'list-item',
                        'settings'              =>array(
                            array(
                                'id'                    =>'desc',
                                'label'                 =>__('Description',ST_TEXTDOMAIN),
                                'type'                  =>'textarea'
                            )
                        )
                    ),
                    array(
                        'label'       => __( 'Location Information', ST_TEXTDOMAIN),
                        'id'          => 'location_tab',
                        'type'        => 'tab'
                    ),
                    array(
                        'id'                    =>'location_id',
                        'label'                 =>__('Location',ST_TEXTDOMAIN),
                        'type'                  =>'post-select-ajax',
                        'post_type'             =>'location',
                        'desc'                  =>__('Type location name to search',ST_TEXTDOMAIN)
                    ),
                    array(
                        'id'                    =>'address',
                        'label'                 =>__('Address',ST_TEXTDOMAIN),
                        'type'                  =>'text',
                        'desc'                  =>__('Cruise\'s address ',ST_TEXTDOMAIN)
                    ),
                    array(
                        'label'       => __( 'Latitude', ST_TEXTDOMAIN),
                        'id'          => 'map_lat',
                        'type'        => 'text',
                        'desc'        => __( 'Latitude <a href="http://www.latlong.net/" target="_blank">Get here</a>', ST_TEXTDOMAIN),
                    ),

                    array(
                        'label'       => __( 'Longitude', ST_TEXTDOMAIN),
                        'id'          => 'map_lng',
                        'type'        => 'text',
                        'desc'        => __( 'Longitude', ST_TEXTDOMAIN),
                    ),

                    array(
                        'label'       => __( 'Map Zoom', ST_TEXTDOMAIN),
                        'id'          => 'map_zoom',
                        'type'        => 'text',
                        'desc'        => __( 'Map Zoom', ST_TEXTDOMAIN),
                        'std'         =>13
                    ),
                    array(
                        'label'       => __( 'Contact Infomaiton', ST_TEXTDOMAIN),
                        'id'          => 'contact_tab',
                        'type'        => 'tab'
                    ),array(
                        'label'       => __( 'Cruise Email', ST_TEXTDOMAIN),
                        'id'          => 'email',
                        'type'        => 'text',
                        'desc'        => __( 'Cruise Email Address', ST_TEXTDOMAIN),
                    ),

                    array(
                        'label'       => __( 'Cruise Website', ST_TEXTDOMAIN),
                        'id'          => 'website',
                        'type'        => 'text',
                        'desc'        => __( 'Cruise Website. Ex: <em>http://domain.com</em>', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label'       => __( 'Cruise Phone', ST_TEXTDOMAIN),
                        'id'          => 'phone',
                        'type'        => 'text',
                        'desc'        => __( 'Cruise Phone Number', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label'       => __( 'Cruise Fax Number', ST_TEXTDOMAIN),
                        'id'          => 'fax',
                        'type'        => 'text',
                        'desc'        => __( 'Cruise Fax Number', ST_TEXTDOMAIN),
                    ),

                )
            );
        }

        static function search_room($param=array())
        {

            $default=array(
                'cruise_id'=>false,
                'adult_number'=>false,
                'child_number'=>false,
                'cabin_type'=>0
            );

            $page=STInput::request('paged');
            if(!$page)
            {
                $page=get_query_var('paged');
            }

            extract(wp_parse_args($param,$default));

            $arg=array(
                'post_type'=>'cruise_cabin',
                'posts_per_page'=>'15',
                'paged'=>$page
            );


            if($adult_num)
            {
                $arg['meta_query'][]=array(

                    'key'=>'adult_number',
                    'compare'=>'=',
                    'value'=>$adult_num,
                );
            }
            if($child_num===0 or $child_num)
            {
                $arg['meta_query'][]=array(

                    'key'=>'children_number',
                    'compare'=>'=',
                    'value'=>$child_num,


                );
            }


            if($cabin_id)
            {
                $arg['meta_key']='cruise_id';
                $arg['meta_value']=$room_parent;
            }

            if($cabin_type)
            {
                $arg['tax_query'][]=array(
                    'taxonomy'=>'cabin_type',
                    'terms'=>$cabin_type
                );
            }



            return $arg;
        }


    }
    $a=new STCruise();
    $a->init();
}
