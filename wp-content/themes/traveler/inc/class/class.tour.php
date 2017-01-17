<?php
    /**
     * @package WordPress
     * @subpackage Traveler
     * @since 1.0
     *
     * Class STTour
     *
     * Created by ShineTheme
     *
     */
    if(!class_exists('STTour'))
    {
        class STTour extends TravelerObject
        {
			static $_inst;
            protected $post_type="st_tours";
            protected $orderby;
            /**
             * @var string
             * @since 1.1.7
             */
            protected $template_folder='tours';
            function __construct($tours_id=false)
            {
                $this->orderby=array(
                    'new'=>array(
                        'key'=>'new',
                        'name'=>__('New',ST_TEXTDOMAIN)
                    ),
                    'price_asc'=>array(
                        'key'=>'price_asc',
                        'name'=>__('Price (low to high)',ST_TEXTDOMAIN)
                    ),
                    'price_desc'=>array(
                        'key'=>'price_desc',
                        'name'=>__('Price (high to low)',ST_TEXTDOMAIN)
                    ),
                    'name_a_z'=>array(
                        'key'=>'name_a_z',
                        'name'=>__('Tours Name (A-Z)',ST_TEXTDOMAIN)
                    ),
                    'name_z_a'=>array(
                        'key'=>'name_z_a',
                        'name'=>__('Tours Name (Z-A)',ST_TEXTDOMAIN)
                    )
                );

            }
            public function getOrderby()
            {
                return $this->orderby;
            }

            /**
             * @since 1.1.7
             * @param $type
             * @return string
             */
            function _get_post_type_icon($type)
            {
                return  "fa fa-flag-o";
            }

            /**
             *
             *
             * @update 1.1.3
             * */

            function init()
            {
               if(!$this->is_available()) return;
                parent::init();

                add_filter('st_tours_detail_layout',array($this,'custom_tour_layout'));

                // add to cart
                add_action('wp_loaded',array($this,'tours_add_to_cart'),20);

                //custom search cars template
                add_filter('template_include', array($this,'choose_search_template'));

                //Sidebar Pos for SEARCH
                add_filter('st_tours_sidebar', array($this, 'change_sidebar'));

                //Save car Review Stats
                add_action( 'comment_post' , array( $this , '_save_review_stats' ) );

                // Change cars review arg
                add_filter( 'st_tours_wp_review_form_args' , array( $this , 'comment_args' ) , 10 , 2 );

                //Filter the search tour
               //add_action('pre_get_posts',array($this,'change_search_tour_arg'));

                //add Widget Area
                add_action('widgets_init',array($this,'add_sidebar'));
                add_filter('st_search_preload_page',array($this,'_change_preload_search_title'));

                //add_filter('st_data_custom_price',array($this,'_st_data_custom_price'));


                // Woocommerce cart item information
                add_action('st_wc_cart_item_information_st_tours',array($this,'_show_wc_cart_item_information'));
                add_action( 'st_wc_cart_item_information_btn_st_tours' , array( $this , '_show_wc_cart_item_information_btn' ) );
                add_action('st_before_cart_item_st_tours',array($this,'_show_wc_cart_post_type_icon'));


                add_filter('st_add_to_cart_item_st_tours', array($this, '_deposit_calculator'), 10, 2);
                if(is_singular('st_tours')){
                    add_action('wp_enqueue_scripts',array($this,'add_scripts'));
                }

            }

            /**
             * @since 1.1.9
             * @param $comment_id
             */
            function _save_review_stats($comment_id){
                $comemntObj = get_comment( $comment_id );
                $post_id    = $comemntObj->comment_post_ID;

                if(get_post_type( $post_id ) == 'st_tours') {
                    $all_stats       = $this->get_review_stats();
                    $st_review_stats = STInput::post( 'st_review_stats' );

                    if(!empty( $all_stats ) and is_array( $all_stats )) {
                        $total_point = 0;
                        foreach( $all_stats as $key => $value ) {
                            if(isset( $st_review_stats[ $value[ 'title' ] ] )) {
                                $total_point += $st_review_stats[ $value[ 'title' ] ];
                                //Now Update the Each Stat Value
                                update_comment_meta( $comment_id , 'st_stat_' . sanitize_title( $value[ 'title' ] ) , $st_review_stats[ $value[ 'title' ] ] );
                            }
                        }

                        $avg = round( $total_point / count( $all_stats ) , 1 );

                        //Update comment rate with avg point
                        $rate = wp_filter_nohtml_kses( $avg );
                        if($rate > 5) {
                            //Max rate is 5
                            $rate = 5;
                        }
                        update_comment_meta( $comment_id , 'comment_rate' , $rate );
                        //Now Update the Stats Value
                        update_comment_meta( $comment_id , 'st_review_stats' , $st_review_stats );
                    }

                    if(STInput::post( 'comment_rate' )) {
                        update_comment_meta( $comment_id , 'comment_rate' , STInput::post( 'comment_rate' ) );

                    }
                    //review_stats
                    $avg = STReview::get_avg_rate( $post_id );

                    update_post_meta( $post_id , 'rate_review' , $avg );
                }



            }
            /**
             *
             *
             * @since 1.1.9 
             * */
            function change_sidebar($sidebar = FALSE)
            {
                return st()->get_option('tour_sidebar_pos', 'left');
            }
            /**
             * @since 1.1.9
             * @return bool
             */
            function get_review_stats()
            {
                $review_stat = st()->get_option( 'tour_review_stats' );

                return $review_stat;
            }

            /**
             * @since 1.1.9
             * @param $comment_form
             * @param bool $post_id
             * @return mixed
             */
            function comment_args( $comment_form , $post_id = false )
            {
                /*since 1.1.0*/

                if(!$post_id)
                    $post_id = get_the_ID();
                if(get_post_type( $post_id ) == 'st_tours') {
                    $stats = $this->get_review_stats();

                    if($stats and is_array( $stats )) {
                        $stat_html = '<ul class="list booking-item-raiting-summary-list stats-list-select">';

                        foreach( $stats as $key => $value ) {
                            $stat_html .= '<li class=""><div class="booking-item-raiting-list-title">' . $value[ 'title' ] . '</div>
                                                    <ul class="icon-group booking-item-rating-stars">
                                                    <li class=""><i class="fa fa-smile-o"></i>
                                                    </li>
                                                    <li class=""><i class="fa fa-smile-o"></i>
                                                    </li>
                                                    <li class=""><i class="fa fa-smile-o"></i>
                                                    </li>
                                                    <li class=""><i class="fa fa-smile-o"></i>
                                                    </li>
                                                    <li><i class="fa fa-smile-o"></i>
                                                    </li>
                                                </ul>
                                                <input type="hidden" class="st_review_stats" value="0" name="st_review_stats[' . $value[ 'title' ] . ']">
                                                    </li>';
                        }
                        $stat_html .= '</ul>';


                        $comment_form[ 'comment_field' ] = "
                        <div class='row'>
                            <div class=\"col-sm-8\">
                    ";
                        $comment_form[ 'comment_field' ] .= '<div class="form-group">
                                            <label>' . __( 'Review Title' , ST_TEXTDOMAIN ) . '</label>
                                            <input class="form-control" type="text" name="comment_title">
                                        </div>';

                        $comment_form[ 'comment_field' ] .= '<div class="form-group">
                                            <label>' . __( 'Review Text',ST_TEXTDOMAIN ) . '</label>
                                            <textarea name="comment" id="comment" class="form-control" rows="6"></textarea>
                                        </div>
                                        </div><!--End col-sm-8-->
                                        ';

                        $comment_form[ 'comment_field' ] .= '<div class="col-sm-4">' . $stat_html . '</div></div><!--End Row-->';
                    }
                }

                return $comment_form;
            }
            /**
             *
             *
             * @since 1.1.1
             * */
            function _show_wc_cart_item_information($st_booking_data=array()){
                echo st()->load_template('tours/wc_cart_item_information',false,array('st_booking_data'=>$st_booking_data));
            }
            /**
             *
             *
             * @since 1.1.1
             * */
            function _show_wc_cart_post_type_icon()
            {
                echo '<span class="booking-item-wishlist-title"><i class="fa fa-flag-o"></i> '.__('tour',ST_TEXTDOMAIN).' <span></span></span>';
            }


            function _st_data_custom_price(){
                return array('title'=>'Price Custom Settings','post_type'=>'st_tours');
            }

            /**
             *
             *
             * @update 1.1.1
             * */
            static function get_search_fields_name()
            {
                return array(/*
                    'google_map_location' => array(
                        'value' => 'google_map_location',
                        'label' => __('Google Map Location', ST_TEXTDOMAIN)
                    ),*/
                    'address'=>array(
                        'value'=>'address',
                        'label'=>__('Location',ST_TEXTDOMAIN)
                    ),/*
                    'address-2'=>array(
                        'value'=>'address-2',
                        'label'=>__('Address (geobytes.com)',ST_TEXTDOMAIN)
                    ),*/
                    'people'=>array(
                        'value'=>'people',
                        'label'=>__('People',ST_TEXTDOMAIN)
                    ),
                    'check_in'=>array(
                        'value'=>'check_in',
                        'label'=>__('Departure date',ST_TEXTDOMAIN)
                    ),
                    'check_out'=>array(
                        'value'=>'check_out',
                        'label'=>__('Arrival Date',ST_TEXTDOMAIN)
                    ),
                    'taxonomy'=>array(
                        'value'=>'taxonomy',
                        'label'=>__('Taxonomy',ST_TEXTDOMAIN)
                    ),
                    'list_location'=>array(
                        'value'=>'list_location',
                        'label'=>__('Location List',ST_TEXTDOMAIN)
                    ),/*
                    'duration'=>array(
                        'value'=>'duration',
                        'label'=>__('Duration',ST_TEXTDOMAIN)
                    ),
                    'duration-dropdown'=>array(
                        'value'=>'duration-dropdown',
                        'label'=>__('Duration Dropdown',ST_TEXTDOMAIN)
                    ),*/
                    'item_name'=>array(
                        'value'=>'item_name',
                        'label'=>__('Tour Name',ST_TEXTDOMAIN)
                    ),
                    'list_name'=>array(
                        'value'=>'list_name',
                        'label'=>__('List Name',ST_TEXTDOMAIN)
                    ),
                    'price_slider'=>array(
                        'value'=>'price_slider',
                        'label'=>__('Price slider ',ST_TEXTDOMAIN)
                    )

                );
            }
            function _change_preload_search_title($return)
            {
                if( get_query_var('post_type')=='st_tours')
                {
                    $return=__(" Tours in %s",ST_TEXTDOMAIN);

                    if(STInput::get('location_id'))
                    {
                        $return=sprintf($return,get_the_title(STInput::get('location_id')));
                    }elseif(STInput::get('location_name')){
                        $return=sprintf($return,STInput::get('location_name'));
                    }elseif(STInput::get('address')){
                        $return=sprintf($return,STInput::get('address'));
                    }else {
                        $return=__(" Tours",ST_TEXTDOMAIN);
                    }

                    $return.='...';
                }





                return $return;
            }

            function add_sidebar()
            {
                register_sidebar( array(
                    'name' => __( 'Tours Search Sidebar 1', ST_TEXTDOMAIN ),
                    'id' => 'tours-sidebar',
                    'description' => __( 'Widgets in this area will be shown on Tours', ST_TEXTDOMAIN),
                    'before_title' => '<h4>',
                    'after_title' => '</h4>',
                    'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
                    'after_widget'  => '</div>',
                ) );


                register_sidebar( array(
                    'name' => __( 'Tour Single Sidebar', ST_TEXTDOMAIN ),
                    'id' => 'tour-single-sidebar',
                    'description' => __( 'Widgets in this area will be shown on all tour.', ST_TEXTDOMAIN),
                    'before_title' => '<h4>',
                    'after_title' => '</h4>',
                    'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
                    'after_widget'  => '</div>',
                ) );
            }

            /**
            *@since 1.1.7
            **/
            function search_distinct() {
                return "DISTINCT";
            }
            /**
             *
             *
             *@since 1.1.3
             *
             * */
            function _alter_search_query($where)
            {
                global $wpdb;

                // only daily tour
                // check if tour search result
//                $where.=" AND {$wpdb->posts}.ID in
//                            (SELECT post_id
//                                from $wpdb->postmeta
//                                join $wpdb->posts as mt10 on  {$wpdb->postmeta}.post_id  = mt10.ID
//                                where meta_key='type_tour' and meta_value='daily_tour'
//                                and mt10.post_type = 'st_tours'
//                                )";

                return $where;
            }

            /**
             * @since 1.1.7
             * @param $JOIN
             * @return string
             */
            function _alter_join_query($JOIN)
            {
                return $JOIN;
                global $wpdb;
                //$JOIN.="LEFT JOIN $wpdb->postmeta as st_meta1 on st_meta1.post_id=$wpdb->posts.ID and st_meta1.meta_key='type_tour' and st_meta1.meta_key='daily_tour'";
                return $JOIN;
            }

            function _get_join_query($join)
            {
                if(!TravelHelper::checkTableDuplicate('st_tours')) return $join;
                
                global $wpdb;

                $table = $wpdb->prefix.'st_tours';

                $join .= " INNER JOIN {$table} as tb ON {$wpdb->prefix}posts.ID = tb.post_id";

                return $join;
            }
            /**
            * @update 1.1.8
            *
            */
            function _get_where_query_tab_location($where){
                $location_id = get_the_ID();
                if(!TravelHelper::checkTableDuplicate('st_tours')) return $where;
                if(!empty( $location_id )) {

                    $where = TravelHelper::_st_get_where_location($location_id,array('st_tours'),$where);

                }
                return $where;            
            }
            function _get_where_query($where)
            {
                if(!TravelHelper::checkTableDuplicate('st_tours')) return $where;

				global $wpdb,$st_search_args;
				if(!$st_search_args) $st_search_args=$_REQUEST;

				/**
				 * Merge data with element args with search args
				 * @since 1.2.4
				 * @author dungdt
				 */
				if(!empty($st_search_args['st_location'])){
					if(empty($st_search_args['only_featured_location']) or $st_search_args['only_featured_location']=='no')
						$st_search_args['location_id']=$st_search_args['st_location'];
				}

                if( isset( $st_search_args['location_id'] ) && !empty( $st_search_args['location_id']) ){
                    $location_id = $st_search_args[ 'location_id'];

                    $where = TravelHelper::_st_get_where_location( $location_id, array('st_tours'), $where );
                }elseif( isset( $_REQUEST['location_name'] ) && !empty( $_REQUEST['location_name'] ) ){
                    $location_name = STInput::request('location_name', '');

                    $ids_location = TravelerObject::_get_location_by_name($location_name);
                    
                    if( !empty( $ids_location) && is_array( $ids_location ) ){
                        $where .= TravelHelper::_st_get_where_location( $ids_location, array('st_hotel') , $where);
                    }else{
                        $where .= " AND (tb.address LIKE '%{$location_name}%'";
                        $where .= " OR {$wpdb->prefix}posts.post_title LIKE '%{$location_name}%')";
                    }
                }

                if( isset( $_REQUEST['item_name'] ) && !empty( $_REQUEST['item_name'] ) ){
                    $item_name = STInput::request('item_name', '');
                    $where .= " AND {$wpdb->prefix}posts.post_title LIKE '%{$item_name}%'";
                }

                if( isset( $_REQUEST['item_id'] ) and !empty( $_REQUEST['item_id'] ) ){
                    $item_id = STInput::request('item_id', '');
                    $where .= " AND ({$wpdb->prefix}posts.ID = '{$item_id}')";
                }

                if(isset($_REQUEST['people']) && !empty($_REQUEST['people'])){
                    $people = STInput::get('people', 1);
                    $where .= " AND (tb.max_people >= {$people})";
                }

                /*if(isset($_REQUEST['duration']) && !empty($_REQUEST['duration'])){
                    $duration = intval(STInput::get('duration', 1));
                    $today = time();

                    $where .= "AND (
                        (
                            tb.duration_day = {$duration}
                            AND tb.type_tour = 'daily_tour'
                        )
                    )";
                }*/

                $start = STInput::request("start") ; 
                $end = STInput::request("end") ; 
                if( !empty($start) &&  !empty($end)) {
                    $today = date('Y-m-d');
                    $check_in = date('Y-m-d',strtotime(TravelHelper::convertDateFormat(STInput::request("start"))));
                    $period = TravelHelper::dateDiff($today, $check_in);
                    if($period<0)$period=0;
                    $check_out = date('Y-m-d',strtotime(TravelHelper::convertDateFormat(STInput::request("end"))));
                    $list_date = TourHelper::_tourValidate($check_in);
                    //$list_date = $this->get_unavailable_tour($check_in,$check_out,STInput::get('people',1));
                    if(is_array($list_date) && count($list_date)){
                        $list = implode(',', $list_date);
						$where .= " AND {$wpdb->posts}.ID NOT IN ({$list})";
                    }
                }
                if(isset($_REQUEST['star_rate']) && !empty($_REQUEST['star_rate'])){
                    $stars = STInput::get('star_rate', 1);
                    $stars = explode(',', $stars);
                    $all_star = array();
                    if (!empty($stars) && is_array($stars)) {
                        foreach ($stars as $val) {
                            for($i = $val; $i < $val + 0.9; $i += 0.1){
                                if ($i){
                                    $all_star[] = $i;
                                }                                
                            }
                        }
                    }
                    
                    $list_star = implode(',', $all_star);
                    if ($list_star) {
                        $where .= " AND (tb.rate_review IN ({$list_star}))";
                    }
                }
                if( isset($_REQUEST['range']) and isset($_REQUEST['location_id']) ){
                    $range = STInput::get('range', '0;5');
                    $rangeobj = explode(';', $range);
                    $range_min = $rangeobj[0];
                    $range_max = $rangeobj[1];
                    $location_id = STInput::request('location_id');
                    $post_type = get_query_var( 'post_type' );
                    $map_lat   = (float)get_post_meta( $location_id , 'map_lat' , true );
                    $map_lng   = (float)get_post_meta( $location_id , 'map_lng' , true );
                    global $wpdb;
                    $where .= "
                    AND $wpdb->posts.ID IN (
                            SELECT ID FROM (
                                SELECT $wpdb->posts.*,( 6371 * acos( cos( radians({$map_lat}) ) * cos( radians( mt1.meta_value ) ) *
                                                cos( radians( mt2.meta_value ) - radians({$map_lng}) ) + sin( radians({$map_lat}) ) *
                                                sin( radians( mt1.meta_value ) ) ) ) AS distance
                                                    FROM $wpdb->posts, $wpdb->postmeta as mt1,$wpdb->postmeta as mt2
                                                    WHERE $wpdb->posts.ID = mt1.post_id
                                                    and $wpdb->posts.ID=mt2.post_id
                                                    AND mt1.meta_key = 'map_lat'
                                                    and mt2.meta_key = 'map_lng'
                                                    AND $wpdb->posts.post_status = 'publish'
                                                    AND $wpdb->posts.post_type = '{$post_type}'
                                                    AND $wpdb->posts.post_date < NOW()
                                                    GROUP BY $wpdb->posts.ID HAVING distance >= {$range_min} and distance <= {$range_max}
                                                    ORDER BY distance ASC
                            ) as st_data
                    )";
                }


				/**
				 * Change Where for Element List
				 * @since 1.2.4
				 * @author dungdt
				 */

                if(!empty($st_search_args['only_featured_location']) and !empty($st_search_args['featured_location']) ){
                    $featured=$st_search_args['featured_location'];
                    if($st_search_args['only_featured_location'] == 'yes' and is_array($featured)) {

						if(is_array( $featured ) && count( $featured )) {
							$where .= " AND (";
							$where_tmp = "";
							foreach( $featured as $item ) {
								if(empty( $where_tmp )) {
									$where_tmp .= " tb.multi_location LIKE '%_{$item}_%'";
								} else {
									$where_tmp .= " OR tb.multi_location LIKE '%_{$item}_%'";
								}
							}
							$featured = implode( ',' , $featured );
							$where_tmp .= " OR tb.id_location IN ({$featured})";
							$where .= $where_tmp . ")";
						}
					}
				}

                return $where;
            }

			/**
			 * @since 1.2.0
			 */
			function get_unavailable_tour($check_in,$check_out = '',$people=1)
			{
				if(!$people) $people=1;
				$check_in=strtotime($check_in);
				$check_out=strtotime($check_out);
				global $wpdb;
				$query="SELECT
					post_id,
					{$wpdb->prefix}st_tours.max_people,
					{$wpdb->prefix}st_order_item_meta.adult_number+{$wpdb->prefix}st_order_item_meta.child_number+{$wpdb->prefix}st_order_item_meta.infant_number as total_booked
				FROM
					{$wpdb->prefix}st_tours
				JOIN {$wpdb->prefix}st_order_item_meta ON {$wpdb->prefix}st_tours.post_id = {$wpdb->prefix}st_order_item_meta.st_booking_id
				AND {$wpdb->prefix}st_order_item_meta.st_booking_post_type = 'st_tours'
				WHERE
					1 = 1
				AND
					(
						(
							{$wpdb->prefix}st_order_item_meta.check_in_timestamp <= {$check_in}
							AND {$wpdb->prefix}st_order_item_meta.check_out_timestamp >= {$check_out}
						)
						OR (
							{$wpdb->prefix}st_order_item_meta.check_in_timestamp >= {$check_in}
							AND {$wpdb->prefix}st_order_item_meta.check_in_timestamp <= {$check_out}
						)
					)
				GROUP BY post_id
				HAVING  {$wpdb->prefix}st_tours.max_people - total_booked <{$people}
				LIMIT 0,500";
				$res=$wpdb->get_results($query, ARRAY_A);

				$r=array();
				if(!is_wp_error($res))
				{
					foreach($res as $key=>$value)
					{
						$r[]=$value['post_id'];
					}
				}

				$unavailable="SELECT
						post_id
					FROM
						{$wpdb->prefix}st_availability
					WHERE
						1 = 1
					AND (
						check_in >= {$check_in}
						AND check_out <= {$check_out}
						AND `status` = 'unavailable'
					)
					AND post_type='st_tours'";

				$res=$wpdb->get_results($unavailable, ARRAY_A);
				if(!is_wp_error($res))
				{
					foreach($res as $key=>$value)
					{
						$r[]=$value['post_id'];
					}
				}


				return $r;

			}

            /**
             *  since 1.2.3
             */
            static function _get_order_by_query($orderby){
                if($check = STInput::get( 'orderby' )) {
                    global $wpdb;
                    switch( $check ) {
                        case "price_asc":
                            $orderby = ' CAST(st_tour_price as DECIMAL) asc';
                            break;
                        case "price_desc":
                            $orderby = ' CAST(st_tour_price as DECIMAL) desc';
                            break;
                        case "name_a_z":
                            $orderby = $wpdb->posts.'.post_title';
                            break;
                        case "name_z_a":
                            $orderby = $wpdb->posts.'.post_title desc';
                            break;
                        case "new":
                            $orderby = $wpdb->posts.'.post_modified desc';
                            break;
                    }
                }
                global $st_search_args;
                if(!empty($st_search_args['st_orderby'])){
                    $check = $st_search_args['st_orderby'];
                    $order = $st_search_args['st_order'];
                    switch( $check ) {
                        case "sale":
                            $orderby = ' CAST(st_tour_price as DECIMAL) '.$order;
                            break;
                    }
                }
                return $orderby;
            }

            function alter_search_query(){
                add_action('pre_get_posts',array($this,'change_search_tour_arg'));
                add_filter('posts_where', array($this, '_get_where_query'));
                add_filter('posts_join', array($this, '_get_join_query'));
                add_filter('posts_fields', array($this, '_get_select_query'));
                add_filter('posts_orderby', array($this , '_get_order_by_query'));
                add_filter( 'posts_clauses', array($this , '_get_query_clauses') );
            }

            function remove_alter_search_query()
            {
                remove_action('pre_get_posts',array($this,'change_search_tour_arg'));
                remove_filter('posts_where', array($this, '_get_where_query'));
                remove_filter('posts_join', array($this, '_get_join_query'));
                remove_filter('posts_fields', array($this, '_get_select_query'));
                remove_filter('posts_orderby', array($this , '_get_order_by_query'));
                remove_filter( 'posts_clauses', array($this , '_get_query_clauses') );
            }
            /**
             *
             *
             *@since 1.2.4
             */
            function _get_query_clauses($clauses){
                if(STAdminTours::check_ver_working() == false) return $clauses;
                $post_type = get_query_var('post_type');
                if($post_type == 'st_tours'){
                    global $wpdb;
                    if(isset($_REQUEST['price_range']) ) {
                        if(empty($clauses['groupby'])){
                            $clauses['groupby'] = $wpdb->posts.".ID";
                        }
                        $price = STInput::get( 'price_range' , '0;0');
                        $priceobj      = explode( ';' , $price );

                        $priceobj[0]=TravelHelper::convert_money_to_default($priceobj[0]);
                        $priceobj[1]=TravelHelper::convert_money_to_default($priceobj[1]);

                        $min_range = $priceobj[0] ;
                        $max_range = $priceobj[1] ;
                        $clauses['groupby'] .= " HAVING CAST(st_tour_price AS DECIMAL) >= {$min_range} AND CAST(st_tour_price AS DECIMAL) <= {$max_range}";
                    }
                }
                return $clauses;
            }
            /**
             *
             *
             *@since 1.2.4
             */
            function _get_select_query($query){
                if(STAdminTours::check_ver_working() == false) return $query;
                $post_type = get_query_var('post_type');
                if($post_type == 'st_tours')
                {
                    $query .=",CASE
						WHEN tb.adult_price > 0 and tb.adult_price != ''
							THEN
								CASE
										WHEN tb.is_sale_schedule = 'on'
												AND tb.discount != 0 AND tb.discount != ''
												AND tb.sale_price_from <= CURDATE() AND tb.sale_price_to >= CURDATE()
										THEN
												CAST(tb.adult_price AS DECIMAL) - ( CAST(tb.adult_price AS DECIMAL) / 100 ) * CAST(tb.discount AS DECIMAL)

										WHEN tb.is_sale_schedule != 'on' AND tb.discount != 0 AND tb.discount != ''
										THEN
												CAST(tb.adult_price AS DECIMAL) - ( CAST(tb.adult_price AS DECIMAL) / 100 ) * CAST(tb.discount AS DECIMAL)

										ELSE tb.adult_price
								END

							WHEN tb.child_price > 0 and tb.child_price != ''
							THEN CASE
										WHEN tb.is_sale_schedule = 'on'
												AND tb.discount != 0 AND tb.discount != ''
												AND tb.sale_price_from <= CURDATE() AND tb.sale_price_to >= CURDATE()
										THEN
												CAST(tb.child_price AS DECIMAL) - ( CAST(tb.child_price AS DECIMAL) / 100 ) * CAST(tb.discount AS DECIMAL)

										WHEN tb.is_sale_schedule != 'on' AND tb.discount != 0 AND tb.discount != ''
										THEN
												CAST(tb.child_price AS DECIMAL) - ( CAST(tb.child_price AS DECIMAL) / 100 ) * CAST(tb.discount AS DECIMAL)

										ELSE tb.child_price
								END
							WHEN tb.infant_price > 0 and tb.infant_price != ''
							THEN CASE
												WHEN tb.is_sale_schedule = 'on'
														AND tb.discount != 0 AND tb.discount != ''
														AND tb.sale_price_from <= CURDATE() AND tb.sale_price_to >= CURDATE()
												THEN
														CAST(tb.infant_price AS DECIMAL) - ( CAST(tb.infant_price AS DECIMAL) / 100 ) * CAST(tb.discount AS DECIMAL)

												WHEN tb.is_sale_schedule != 'on' AND tb.discount != 0 AND tb.discount != ''
												THEN
														CAST(tb.infant_price AS DECIMAL) - ( CAST(tb.infant_price AS DECIMAL) / 100 ) * CAST(tb.discount AS DECIMAL)

												ELSE tb.infant_price
										END

							ELSE 0
						END AS st_tour_price";
                }
                return $query;
            }

            /**
             *
             *
             * @update 1.1.3
			 * @update 1.2.4 Use this functions for ST List Tour also
             * */
            function change_search_tour_arg($query)
            {

				/**
				 * Global Search Args used in Element list and map display
				 * @since 1.2.4
				 */
				global $st_search_args;
				if(!$st_search_args) $st_search_args=$_REQUEST;
                if (is_admin() and empty( $_REQUEST['is_search_map'] )) return $query;



				$tax_query=array();

                $post_type = get_query_var('post_type');

                if($post_type == 'st_tours')
                {
                    $query->set('author', '');
                    if(STInput::get('item_name'))
                    {
                        $query->set('s',STInput::get('item_name'));
                    }

                    $tax = STInput::get('taxonomy');
                    if(!empty($tax) and is_array($tax))
                    {
                        foreach($tax as $key=>$value)
                        {
                            if($value)
                            {
                                $value = explode(',',$value);
                                if(!empty($value) and is_array($value)){
                                    foreach($value as $k=>$v) {
                                        if(!empty($v)){
                                            $ids[] = $v;
                                        }
                                    }
                                }
                                if(!empty($ids)){
                                    $tax_query[]=array(
                                        'taxonomy'=>$key,
                                        'terms'=>$ids,
                                        //'COMPARE'=>"IN",
                                        'operator' => 'AND',
                                        'include_children'=>false
                                    );
                                }
                                $ids = array();
                            }
                        }
                    }

                    $is_featured = st()->get_option('is_featured_search_tour','off');
                    if(!empty($is_featured) and $is_featured =='on' and empty( $st_search_args['st_orderby'] )){
                        $query->set('meta_key','is_featured');
                        $query->set('orderby','meta_value');
                        $query->set('order','DESC');
                    }

                    if($is_featured == 'off' and !STInput::get('orderby') and empty( $st_search_args['st_orderby'] )) {
                        //Default Sorting
                        $query->set('orderby', 'modified');
                        $query->set('order', 'desc');
                    }



					/**
					 * Post In and Post Order By from Element
					 * @since 1.2.4
					 * @author dungdt
					 */
					if(!empty( $st_search_args['st_ids'] )) {
						$query->set( 'post__in',explode( ',' , $st_search_args['st_ids'] ));
						$query->set( 'orderby','post__in');
					}

					if(!empty( $st_search_args['st_orderby'] ) and $st_orderby= $st_search_args['st_orderby']) {


						if($st_orderby == 'rate') {
							$query->set('meta_key', 'rate_review');
							$query->set( 'orderby',  'meta_value_num');
						}
						if($st_orderby == 'discount') {
                            $query->set( 'order',  $st_search_args['st_order']);
							$query->set('meta_key', 'discount');
							$query->set( 'orderby',  'meta_value_num');
						}

						if($st_orderby == 'last_minute_deal') {
							$query->set( 'order',  'DESC');
							$query->set( 'orderby',  'meta_value');
							$query->set( 'meta_key',  'sale_price_from');
							$meta_query[] = array(
								'key'     => 'is_sale_schedule' ,
								'value'   => 'on' ,
								'compare' => "="
							);

						}
					}
					if(!empty( $st_search_args['sort_taxonomy'] ) and $sort_taxonomy=$st_search_args['sort_taxonomy']) {
						if(isset( $st_search_args[ "id_term_" . $sort_taxonomy ] )) {
							$id_term              = $st_search_args[ "id_term_" . $sort_taxonomy ];
							$tax_query[]=array(
								array(
									'taxonomy' => $sort_taxonomy ,
									'field'    => 'id' ,
									'terms'    => explode( ',' , $id_term ),
									'include_children'=>false
								) ,
							);
						}
					}


					if(!empty($meta_query)){
						$query->set('meta_query',$meta_query);
					}

					if(!empty($tax_query))
					$query->set('tax_query',$tax_query);
                }
            }
            function choose_search_template($template)
            {
                global $wp_query;
                $post_type = get_query_var('post_type');
                if( $wp_query->is_search && $post_type == 'st_tours' )
                {
                    return locate_template('search-tour.php');  //  redirect to archive-search.php
                }
                return $template;
            }

            function get_result_string()
            {
                global $wp_query,$st_search_query;
                if($st_search_query){
                    $query=$st_search_query;
                }else $query=$wp_query;
                
                $result_string='';

                if ($query->found_posts) {
                    if($query->found_posts > 1){
                        $result_string.=esc_html( $query->found_posts).__(' tours ',ST_TEXTDOMAIN);
                    }else{
                        $result_string.=esc_html( $query->found_posts).__(' tour ',ST_TEXTDOMAIN);
                    }
                } else {
                    $result_string = __('No tour found', ST_TEXTDOMAIN);
                }


                $location_id=STInput::get('location_id');
                if (!$location_id){
                    $location_id = STInput::get('location_id_pick_up') ; 
                }
                if($location_id and $location=get_post($location_id))
                {
                    $result_string.=sprintf(__(' in %s',ST_TEXTDOMAIN),get_the_title($location_id));
                }elseif(STInput::request('location_name')){
                    $result_string.=sprintf(__(' in %s',ST_TEXTDOMAIN), STInput::request('location_name'));
                }elseif(STInput::request('address')){
                    $result_string.=sprintf(__(' in %s',ST_TEXTDOMAIN), STInput::request('address'));
                }

                if(STInput::request('st_google_location', '') != ''){
                    $result_string .= sprintf( __( ' in %s' , ST_TEXTDOMAIN ) , esc_html(STInput::request( 'st_google_location' , '')) );
                }
                
                $start=TravelHelper::convertDateFormat(STInput::get('start'));
                $end=TravelHelper::convertDateFormat(STInput::get('end'));

                $start=strtotime($start);

                $end=strtotime($end);

                if($start and $end)
                {
                    $result_string.=__(' on ',ST_TEXTDOMAIN).date_i18n('M d',$start).' - '.date_i18n('M d',$end);
                }

                if($adult_number=STInput::get('adult_number')){
                    if($adult_number>1){
                        $result_string.=sprintf(__(' for %s adults',ST_TEXTDOMAIN),$adult_number);
                    }else{

                        $result_string.=sprintf(__(' for %s adult',ST_TEXTDOMAIN),$adult_number);
                    }

                }

                return esc_html($result_string);

            }
            static function get_count_book($post_id=null){
                if(!$post_id) $post_id=get_the_ID();
                //  $post_type = get_post_type($id_post);
                $query = array(
                    'post_type'=>'st_order',
                    'post_per_page'=>'-1',
                    'meta_query'=>array(
                        array(
                            'key'=>'item_id',
                            'value'=>$post_id,
                            'compare'=>"="
                        )
                    ),
                );

                $query = new WP_Query( $query );
                $count= $query->post_count;
                wp_reset_postdata();
                return $count;
            }
            static function get_count_user_book($post_id=null){
                if(!$post_id) $post_id=get_the_ID();
                $count = 0;
                if(st()->get_option('use_woocommerce_for_booking','off') == 'on'){
                    global $wpdb;

                    $query = "  SELECT ".$wpdb->prefix."woocommerce_order_items.*,".$wpdb->prefix."woocommerce_order_itemmeta.meta_value,st_meta1.meta_value FROM ".$wpdb->prefix."woocommerce_order_items
                                INNER JOIN ".$wpdb->prefix."woocommerce_order_itemmeta  ON ".$wpdb->prefix."woocommerce_order_itemmeta.order_item_id = ".$wpdb->prefix."woocommerce_order_items.order_item_id and ".$wpdb->prefix."woocommerce_order_itemmeta.meta_key='_st_st_booking_id'
                                INNER JOIN ".$wpdb->prefix."woocommerce_order_itemmeta as st_meta1  ON st_meta1.order_item_id = ".$wpdb->prefix."woocommerce_order_items.order_item_id and st_meta1.meta_key='_st_number_book'
                                WHERE 1=1
                                AND ".$wpdb->prefix."woocommerce_order_itemmeta.meta_value = ".get_the_ID();
                    $rs = $wpdb->get_results($query,OBJECT);
                    if(!empty($rs)){
                        foreach($rs as $k=>$v){
                            $count  = $count + $v->meta_value;
                        }
                    }
                }else{
                    $query = array(
                        'post_type'=>'st_order',
                        'post_per_page'=>'-1',
                        'meta_query'=>array(
                            array(
                                'key'=>'item_id',
                                'value'=>$post_id,
                                'compare'=>"="
                            )
                        ),
                    );
                    $type_tour = get_post_meta($post_id , 'type_tour' , true);
                    if($type_tour == 'daily_tour') {
                        $query['date_query'] =  array(
                            array(
                                'after'     => date("Y-m-d") ,
                                'before'    => date("Y-m-d") ,
                                'inclusive' => true ,
                            ) ,
                        ) ;
                    }

                    $query = new WP_Query( $query );
                    while($query->have_posts()){
                        $query->the_post();

                        $count = $count +  get_post_meta(get_the_ID() , 'adult_number' , true);
                        $count = $count +  get_post_meta(get_the_ID() , 'child_number' , true);

                    }
                    wp_reset_postdata();
                }

                return $count;
            }
            function tours_add_to_cart()
            {
                if(STInput::request('action') == 'tours_add_to_cart')
                {
                    if(self::do_add_to_cart()){
                        $link=STCart::get_cart_link();
                        wp_safe_redirect($link);
                        die;
                    }

                }

            }
            /**
            * from 1.1.7 fix price child adult by person booking
            */
            function filter_price_by_person($price_old, $number ,  $key = 1 ){

                $discount_by_adult = (get_post_meta(STInput::request('item_id') , 'discount_by_adult' , true));
                $discount_by_child = (get_post_meta(STInput::request('item_id') , 'discount_by_child' , true));

                if ($key == 1 and is_array($discount_by_adult) and !empty($discount_by_adult)){
                    foreach ($discount_by_adult as $key => $value) {
                        if ($number>=$value['key'])
                        {
                            $flag_return =  (1-$value['value']/100)*$price_old ;

                        }
                        if (!$flag_return){
                            $flag_return = $price_old;
                        }
                    }
                    return $flag_return ;
                }
                if ($key == 2 and is_array($discount_by_child) and !empty($discount_by_child)){

                    foreach ($discount_by_child as $key => $value) {
                        if ($number>=$value['key'])
                        {
                            $flag_return =  (1-$value['value']/100)*$price_old ;
                        }
                        if (!$flag_return){
                            $flag_return = $price_old;
                        }
                    }
                    return $flag_return;
                }
                return $price_old ;
            }

            /* 
            *@updated 1.2.3
            */
            function do_add_to_cart(){
                $pass_validate = true;
                
                $item_id       = STInput::request('item_id','');
                if($item_id <= 0 || get_post_type($item_id) != 'st_tours'){
                    STTemplate::set_message( __( 'This tour is not available..' , ST_TEXTDOMAIN ) , 'danger' );
                    $pass_validate = false;
                    return false;
                }
                
                $number        = 1;
                
                $adult_number = intval(STInput::request('adult_number',0));
                $child_number = intval(STInput::request('child_number',0));
                $infant_number = intval(STInput::request('infant_number',0));


                $data['adult_number'] = $adult_number;
                $data['child_number'] = $child_number;
                $data['infant_number'] = $infant_number;

                $min_number = intval( get_post_meta( $item_id, 'min_people', true ) );
                if( $min_number <= 0 ) $min_number = 1;

                $max_number = intval( get_post_meta( $item_id, 'max_people', true ) );
                

                $type_tour  = get_post_meta($item_id, 'type_tour', true);

                $data['type_tour']    = $type_tour;
                
                $today = date('Y-m-d');
                $check_in = TravelHelper::convertDateFormat(STInput::request('check_in', ''));
                $check_out = TravelHelper::convertDateFormat(STInput::request('check_out', ''));

				if(!$adult_number and  !$child_number and  !$infant_number){
					STTemplate::set_message(__( 'Please select at least one person.' , ST_TEXTDOMAIN ) , 'danger' );
					$pass_validate = FALSE;
					return false;
				}

                if($adult_number + $child_number + $infant_number < $min_number){
                    STTemplate::set_message( sprintf(__( 'Min of people for this tour is %d people' , ST_TEXTDOMAIN ), $min_number) , 'danger' );
                    $pass_validate = FALSE;
                    return false;
                }
                
                /**
                *@since 1.2.8
                *   Only check limit people when max_people > 0 (unlimited)
                **/
                if( $max_number > 0 ){
                    if($adult_number + $child_number + $infant_number > $max_number){
                        STTemplate::set_message( sprintf(__( 'Max of people for this tour is %d people' , ST_TEXTDOMAIN ), $max_number) , 'danger' );
                        $pass_validate = FALSE;
                        return false;
                    }
                }

                if(!$check_in || !$check_out){
                    STTemplate::set_message(__( 'Select a day in the calendar.' , ST_TEXTDOMAIN ) , 'danger' );
                    $pass_validate = FALSE;
                    return false;
                }

                $compare = TravelHelper::dateCompare($today, $check_in);
                if($compare < 0){
                    STTemplate::set_message( __( 'This tour has expired' , ST_TEXTDOMAIN ) , 'danger' );
                    $pass_validate = false;
                    return false;
                }

                $booking_period = intval(get_post_meta($item_id, 'tours_booking_period', true));
                $period = TravelHelper::dateDiff($today, $check_in);
                if($period < $booking_period){
                    STTemplate::set_message(sprintf(__('This tour allow minimum booking is %d day(s)', ST_TEXTDOMAIN), $booking_period), 'danger');
                    $pass_validate = false;
                    return false;
                }
                $tour_available = TourHelper::checkAvailableTour($item_id, strtotime($check_in), strtotime($check_out));
                if(!$tour_available){
                    STTemplate::set_message(__('The check in, check out day is not invalid or this tour not available.', ST_TEXTDOMAIN), 'danger');
                    $pass_validate = FALSE;
                    return false;
                }

                if( $max_number > 0 ){
                    $free_people = $max_number;
                    $result = TourHelper::_get_free_peple($item_id, strtotime($check_in), strtotime($check_out));

                    if(is_array($result) && count($result)){
                        $free_people = intval($result['free_people']);
                    }
                    
                    /**
                    *@since 1.2.8
                    *   Only check limit people when max_people > 0 (unlimited)
                    **/
                    if($free_people < ($adult_number + $child_number + $infant_number)){
                        STTemplate::set_message(sprintf(__('This tour only vacant %d people', ST_TEXTDOMAIN), $free_people), 'danger');
                        $pass_validate = FALSE;
                        return false;
                    }
                }
                

                $extras = STInput::request('extra_price', array());
                $extra_price = self::geExtraPrice($extras);
                $data['extras'] = $extras;
                $data['extra_price'] = $extra_price;

                $data_price = STPrice::getPriceByPeopleTour($item_id, strtotime($check_in), strtotime($check_out),$adult_number, $child_number, $infant_number);
                $total_price = $data_price['total_price'];
                $sale_price = STPrice::getSaleTourSalePrice($item_id, $total_price, $type_tour, strtotime($check_in));
                $data['check_in'] = date('m/d/Y',strtotime($check_in));
                $data['check_out'] = date('m/d/Y',strtotime($check_out));

                $people_price = STPrice::getPeoplePrice($item_id, strtotime($check_in), strtotime($check_out));
                
                $data = wp_parse_args($data, $people_price);
                $data['ori_price'] = $sale_price  + $extra_price;

                /*$data['currency']     = TravelHelper::get_current_currency('symbol');
                $data['currency_rate'] = TravelHelper::get_current_currency('rate');
                $data['currency_pos'] = TravelHelper::get_current_currency('booking_currency_pos');*/
                $data['commission'] = TravelHelper::get_commission();
                $data['data_price'] = $data_price;
                $data['discount_rate']  = STPrice::get_discount_rate($item_id, strtotime($check_in));
                if($pass_validate) {
                    $data['duration'] = ($type_tour == 'daily_tour') ? get_post_meta($item_id, 'duration_day', true) : '';
                    if ($pass_validate) {
                        STCart::add_cart($item_id, $number, $sale_price  + $extra_price, $data);
                    }
                }
                return $pass_validate;
            }
            function geExtraPrice($extra_price = array()){
                $total_price = 0;
                if(isset($extra_price['value']) && is_array($extra_price['value']) && count($extra_price['value'])){
                    foreach($extra_price['value'] as $name => $number){
                        $price_item = floatval($extra_price['price'][$name]);
                        if($price_item <= 0) $price_item = 0;
                        $number_item = intval($extra_price['value'][$name]);
                        if($number_item <= 0) $number_item = 0;
                        $total_price += $price_item * $number_item;
                    }
                }
                return $total_price ;
            }
            function get_cart_item_html($item_id = false){
                return st()->load_template('tours/cart_item_html',null,array('item_id'=>$item_id));
            }

            function custom_tour_layout($old_layout_id)
            {
                if(is_singular('st_tours'))
                {
                    $meta=get_post_meta(get_the_ID(),'st_custom_layout',true);

                    if($meta)
                    {
                        return $meta;
                    }
                }
                return $old_layout_id;
            }

            function get_search_fields()
            {
                $fields=st()->get_option('activity_tour_search_fields');
                return $fields;
            }

            static function get_info_price($post_id=null){

                /**
                 * @since 1.2.5
                 * filter hook get_price_html
                 * author quandq
                 */
                if(!$post_id)
                    $post_id = get_the_ID();
                $prices = self::get_price_person( $post_id );
                $price_old = $price_new = 0;
                if(!empty($prices['adult'])){
                    $price_old = $prices['adult'];
                    $price_new = $prices['adult_new'];
                }elseif(!empty($prices['child'])){
                    $price_old = $prices['child'];
                    $price_new = $prices['child_new'];
                }elseif(!empty($prices['infant'])){
                    $price_old = $prices['infant'];
                    $price_new = $prices['infant_new'];
                }
                return array('price_old'=>$price_old,'price_new'=>$price_new,'discount'=>$prices['discount']);
            }

            static function get_price_person($post_id=null)
            {
                if(!$post_id) $post_id=get_the_ID();
                $adult_price=get_post_meta($post_id,'adult_price',true);
                $child_price=get_post_meta($post_id,'child_price',true);
                $infant_price=get_post_meta($post_id,'infant_price',true);

                if( $adult_price < 0 ) $adult_price = 0;
                if( $child_price < 0 ) $child_price = 0;
                if( $infant_price < 0 ) $infant_price = 0;

                $adult_price = apply_filters('st_apply_tax_amount',$adult_price);
                $child_price = apply_filters('st_apply_tax_amount',$child_price);
                $infant_price = apply_filters('st_apply_tax_amount',$infant_price);

                $discount=get_post_meta($post_id,'discount',true);
                $is_sale_schedule=get_post_meta($post_id,'is_sale_schedule',true);

                if($is_sale_schedule=='on')
                {
                    $sale_from=get_post_meta($post_id,'sale_price_from',true);
                    $sale_to=get_post_meta($post_id,'sale_price_to',true);
                    if($sale_from and $sale_from){

                        $today=date('Y-m-d');
                        $sale_from = date('Y-m-d', strtotime($sale_from));
                        $sale_to = date('Y-m-d', strtotime($sale_to));
                        if (($today >= $sale_from) && ($today <= $sale_to))
                        {

                        }else{

                            $discount=0;
                        }

                    }else{
                        $discount=0;
                    }
                }

                if($discount){
                    if($discount>100) $discount=100;

                    $adult_price_new=$adult_price-($adult_price/100)*$discount;
                    $child_price_new=$child_price-($child_price/100)*$discount;
                    $infant_price_new=$infant_price-($infant_price/100)*$discount;
                    $data = array(
                        'adult'=>$adult_price,
                        'adult_new'=>$adult_price_new,
                        'child'=>$child_price,
                        'child_new'=>$child_price_new,
                        'infant'=>$infant_price,
                        'infant_new'=>$infant_price_new,
                        'discount'=>$discount,

                    );
                }else{
                    $data = array(
                        'adult_new'=>$adult_price,
                        'adult'    =>$adult_price,
                        'child'     =>$child_price,
                        'child_new'=>$child_price,
                        'infant'     =>$infant_price,
                        'infant_new'=>$infant_price,
                        'discount'=>$discount,
                    );
                }

                $off_adult  = get_post_meta($post_id , 'hide_adult_in_booking_form' ,true);
                $off_child  = get_post_meta($post_id , 'hide_children_in_booking_form' ,true);
                $off_infant  = get_post_meta($post_id , 'hide_infant_in_booking_form' ,true);

                if ($off_adult == "on") {unset ($data['adult']) ;unset ($data['adult_new']) ;}
                if ($off_child == "on") {unset ($data['child']) ;unset ($data['child_new']) ;}
                if ($off_infant == "on") {unset ($data['infant']) ;unset ($data['infant_new']) ;}

                return $data;
            }

            static function get_price_html( $post_id = false , $get = false , $st_mid = '' , $class = '' ,$hide_title=true)
            {
                /*
                 * since 1.1.3
                 * filter hook get_price_html
                */
                if(!$post_id)
                    $post_id = get_the_ID();

                $prices = self::get_price_person( $post_id );
                $price_old = $price_new = 0;
                if(!empty($prices['adult'])){
                    $price_old = $prices['adult'];
                    $price_new = $prices['adult_new'];
                }elseif(!empty($prices['child'])){
                    $price_old = $prices['child'];
                    $price_new = $prices['child_new'];
                }elseif(!empty($prices['infant'])){
                    $price_old = $prices['infant'];
                    $price_new = $prices['infant_new'];
                }
                $html ="";
                if (!$hide_title and $price_new > 0) {
                    $html .= __("From  " , ST_TEXTDOMAIN ) ;
                }
                if ($price_new !=  $price_old) {
                    $html .= '<span class="text-small lh1em item onsale ">'.TravelHelper::format_money( $price_old ) ."</span>";
                }
                $price_new = ($price_new) ? TravelHelper::format_money( $price_new ) : __("Free" , ST_TEXTDOMAIN) ;
                $html .= '<span class="text-lg lh1em item "> '.$price_new ."</span>";
                return apply_filters( 'st_get_tour_price_html' , $html );
            }
            static function get_array_discount_by_person_num($item_id = false){
                /* @since 1.1.1 */
                $return = array();

                $discount_by_adult = get_post_meta($item_id, 'discount_by_adult' , true) ;
                $discount_by_child = get_post_meta($item_id, 'discount_by_child' , true) ;

                if (!$discount_by_adult and !$discount_by_child) { return false; }
                if (is_array($discount_by_adult) and !empty($discount_by_adult)){
                    foreach ($discount_by_adult as $row) {
                        $key = (int)$row['key']  ;
                        $value = (int)$row['value']/100;
                        $return['adult'][$key]= $value;
                    }
                }else
                {
                    $return['adult'] = array();
                }
                if (is_array($discount_by_child) and !empty($discount_by_child)){
                    foreach ($discount_by_child as $row) {
                        $key = (int)$row['key']  ;
                        $value = (int)$row['value']/100;
                        $return['child'][$key]= $value;
                    }
                }else {
                    $return['child'] = array();
                }

                return $return ;
            }
            static function get_cart_item_total($item_id,$item)
            {
                $count_sale=0;
                $price_sale = $item['price'];

                if(!empty($item['data']['discount'])){
                    $count_sale = $item['data']['discount'];
                    $price_sale = $item['data']['price_sale'] * $item['number'];
                }

                $adult_number=$item['data']['adult_number'];
                $child_number=$item['data']['child_number'];
                $adult_price=$item['data']['adult_price'];
                $child_price=$item['data']['child_price'];

                if( $adult_price < 0 ) $adult_price = 0;
                if( $child_price < 0 ) $child_price = 0;



                if ($get_array_discount_by_person_num = self::get_array_discount_by_person_num($item_id)){
                    if ($array_adult = $get_array_discount_by_person_num['adult']){
                        if (is_array($array_adult) and  !empty($array_adult)){
                            foreach ($array_adult as $key => $value) {
                                if ($adult_number>=(int)$key ){
                                    $adult_price2 = $adult_price*$value;
                                }
                            }
                            if (!empty($adult_price2)){
                                $adult_price -=$adult_price2;
                            }

                        }
                    };
                    if ($array_child = $get_array_discount_by_person_num['child']){
                        if (is_array($array_child) and  !empty($array_child)){
                            foreach ($array_child as $key => $value) {
                                if ($child_number>=(int)$key ){
                                    $child_price2 = $child_price*$value;
                                }
                            }
                            if (!empty($child_price2)){
                                $child_price -=$child_price2;
                            }

                        }
                    };
                }

                $adult_price = round($adult_price);
                $child_price = round($child_price);
                $total_price=$adult_number*st_get_discount_value($adult_price,$count_sale,false);
                $total_price+=$child_number*st_get_discount_value($child_price,$count_sale,false);

                return $total_price;

            }


            function get_near_by($post_id=false,$range=20, $limit = 5)
            {
                $this->post_type='st_tours';
                //$limit = st()->get_option('tours_similar_tour',5);

                return parent::get_near_by($post_id,$range, $limit);

            }

            static function get_owner_email($item_id)
            {
				$theme_option=st()->get_option('partner_show_contact_info');
				$metabox=get_post_meta($item_id,'show_agent_contact_info',true);

				$use_agent_info=FALSE;

				if($theme_option=='on') $use_agent_info=true;
				if($metabox=='user_agent_info') $use_agent_info=true;
				if($metabox=='user_item_info') $use_agent_info=FALSE;

				if($use_agent_info){
					$post=get_post($item_id);
					if($post){
						return get_the_author_meta('user_email',$post->post_author);
					}

				}
                return get_post_meta($item_id,'contact_email',true);
            }

            public static function tour_external_booking_submit(){
                /*
                 * since 1.1.1
                 * filter hook tour_external_booking_submit
                */
                $post_id = get_the_ID();
                if (STInput::request('post_id')) {$post_id = STInput::request('post_id') ; }

                $tour_external_booking = get_post_meta($post_id, 'st_tour_external_booking' , "off");
                $tour_external_booking_link = get_post_meta($post_id , 'st_tour_external_booking_link' ,true) ;
                if ($tour_external_booking =="on" and $tour_external_booking_link!==""){
                    if (get_post_meta($post_id , 'st_tour_external_booking_link' , true)){
                        ob_start();
                        ?>
                            <a class='btn btn-primary' href='<?php echo get_post_meta($post_id , 'st_tour_external_booking_link' , true) ?>'> <?php st_the_language('book_now')  ?></a>
                        <?php
                    $return  =  ob_get_clean();
                    }
                }
                    else
                {
                    $return  =  TravelerObject::get_book_btn();
                }
                return apply_filters('tour_external_booking_submit' , $return ) ;
            }

            /* @since 1.1.3 */
            static function get_taxonomy_and_id_term_tour()
            {
                $list_taxonomy = st_list_taxonomy( 'st_tours' );
                $list_id_vc    = array();
                $param         = array();
                foreach( $list_taxonomy as $k => $v ) {
                    $term = get_terms( $v );
                    if(!empty( $term ) and is_array( $term )) {
                        foreach( $term as $key => $value ) {
                            $list_value[ $value->name ] = $value->term_id;
                        }
                        $param[ ]                      = array(
                            "type"       => "checkbox" ,
                            "holder"     => "div" ,
                            "heading"    => $k ,
                            "param_name" => "id_term_" . $v ,
                            "value"      => $list_value ,
                            'dependency' => array(
                                'element' => 'sort_taxonomy' ,
                                'value'   => array( $v )
                            ) ,
                        );
                        $list_value                    = "";
                        $list_id_vc[ "id_term_" . $v ] = "";
                    }
                }

                return array(
                    "list_vc"    => $param ,
                    'list_id_vc' => $list_id_vc
                );
            }
            /**
            * from 1.1.7
            * removed $duration_unit from 1.2.7
            */
            static function get_duration_unit($post_id = null ){
                // day , hours
                if (!$post_id){
                    $post_id = get_the_ID() ;
                }
                $duration = get_post_meta($post_id  , 'duration_day' , true) ;
                
                return $duration;
                
                /*$duration_unit  = get_post_meta($post_id,'duration_unit' , true );

                if (!$duration_unit){$duration = 'day' ; }

                $html = "";
                $html_unit = "" ;

                if (!is_int($duration)) {$html =  $duration ; }

                if ($duration >1 ){
                    if ($duration_unit == 'day'){
                        $html_unit =  __('days' , ST_TEXTDOMAIN) ;
                    }
                    if ($duration_unit == 'hour'){
                        $html_unit =  __('hours' , ST_TEXTDOMAIN) ;
                    }
                    if ($duration_unit == 'week'){
                        $html_unit =  __('weeks' , ST_TEXTDOMAIN) ;
                    }
                    if ($duration_unit == 'month'){
                        $html_unit =  __('months' , ST_TEXTDOMAIN) ;
                    }
                }
                if ($duration == 1) {
                    if ($duration_unit == 'day'){
                        $html_unit =  __('day' , ST_TEXTDOMAIN) ;
                    }
                    if ($duration_unit == 'hour'){
                        $html_unit =  __('hour' , ST_TEXTDOMAIN) ;
                    }
                    if ($duration_unit == 'week'){
                        $html_unit =  __('week' , ST_TEXTDOMAIN) ;
                    }
                    if ($duration_unit == 'month'){
                        $html_unit =  __('month' , ST_TEXTDOMAIN) ;
                    }
                }

                return $html ." ".$html_unit;*/
            }
            /**
             * from 1.1.7
            * removed from 1.2.7
             */
            static function get_simple_duration_unit($post_id = null ){
                // day , hours
                if (!$post_id){
                    $post_id = get_the_ID() ;
                }
                $duration_unit  = get_post_meta($post_id,'duration_unit' , true );

                if (!$duration_unit){$duration_unit = 'day' ; }

                return $duration_unit;

            }

			static function get_instance()
			{
				if(!self::$_inst){
					self::$_inst=new self();
				}
				return self::$_inst;
			}
            
        }
        st()->tour=STTour::get_instance();
        st()->tour->init();
    }
