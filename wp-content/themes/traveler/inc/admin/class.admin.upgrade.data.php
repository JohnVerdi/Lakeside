<?php

/**

*@since 1.2.2

**/

if( !class_exists('STUpgradeData')){

	class STUpgradeData{

		public $table_nested = 'st_location_nested';

		public $table_relationships = 'st_location_relationships';

		public $column = array();

		public $st_upgrade_location_nested = 0;

		public $allow_version = false;



		public function __construct(){

			add_action('after_setup_theme',array(&$this,'_check_table_location_nested'), 50);

			add_action('after_setup_theme',array(&$this,'_check_table_location_relationships'), 50);



			add_action('admin_enqueue_scripts', array($this, '_add_scripts'));

			add_action('admin_notices', array($this, '_add_notices'));

			add_action('wp_ajax_st_get_data_location_nested', array($this,'st_get_data_location_nested'), 9999);



			add_action('save_post', array($this, 'st_update_location_nested'), 50);

			add_action('delete_post', array($this, 'st_delete_location_nested'), 50);



		}



		public function _add_scripts(){

			wp_enqueue_style( 'admin-location-nested', get_template_directory_uri() .'/css/admin/admin.location-nested.css' );

			wp_enqueue_script('admin-location-nested.js', get_template_directory_uri() . '/js/admin/location-nested.js', array('jquery'), true );

		}



		public function _add_notices(){

			$my_theme = wp_get_theme();

			$version = $my_theme->get( 'Version' );



			$check = get_option('st_completed_update_location_nested', '');

			$check2 = get_option('st_completed_update_location_relationships', '');

			$check3 = get_option('st_duplicated_data', '');



			if( !$check || empty( $check ) || !$check2 || empty( $check2 ) || STInput::request('page', '') == 'st-upgrade-data' || !$check3 || empty( $check3 ) ){

				echo '<div class="notice notice-warning" style="padding-bottom: 10px">';

				echo '

					<p>From the version <strong>1.2.7</strong>, it has a few new data updates. Click "Update Now" button to perform it.</p>';



				if( $check && $check2 && $check3 ){

					echo '

						<p><em>(You did it once. If you want to do it again, click "Update Now" button.)</em></p>

					';

				}

				echo '

					<button id="st-update-glocation" class="button button-primary button-large" type="submit">Update Now</button>

				';

				echo '</div>';

				echo '

					<div class="update-glocation-wrapper">

						<div class="update-glocation-content">

							<div class="update-glocation-title clear">

								<h4 class="title">Update Data Traveler</h4>

								<a href="#" class="update-glocation-close"></a>

							</div>

							<!--<div class="update-glocation-detail">

								<h4>Content will be updated</h4>

							</div>-->

							<form action="" class="update-item-form update-glocation-detail">

								<h4><strong>Select a content update:</strong></h4>

								<label for="update_table_post_type"><input checked id="update_table_post_type" type="checkbox" name="update_table_post_type" value="update_table_post_type"><span style="margin-left: 5px">Update new table all service.</span></label><br/>

								<label for="update_location_nested"><input checked id="update_location_nested" type="checkbox" name="update_location_nested" value="update_location_nested"><span style="margin-left: 5px">Update location data.</span></label><br/>

								<label for="update_location_relationships"><input checked id="update_location_relationships" type="checkbox" name="update_location_relationships" value="update_location_relationships"><span style="margin-left: 5px">Update location relationships.</span></label>

							</form>

							<div class="update-glocation-button">

								<div class="ball"></div>

								<div class="ball1"></div>

								<div class="text">Start</div>

							</div>

							<div class="update-glocation-progress">

								<div class="progress-bar blue stripes">

								    <span style="width: 0%"></span>

								</div>

							</div>

							<input checked id="reset_table" type="checkbox" name="reset_table" value="reset" style="display: none">

							<div class="update-glocation-message">

							

							</div>

						</div>

					</div>

				';

			}

		}



		public function isset_table( $table){

			global $wpdb;

			if( strtolower( $wpdb->get_var("SHOW TABLES LIKE '{$table}'") ) != strtolower( $table ) ){

				return false;

			}

			return true;

		}



		public function _deleteTable( $table){

			global $wpdb;

        	$wpdb->query("DROP TABLE {$table}");

        }



		public function _check_table_location_nested(){

			global $wpdb;



			$dbhelper = new DatabaseHelper('1.0.1');

			$dbhelper->setTableName($this->table_nested);

			$column = array(

				'id' => array(

	                'type' => 'bigint',

	                'length' => 9,

	                'AUTO_INCREMENT' => TRUE

	            ),

	            'location_id'=>array(

	                'type' => 'bigint',

	                'length' => 11

	            ),

	            'location_country' => array(

	            	'type' => 'varchar',

	            	'length' => 255

	            ),

	            'parent_id' => array(

	            	'type' => 'bigint',

	            	'length' => 11

	            ),

	            'left_key' => array(

					'type' => 'bigint',

					'length' => 11

	            ),

	            'right_key' => array(

					'type' => 'bigint',

					'length' => 11

	            ),

	            'name' => array(

					'type' => 'varchar',

					'length' => 255

	            ),

	            'fullname' => array(

					'type' => 'text',

	            ),

	            'language' => array(

					'type' => 'varchar',

					'length' => 255

	            ),

	            'status' => array(

					'type' => 'varchar',

					'length' => 255

	            )

			);

			$this->column = $column;

			$dbhelper->setDefaultColums($column);

			$dbhelper->check_meta_table_is_working('location_nested_table_version');

			$sql = "SELECT id FROM ".$wpdb->prefix.$this->table_nested." LIMIT 1";

			$num_row = (int) $wpdb->get_var( $sql );


			if( $this->isset_table( $wpdb->prefix.$this->table_nested) && $num_row <= 0){

				$ns = new Nested_set();

				$ns->setControlParams($wpdb->prefix.$this->table_nested);



				$ns->insertNewTree(array('location_id' => 0, 'name' => 'root', 'status' => 'private_root', 'fullname' => 'root'));

			}

		}



		public function _check_table_location_relationships(){

			global $wpdb;



			$dbhelper = new DatabaseHelper('1.0.0');

			$dbhelper->setTableName($this->table_relationships);

			$column = array(

				'id' => array(

	                'type' => 'bigint',

	                'length' => 9,

	                'AUTO_INCREMENT' => TRUE

	            ),

	            'post_id'=>array(

	                'type' => 'bigint',

	                'length' => 11

	            ),

	            'location_from' => array(

	            	'type' => 'bigint',

	            	'length' => 11

	            ),

	            'location_to' => array(

					'type' => 'bigint',

					'length' => 11

	            ),

	            'post_type' => array(

	            	'type' => 'varchar',

	            	'length' => 255

	            ),

	            'location_type' => array(

	            	'type' => 'varchar',

	            	'length' => 255

	            ),

			);

			$this->column = $column;

			$dbhelper->setDefaultColums($column);

			$dbhelper->check_meta_table_is_working('location_relationships_table_version');

		}



		/**

		*@updated 1.2.4

		**/

		public function st_get_data_location_nested(){

			global $wpdb;

			$duplicate = STDuplicateData::inst();

			if( STInput::request('update_table_post_type','') == 'update_table_post_type' && STInput::request('step', '') == 'update_table_post_type' ){



				$progress = (float) STInput::request('progress','');



				$sql = "SELECT count(ID) as total FROM {$wpdb->prefix}posts WHERE post_type in ('st_hotel','st_rental','st_activity','st_tours','st_cars') AND post_status IN ('publish','private')";

    			$total = (int) $wpdb->get_var( $sql );



    			if( $progress == 100 || $total <= 0 ){

    				update_option('st_duplicated_data','updated');



        			echo json_encode(array('status' => 'continue', 'progress' => 100, 'step' => 'update_location_nested', 'page' => 1, 'number_page' => '', 'update_location_nested' => STInput::request('update_location_nested', ''), 'update_location_relationships' => STInput::request('update_location_relationships', ''), 'message' => 'The next step is being checked...', 'reset_table' => 'reset', 'post_type' => ''));

        			die;

        		}





        		if(STInput::request('reset_table', '') == 'reset'){

                    $duplicate->stDeleteTable();

					$duplicate->stCreateTable();

					update_option('st_duplicated_data', '');

					update_option('update_meta_hotel','');

				}



				$posts_per_page = 10;





        		$post_type = STInput::request('post_type','st_hotel');



        		$page = (int) STInput::request( 'page', 1 );



        		$results = $duplicate->runUpdate( $post_type, $page );



        		$results['progress'] = $duplicate->get_progress( $total );



				$results['number_page'] = '';

				$results['status'] = 'continue';

				//$results['step'] = STInput::request('step', '');

				$results['update_table_post_type'] = STInput::request('update_table_post_type', '');

				$results['update_location_nested'] = STInput::request('update_location_nested', '');

				$results['update_location_relationships'] = STInput::request('update_location_relationships', '');

				$results['message'] = 'Updating new table service ...';



        		echo json_encode( $results );

        		die();

			}



			if(STInput::request('update_location_nested', '') == 'update_location_nested' && STInput::request('step', '') == 'update_location_nested'){

				$table = $wpdb->prefix.$this->table_nested;



				$ns = new Nested_set();

				$ns->setControlParams( $table );



				if(STInput::request('reset_table', '') == 'reset'){

					if($this->isset_table( $table)){

	        			$this->_deleteTable( $table );

	        			$this->_check_table_location_nested();

	        			update_option('st_completed_update_location_nested', '');

	        		}

	        	}



    			$sql = "SELECT

				(

					SELECT

						count(ID)

					FROM

						{$wpdb->prefix}posts

					WHERE

						post_type = 'location'

					AND post_status NOT IN ('auto-draft')

				) - (

					SELECT

						count(id)

					FROM

						{$wpdb->prefix}st_location_nested

					WHERE

						id <> 1

				)";



				$compare = $wpdb->get_var( $sql );



        		if( $compare == 0){

        			update_option('st_completed_update_location_nested', 'completed');

        			echo json_encode(array('status' => 'continue', 'progress' => 100, 'step' => 'update_location_full_name', 'page' => 1, 'number_page' => '', 'update_location_nested' => STInput::request('update_location_nested', ''), 'update_location_relationships' => STInput::request('update_location_relationships', ''), 'message' => 'The next step is being checked...', 'reset_table' => '', 'post_type' => ''));

        			die;

        		}



        		$results = $this->get_data_location();



        		if(!empty($results['lists'])){

        			$string_value = '';

					foreach($results['lists'] as $list){

						$id = NULL;

						$location_id = (int) $list['ID'];

						$location_country = get_post_meta( $location_id, 'location_country', true);

						$name = $list['post_title'];

						$parent_id = (int) $list['post_parent'];

						$status = $list['post_status'];

						if($parent_id == 0){

							$this->setFirstChild($parent_id, $location_country, $location_id, $name, $status);

						}else{

							$this->setChild($parent_id, $location_country, $location_id, $name, $status);

						}



					}



					$progress = $wpdb->get_var("SELECT

					(

						SELECT

							count(id)

						FROM

							{$wpdb->prefix}st_location_nested

						WHERE

							id <> 1

					) / (

						SELECT

							count(ID)

						FROM

							{$wpdb->prefix}posts

						WHERE

							post_type = 'location'

						AND post_status NOT IN ('auto-draft')

					) * 100");



					$results['page'] = '';

					$results['number_page'] = '';

					$results['progress'] = $progress;

					$results['status'] = 'continue';

					$results['step'] = STInput::request('step', '');

					$results['update_location_nested'] = STInput::request('update_location_nested', '');

					$results['update_location_relationships'] = STInput::request('update_location_relationships', '');

					$results['message'] = 'Updating location data...';

					$results['post_type'] = '';



					unset($results['lists']);

					echo json_encode($results);

					die();

        		}else{

        			echo json_encode(array('status' => 'error', 'message' => 'Your location is empty.'));

					die();

        		}

        	}



        	if(STInput::request('update_location_nested', '') == 'update_location_nested' && STInput::request('step', '') == 'update_location_full_name'){

        		$table = $wpdb->prefix.$this->table_nested;

        		$ns = new Nested_set();

				$ns->setControlParams( $table );



        		$posts_per_page = 10;



        		$number_page = (int) STInput::request('number_page', '');

    			if( $number_page == 0 ){



    				$root = $ns->getRootNodes();

    				$total = (int) $ns->getNumberOfChildren( $root[0] );

        			$number_page = ceil($total / $posts_per_page);

    			}



				$page = (int) STInput::request('page', 1);



        		if($page > $number_page){

        			echo json_encode(array('status' => 'continue', 'progress' => 100, 'step' => 'update_location_relationships', 'page' => 1, 'number_page' => '', 'update_location_nested' => STInput::request('update_location_nested', ''), 'update_location_relationships' => STInput::request('update_location_relationships', ''), 'message' => 'The next step is being checked...', 'reset_table' => 'reset', 'post_type' => ''));

        			die;

        		}



				$offset = ($page - 1) * $posts_per_page;



				$lists = $this->get_data_location_inserted( $page, $number_page, $posts_per_page );



				if( !empty( $lists ) ){

					foreach( $lists as $node ){

						$string = $this->getFullName( $node );

						$this->updateExtrafield( (int) $node['location_id'], array('fullname' => $string));

					}



					$progress = $progress = ($page / $number_page) * 100;



					$next_page = ($page < $number_page) ? $page + 1 : $number_page + 1;

					$results['page'] = $next_page;

					$results['number_page'] = $number_page;

					$results['progress'] = $progress;

					$results['status'] = 'continue';

					$results['step'] = STInput::request('step', '');

					$results['update_location_nested'] = STInput::request('update_location_nested', '');

					$results['update_location_relationships'] = STInput::request('update_location_relationships', '');

					$results['message'] = 'Updating name location data ...';

					$results['post_type'] = '';



					echo json_encode($results);

					die();

				}else{

					update_option('st_completed_update_location_nested', 'completed');

        			echo json_encode(array('status' => 'continue', 'progress' => 100, 'step' => 'update_location_relationships', 'page' => 1, 'number_page' => '', 'update_location_nested' => STInput::request('update_location_nested', ''), 'update_location_relationships' => STInput::request('update_location_relationships', ''), 'message' => 'The next step is being checked...', 'reset_table' => 'reset', 'post_type' => ''));

        			die;

				}

        	}



        	if(STInput::request('update_location_relationships', '') == 'update_location_relationships' && STInput::request('step', '') == 'update_location_relationships'){

        		$table = $wpdb->prefix.$this->table_relationships;



				$ns = new Nested_set();

				$ns->setControlParams( $table );



        		if(STInput::request('reset_table', '') == 'reset'){

					if($this->isset_table( $table )){

	        			$this->_deleteTable( $table );

	        			$this->_check_table_location_relationships();

	        			update_option('st_completed_update_location_relationships', '');

	        		}

	        	}



	        	$posts_per_page = 10;



	        	$number_page = (int) STInput::request('number_page', '');

    			if( $number_page == 0){

        			$total = intval($wpdb->get_var("SELECT

						COUNT(DISTINCT ID)

					FROM

						{$wpdb->prefix}posts

					INNER JOIN {$wpdb->prefix}postmeta AS mt ON mt.post_id = {$wpdb->prefix}posts.ID

					AND mt.meta_key = 'multi_location'

					WHERE

						post_type IN (

							'st_hotel',

							'hotel_room',

							'st_cars',

							'st_tours',

							'st_rental',

							'st_activity'

						)

					AND mt.meta_value <> ''

					AND post_status NOT IN ('auto-draft')"));



        			$number_page = ceil($total / $posts_per_page);

    			}



        		$page = (int) STInput::request('page', 1);



        		if($page > $number_page){

        			update_option('st_completed_update_location_relationships', 'completed');

        			echo json_encode(array('status' => 'completed', 'progress' => 100, 'step' => '', 'page' => 1, 'number_page' => '', 'mesage' => ''));

        			die;

        		}



        		$results = $this->get_data_location_relationship($page, $number_page, $posts_per_page);



        		if(!empty($results['lists'])){

        			$string_value = '';

        			foreach( $results['lists'] as $list){

        				$post_id = (int) $list['ID'];

        				$multi_location = explode(',', $list['location']);

        				$post_type = $list['post_type'];



        				foreach( $multi_location as $location){

        					if( !empty( $location ) ){

        						$location = (int) str_replace('_', '', $location);

        						$data = array(

        							'post_id' => $post_id,

        							'location_from' => $location,

        							'location_to' => '',

        							'post_type' => $post_type,

        							'location_type' => 'multi_location'

        						);



        						$wpdb->insert( $wpdb->prefix.$this->table_relationships, $data );

        					}

        				}

        			}



        			$progress = ($page / $number_page) * 100;



					$next_page = ($page < $number_page) ? $page + 1 : $number_page + 1;

					$results['page'] = $next_page;

					$results['number_page'] = $number_page;

					$results['progress'] = $progress;

					$results['status'] = 'continue';

					$results['step'] = STInput::request('step', '');

					$results['update_location_nested'] = STInput::request('update_location_nested', '');

					$results['update_location_relationships'] = STInput::request('update_location_relationships', '');

					$results['message'] = 'Updating location relationships data...';

					$results['reset_table'] = '';

					$results['post_type'] = '';



					unset($results['lists']);

					echo json_encode($results);

					die();

        		}else{

        			echo json_encode(array('status' => 'error', 'message' => 'Have an error when get relationships data. Contact with supporter for help!'));

					die();

        		}



        	}



        	echo json_encode(array('status' => 'completed', 'progress' => 100, 'mesage' => ''));

        	die();



		}



		public function setFirstChild($parent_id = 0, $location_country = '', $location_id = 0, $name = '', $status = 'publish'){

			global $wpdb;

			$ns = new Nested_set();

			$ns->setControlParams($wpdb->prefix.$this->table_nested);



			$node_root = $ns->getRootNodes();

			$node = array(

				'id' => (int) $node_root[0]['id'],

				'parent_id' => (int) $node_root[0]['parent_id'],

				'left_key' => (int) $node_root[0]['left_key'],

				'right_key' => (int) $node_root[0]['right_key'],

			);



			$language = $this->langcode_post_id( $location_id );



			$extra = array(

				'location_country' => $location_country,

				'location_id' => $location_id,

				'name' => $name,

				'status' => $status,

				'language' => $language

			);

			$new_node = $ns->appendNewChild($node, $extra);

			return $new_node;

		}



		public function setChild($parent_id = 0, $location_country = '', $location_id = 0, $name = '', $status){

			global $wpdb;

			$ns = new Nested_set();

			$ns->setControlParams($wpdb->prefix.$this->table_nested);



			$node_parent = $ns->getNodeWhere('location_id = '.$parent_id);

			if(empty($node_parent)){

				$this->setFirstChild($parent_id, $location_country,  $location_id, $name, $status);

			}else{

				$node = array(

					'id' => (int) $node_parent['id'],

					'parent_id' => (int) $node_parent['parent_id'],

					'left_key' => (int) $node_parent['left_key'],

					'right_key' => (int) $node_parent['right_key'],

				);



				$language = $this->langcode_post_id( $location_id );



				$extra = array(

					'location_country' => $location_country,

					'name' => $name,

					'location_id' => $location_id,

					'status' => $status,

					'language' => $language

				);


				$new_node = $ns->appendNewChild($node, $extra);



				return $new_node;

			}



		}



		public function getFullName( $node ){

			global $wpdb;

			$table = $wpdb->prefix.$this->table_nested;



			$ns = new Nested_set();

			$ns->setControlParams($wpdb->prefix.$this->table_nested);



			if( empty( $node ) ){

				return '';

			}

			$string = $node['name'];



            $tree = $ns->getNodesWhere("left_key < ". (int) $node['left_key'] . " AND right_key > ". (int) $node['right_key'] . " AND location_id <> 0", "left_key DESC");

            if( !empty( $tree ) ){

                foreach( $tree as $key => $item ){

                    $string .= ', '. $item['name'];

                }

            }

            $string .= $this->getZipCodeHtml( (int) $node['location_id'] );



            return $string;



		}



		public function getZipCodeHtml( $post_id ){

            $zipcode = get_post_meta($post_id, 'zipcode', true);

            if($zipcode && !empty($zipcode)){

                return '||'.$zipcode;

            }else{

                return '';

            }

        }



		public function updateExtrafield( $location_id, $extrafields = array()){

			global $wpdb;

			$table = $wpdb->prefix.$this->table_nested;



			$data = $extrafields;

			$where = array(

				'location_id' => $location_id

			);



			$wpdb->update( $table, $data, $where );

		}



		public function get_data_location_inserted( $page, $number_page, $posts_per_page ){

			global $wpdb;

			$offset = ($page - 1) * $posts_per_page;



			$sql = "SELECT

				`location_id`,

				`parent_id`,

				`left_key`,

				`right_key`,

				`name`

			FROM

				{$wpdb->prefix}st_location_nested

			WHERE id <> 1	

			ORDER BY id ASC	

			LIMIT {$offset},

			{$posts_per_page}";



			$results = $wpdb->get_results( $sql, ARRAY_A);



			return $results;

		}



		public function get_data_location( ){

			global $wpdb;



			$table = $wpdb->prefix.'posts';

			$table_nested = $wpdb->prefix.'st_location_nested';



			$sql = "SELECT

				ID,

				post_parent,

				post_title,

				post_status,

				post_type

			FROM

				{$wpdb->prefix}posts

			WHERE

				post_type = 'location'

			AND post_status NOT IN ('auto-draft')

			AND post_parent IN ( SELECT location_id FROM {$table_nested})

			AND ID NOT IN (SELECT location_id FROM {$table_nested})

			ORDER BY post_parent ASC

			LIMIT 0, 10";



			$results = $wpdb->get_results( $sql, ARRAY_A);



			return array(

				'lists' => $results

			);

		}



		public function get_data_location_relationship( $page = 1 , $number_page = 1, $posts_per_page = 1){

			global $wpdb;



			$offset = ($page - 1) * $posts_per_page;



			$sql = "SELECT DISTINCT

				ID,

				mt.meta_value as location,

				post_type

			FROM

				{$wpdb->prefix}posts

			INNER JOIN {$wpdb->prefix}postmeta AS mt ON mt.post_id = {$wpdb->prefix}posts.ID

			AND mt.meta_key = 'multi_location'

			WHERE

				post_type IN ('st_hotel','hotel_room','st_cars','st_tours','st_rental','st_activity')

			AND mt.meta_value <> ''

			AND post_status NOT IN ('auto-draft')

			ORDER BY

				id ASC

			LIMIT {$offset}, {$posts_per_page}";



			$results = $wpdb->get_results( $sql, ARRAY_A);



			return array(

				'lists' => $results

			);

		}



		public function langcode_post_id($post_id){

			global $wpdb;

			if( function_exists('icl_object_id') ){



			    $query = $wpdb->prepare('SELECT language_code FROM ' . $wpdb->prefix . 'icl_translations WHERE element_id="%d"', $post_id);

			    $query_exec = $wpdb->get_row($query);

			 	if( !empty( $query_exec->language_code ) ){

			    	return $query_exec->language_code;

			 	}



			 	return 'en';

			}

			return 'en';



		}



		public function st_update_location_nested( $post_id ){

			if( get_post_type( $post_id ) == 'location' && get_post_field( 'post_status', $post_id ) != 'auto-draft'){


				global $wpdb;

				$ns = new Nested_set();

				$ns->setControlParams($wpdb->prefix.$this->table_nested);



				$location_id = (int) $post_id;

				$location_country = get_post_meta( $post_id, 'location_country', true);

				$name = get_the_title( $post_id );

				$parent_id = (int) get_post_field( 'post_parent', $post_id );



				$status = get_post_field( 'post_status', $post_id );



				$node =  $ns->getNodeWhere('location_id = '.$post_id);


				if( !empty( $node )){

					$target = $ns->getNodeWhere('location_id = '.$parent_id);



					$ns->setNodeAsLastChild($node, $target);

					$new_node = $ns->getNodeWhere('location_id = '.$post_id);

					$string = $this->getFullName( $new_node );


					$language = $this->langcode_post_id( $location_id );


					$this->updateExtrafield( $location_id, array('name' => $name, 'status' => $status, 'location_country' => $location_country, 'fullname' => $string, 'language' => $language));



				}else{
					if($parent_id == 0){

						$new_node = $this->setFirstChild($parent_id, $location_country, $location_id, $name, $status);



						$string = $this->getFullName( $new_node );

						$language = $this->langcode_post_id( (int) $new_node['location_id'] );

						$this->updateExtrafield( (int) $new_node['location_id'], array('fullname' => $string, 'language' => $language));


					}else{

						$new_node = $this->setChild($parent_id, $location_country, $location_id, $name, $status);

						$string = $this->getFullName( $new_node );
						$language = $this->langcode_post_id( (int) $new_node['location_id'] );


						$this->updateExtrafield( (int) $new_node['location_id'], array('fullname' => $string, 'language' => $language));

					}

				}

			}

		}



		public function st_delete_location_nested( $post_id ){

			if( get_post_type( $post_id ) == 'location'){

				global $wpdb;

				$ns = new Nested_set();

				$ns->setControlParams($wpdb->prefix.$this->table_nested);



				$parent = (int) get_post_field('post_parent', $post_id);

				$node_parent = $ns->getNodeWhere('location_id = '.$parent);



				$node =  $ns->getNodeWhere('location_id = '.$post_id);



				$node_child = $ns->getFirstChild( $node );



				if( !empty($node_child) ){

					$ns->setNodeAsLastChild($node_child, $node_parent);

				}



				$node =  $ns->getNodeWhere('location_id = '.$post_id);



				if( !empty( $node )){

					$ns->deleteNode( $node );

				}



			}

		}



	}

	new STUpgradeData;

}