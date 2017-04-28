<?php 
/**
*@since 1.1.9
**/
if(!class_exists('STAvailability')){
	class STAvailability{
		public $table = 'st_availability';
		public $column = array();
		public $st_upgrade_availability = 0;
		public $allow_version = false;
		public function __construct(){
			add_action('st_traveler_do_upgrade_table', array(&$this, '_action_check_upgrade_availability'));
			add_action('after_setup_theme',array(&$this,'_check_table_availability'), 10);
			add_action('after_setup_theme',array(&$this,'_check_upgrade_availability'), 50);
		}
		public function _action_check_upgrade_availability(){
			$this->st_upgrade_availability = 1;
			$this->allow_version = true;
			$this->_check_table_availability();
			$this->_check_upgrade_availability();
		}
		public function _check_table_availability(){
			$dbhelper = new DatabaseHelper('1.0.0');
			$dbhelper->setTableName($this->table);
			$column = array(
				'id'=>array(
	                'type' => 'bigint',
	                'length' => 9,
	                'AUTO_INCREMENT' => TRUE
	            ),
	            'post_id' => array(
					'type' => 'INT'
	            ),
	            'post_type' => array(
					'type' => 'varchar',
					'length' => 255
	            ),
	            'check_in'=> array(
					'type' => 'varchar',
					'length' => 255
	            ),
	            'check_out' => array(
					'type' => 'varchar',
					'length' => 255
	            ),
	            'number' => array(
	                'type' => 'varchar',
	                'length' => 255
	            ),
	            'price' => array(
	                'type' => 'varchar',
	                'length' => 255
	            ),
	            'adult_price' => array(
	                'type' => 'varchar',
	                'length' => 255
	            ),
	            'child_price' => array(
	                'type' => 'varchar',
	                'length' => 255
	            ),
	            'infant_price' => array(
	                'type' => 'varchar',
	                'length' => 255
	            ),
	            'status' => array(
	                'type' => 'varchar',
	                'length' => 255
	            ),
	            'groupday' => array(
	                'type' => 'INT'
	            ),
	            'priority' => array(
	                'type' => 'INT'
	            )
			);
			$this->column = $column;
			$dbhelper->setDefaultColums($column);
			$dbhelper->check_meta_table_is_working('availability_table_version');
		}
		public function _check_upgrade_availability(){
			$complete = get_option('st_upgrade_availability');
			if(!$complete || $complete == 0 || $this->st_upgrade_availability == 1 || $this->allow_version){
				$this->_upgradeData();
			}
		}

		public function isset_table(){
			global $wpdb;
			$table = $wpdb->prefix.$this->table;
			if($wpdb->get_var("SHOW TABLES LIKE '{$table}'") != $table){
				return false;
			}
			return true;
		}

		public function _deleteTable(){
        	global $wpdb;
        	$table = $wpdb->prefix.$this->table;
        	$wpdb->query("DROP TABLE {$table}");
        }

		public function _upgradeData(){
			global $wpdb;
        	$table = $wpdb->prefix.$this->table;
        	if($this->allow_version){
        		if($this->isset_table()){
        			$this->_deleteTable();
        			$this->_check_table_availability();
        		}	
        	}

        	$this->_insertCustomPriceHotelRoom();
        	$this->_insertCustomPriceTour();
        	$this->_insertCustomPriceActivity();

		}

		public function _insertCustomPriceHotelRoom(){
			$complete = 1;
			global $wpdb;
			$table = $wpdb->prefix.$this->table;
			$sql = "
			SELECT
				{$wpdb->prefix}st_price.*
			FROM
				{$wpdb->prefix}st_price
			INNER JOIN {$wpdb->prefix}posts AS mt ON mt.ID = {$wpdb->prefix}st_price.post_id
			AND mt.post_type = 'hotel_room'";

			$results = $wpdb->get_results($sql);
			if(is_array($results) && count($results)){
				foreach($results as $key => $val){
					$data = array(
						'post_id' => $val->post_id,
						'post_type' => 'hotel_room',
						'check_in' => strtotime($val->start_date),
						'check_out' => strtotime($val->end_date),
						'number' => 1,
						'price' => $val->price,
						'status' => 'available',
						'groupday' => '0',
						'priority' => $val->priority
					);
					$insert = $wpdb->insert($table, $data);
					if(is_wp_error($insert)){
						$complete = 0;
						break;
					}
				}
			}
			update_option('st_upgrade_availability', $complete);
		}

		public function _insertCustomPriceTour(){
			$complete = 1;
			if(TravelHelper::isset_table('st_tours')){
				
				global $wpdb;
				$table = $wpdb->prefix.$this->table;
				$sql = " SELECT
					post_id,
					adult_price,
					child_price,
					infant_price,
					check_in,
					check_out, type_tour
				FROM
					{$wpdb->prefix}st_tours
				WHERE
					type_tour = 'specific_date'";
					
				$results = $wpdb->get_results($sql);
				if(is_array($results) && count($results)){
					foreach($results as $key => $val){
						if(!empty($val->check_in) && !empty($val->check_out)){
							$data = array(
								'post_id' => $val->post_id,
								'post_type' => 'st_tours',
								'check_in' => strtotime($val->check_in),
								'check_out' => strtotime($val->check_out),
								'number' => 1,
								'adult_price' => floatval($val->adult_price),
								'child_price' => floatval($val->child_price),
								'infant_price' => floatval($val->infant_price),
								'status' => 'available',
								'groupday' => '1',
								'priority' => 0
							);

							$insert = $wpdb->insert($table, $data);
							if(is_wp_error($insert)){
								$complete = 0;
								break;
							}
						}
						
					}
				}
			}else{
				$complete = 0;
			}
			update_option('st_upgrade_availability', $complete);
		}

		public function _insertCustomPriceActivity(){
			$complete = 1;
			if(TravelHelper::isset_table('st_activity')){
				
				global $wpdb;
				$table = $wpdb->prefix.$this->table;
				$sql = "
				SELECT
					post_id,
					adult_price,
					child_price,
					infant_price,
					check_in,
					check_out, type_activity
				FROM
					{$wpdb->prefix}st_activity
				WHERE
					type_activity = 'specific_date'";

				$results = $wpdb->get_results($sql);
				if(is_array($results) && count($results)){
					foreach($results as $key => $val){
						if(!empty($val->check_in) && !empty($val->check_out)){
							$data = array(
								'post_id' => $val->post_id,
								'post_type' => 'st_activity',
								'check_in' => strtotime($val->check_in),
								'check_out' => strtotime($val->check_out),
								'number' => 1,
								'adult_price' => floatval($val->adult_price),
								'child_price' => floatval($val->child_price),
								'infant_price' => floatval($val->infant_price),
								'status' => 'available',
								'groupday' => '1',
								'priority' => 0
							);

							$insert = $wpdb->insert($table, $data);
							if(is_wp_error($insert)){
								$complete = 0;
								break;
							}
						}
						
					}
				}
			}else{
				$complete = 0;
			}
			update_option('st_upgrade_availability', $complete);
		}
	}

	$st_avaibility = new STAvailability();
}
?>