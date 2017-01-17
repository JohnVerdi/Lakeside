<?php 
/**
*@since 1.1.8
**/
if(!class_exists('TourHelper')){
	class TourHelper{
		public function init(){
			add_action('wp_ajax_st_get_disable_date_tour',array(__CLASS__,'_get_disable_date'));
        	add_action('wp_ajax_nopriv_st_get_disable_date_tour',array(__CLASS__,'_get_disable_date'));
		}

		static function _get_tour_cant_order($check_in){
			if(!TravelHelper::checkTableDuplicate('st_tours')) return '';
			global $wpdb;

			$sql = "SELECT
				st_booking_id AS tour_id,
				mt.meta_value AS max_people,
				mt.meta_value - SUM(
					DISTINCT (adult_number + child_number + infant_number)
				) AS free_people
			FROM
				{$wpdb->prefix}st_order_item_meta
			INNER JOIN {$wpdb->prefix}postmeta AS mt ON mt.post_id = st_booking_id
			AND mt.meta_key = 'max_people'
			WHERE
				st_booking_post_type = 'st_tours'
			AND (
				STR_TO_DATE('{$check_in}', '%Y-%m-%d') = STR_TO_DATE(check_in, '%Y-%m-%d')
			)
			AND status NOT IN ('trash', 'canceled')
			GROUP BY st_booking_id
			HAVING
			max_people - SUM(
					DISTINCT (adult_number + child_number + infant_number)
				) <= 0";

			$result = $wpdb->get_col($sql, 0);
			$list_date = array();
			if(is_array($result) && count($result)){
				$list_date = $result;
			}
			return $list_date;
		}

		static function checkAvailableTour($tour_id, $check_in, $check_out){
			global $wpdb;
			$type_tour = get_post_meta($tour_id, 'type_tour', true);

			$tours = AvailabilityHelper::_getdataTourEachDate($tour_id, $check_in, $check_out);
			if(is_array($tours) && count($tours)){
				if(count($tours) == 1){
					foreach($tours as $tour){
						if($tour->status == 'available'){
							return true;
						}else{
							return false;
						}
					}
				}else{
					return false;
				}
				
			}else{
				if(($type_tour == 'daily_tour' || !$type_tour) && $check_in == $check_out){
					return true;
				}else{
					return false;
				}
			}
		}
		static function _get_free_peple($tour_id, $check_in, $check_out, $order_item_id = ''){
			if(!TravelHelper::checkTableDuplicate('st_tours')) return '';
			global $wpdb;
			$string = "";
			if(!empty($order_item_id)){
				$string = " AND order_item_id NOT IN ('{$order_item_id}') ";
			}
			$sql = "SELECT
				st_booking_id AS tour_id,
				mt.max_people AS max_people,
				mt.max_people - SUM(adult_number + child_number + infant_number) AS free_people
			FROM
				{$wpdb->prefix}st_order_item_meta
			INNER JOIN {$wpdb->prefix}st_tours AS mt ON mt.post_id = st_booking_id
			WHERE
				st_booking_id = '{$tour_id}'
			AND (
					UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME('{$check_in}'), '%Y-%m-%d')) = UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_in_timestamp), '%Y-%m-%d')) AND
					UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME('{$check_out}'), '%Y-%m-%d')) = UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_out_timestamp), '%Y-%m-%d'))
			)
			AND status NOT IN ('trash', 'canceled')
			{$string}
			GROUP BY
				st_booking_id";

			$result = $wpdb->get_row($sql, ARRAY_A);
			
			return $result;	
		}

		static function _get_disable_date(){

			$disable = array();	

			if(!TravelHelper::checkTableDuplicate('st_tours')){
				echo json_encode($disable);
				die();
			}

			$tour_id = STInput::request('tour_id','');
			if(empty($tour_id)){
				echo json_encode($disable);
				die();
			}
			$year = STInput::request('year', date('Y'));

			global $wpdb;

			$sql = "SELECT
				st_booking_id AS tour_id,
				check_in_timestamp AS check_in,
				check_out_timestamp AS check_out,
				adult_number,
				child_number,
				infant_number
			FROM
				{$wpdb->prefix}st_order_item_meta
			INNER JOIN {$wpdb->prefix}st_tours AS mt ON mt.post_id = st_booking_id
			WHERE
				st_booking_post_type = 'st_tours'
			AND mt.type_tour = 'daily_tour'
			AND st_booking_id = '{$tour_id}'
			AND status NOT IN ('trash', 'canceled')
			AND YEAR (
				FROM_UNIXTIME(check_in_timestamp)
			) = {$year}
			AND YEAR (
				FROM_UNIXTIME(check_out_timestamp)
			) = {$year}";

			$result = $wpdb->get_results($sql, ARRAY_A);
			if(is_array($result) && count($result)){
				$list_date = array();
				foreach($result as $key => $val){
					$list_date[] = array(
						'check_in' => $val['check_in'],
						'check_out' => $val['check_out'],
						'adult_number' => $val['adult_number'],
						'child_number' => $val['child_number'],
						'infant_number' => $val['infant_number'],
					);
				}
			}

			if(isset($list_date) && count($list_date)){
				$min_max = self::_get_minmax($tour_id, $year);
				if(is_array($min_max) && count($min_max)){
					$max_people = intval(get_post_meta($tour_id, 'max_people', true));
					for($i = intval($min_max['check_in']); $i<= intval($min_max['check_out']); $i = strtotime('+1 day', $i)){
						$people = 0;
						foreach($result as $key => $date){
							
							if($i == intval($date['check_in'])){
								$people += (intval($date['adult_number']) + intval($date['child_number']) + intval($date['infant_number']));
							}
						}
						if($people >= $max_people)
							$disable[] = date(TravelHelper::getDateFormat(),$i);
					}
				}
			}
			if(count($disable)){
				echo json_encode($disable);
				die();
			}

			echo json_encode($disable);
			die();
		}

		static function _get_minmax($tour_id, $year){
			if(!TravelHelper::checkTableDuplicate('st_tours')) return ''; // st_tour
			global $wpdb;

			$sql = "SELECT
				MIN(check_in_timestamp) AS check_in,
				MAX(check_out_timestamp) AS check_out
			FROM
				{$wpdb->prefix}st_order_item_meta
			INNER JOIN {$wpdb->prefix}st_tours AS mt ON mt.post_id = st_booking_id
			WHERE
				st_booking_post_type = 'st_tours'
			AND mt.type_tour = 'daily_tour'
			AND st_booking_id = '{$tour_id}'
			AND YEAR (
				FROM_UNIXTIME(check_in_timestamp)
			) = {$year}
			AND YEAR (
				FROM_UNIXTIME(check_out_timestamp)
			) = {$year}
			AND status NOT IN ('trash', 'canceled')";

			$min_max = $wpdb->get_row($sql, ARRAY_A);
			return $min_max;
		}

		static function _getAllTourID(){
			global $wpdb;
			$sql = "
			SELECT
				ID
			FROM
				{$wpdb->prefix}posts
			WHERE
				post_type = 'st_tours'
			AND post_status = 'publish'";

			$results = $wpdb->get_col($sql, 0);
			return $results;
		}

		static function _tourValidate($check_in){
			global $wpdb;
			$cant_book = self::_get_tour_cant_order($check_in);
			$tours = self::_getAllTourID();
			$results = array();
			$today = date('Y-m-d');
			if(is_array($tours) && count($tours)){
				foreach($tours as $tour){
					$type_tour = get_post_meta($tour, 'type_tour', true);
					$data_tour = AvailabilityHelper::_getdataTourEachDate($tour, strtotime($check_in), strtotime($check_in));
					$booking_period = intval(get_post_meta($tour, 'tours_booking_period', true));
					if(is_array($data_tour) && count($data_tour)){
						$full = 0;
						foreach($data_tour as $key => $val){
							$period = TravelHelper::dateDiff($today, date('Y-m-d', $val->check_in));
							
							if($val->status != 'available' || ($period < $booking_period)){
								$full += 1;
							}
						}
						if($full == count($data_tour)){
							$results[] = $tour;
						}
					}else{
						if($type_tour != 'daily_tour'){
							$results[] = $tour;
						}
					}
				}
			}
			if(count($results)){
				$cant_book = array_unique(array_merge($cant_book, $results));
			}
			return $cant_book;
		}
		
	}

	

	$tourhelper = new TourHelper();
	$tourhelper->init();

}
?>