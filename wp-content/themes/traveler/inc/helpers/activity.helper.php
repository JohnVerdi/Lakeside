<?php 
/**
*@since 1.1.8
**/
if(!class_exists('ActivityHelper')){
	class ActivityHelper{
		public function init(){
			add_action('wp_ajax_st_get_disable_date_activity',array(__CLASS__,'_get_disable_date'));
        	add_action('wp_ajax_nopriv_st_get_disable_date_activity',array(__CLASS__,'_get_disable_date'));
		}

		static function _get_activity_cant_order($check_in, $check_out){
			global $wpdb;

			$sql = "SELECT
				origin_id AS activity_id,
				mt.meta_value AS max_people,
				mt.meta_value - SUM(
					DISTINCT (adult_number + child_number + infant_number)
				) AS free_people
			FROM
				{$wpdb->prefix}st_order_item_meta
			INNER JOIN {$wpdb->prefix}postmeta AS mt ON mt.post_id = origin_id
			AND mt.meta_key = 'max_people'
			WHERE
				st_booking_post_type = 'st_activity'
				AND status NOT IN ('trash', 'canceled')
			AND (
				(
					STR_TO_DATE('{$check_in}', '%Y-%m-%d') < STR_TO_DATE(check_in, '%m/%d/%Y')
					AND STR_TO_DATE('{$check_out}', '%Y-%m-%d') > STR_TO_DATE(check_out, '%m/%d/%Y')
				)
				OR (
					STR_TO_DATE('{$check_in}', '%Y-%m-%d') BETWEEN STR_TO_DATE(check_in, '%m/%d/%Y')
					AND STR_TO_DATE(check_out, '%m/%d/%Y')
				)
				OR (
					STR_TO_DATE('{$check_out}', '%Y-%m-%d') BETWEEN STR_TO_DATE(check_in, '%m/%d/%Y')
					AND STR_TO_DATE(check_out, '%m/%d/%Y')
				)
			)
			GROUP BY origin_id
			HAVING
			max_people - SUM(
					DISTINCT (adult_number + child_number + infant_number)
				) <= 0";
			
			$result = $wpdb->get_results($sql, ARRAY_A);

			if(is_array($result) && count($result)){
				$list_date = array();
				foreach($result as $key => $val){
					$list_date[] = $val['activity_id'];
				}
				return $list_date;
			}
			return '';
		}

		static function _get_free_peple_daily($activity_id, $check_in, $order_item_id = ''){
			$activity_id = apply_filters('st_get_post_id_origin', $activity_id);
			global $wpdb;
			$string = "";
			if(!empty($order_item_id)){
				$string = " AND order_item_id NOT IN ('{$order_item_id}') ";
			}
			$sql = "SELECT
				origin_id AS activity_id,
				mt.max_people AS max_people,
				mt.max_people - SUM(adult_number + child_number + infant_number) AS free_people
			FROM
				{$wpdb->prefix}st_order_item_meta
			INNER JOIN {$wpdb->prefix}st_activity AS mt ON mt.post_id = origin_id
			WHERE
				origin_id = '{$activity_id}'
			AND status NOT IN ('trash', 'canceled')
			and mt.type_activity = 'daily_activity'
			AND (
					STR_TO_DATE('{$check_in}', '%Y-%m-%d') = STR_TO_DATE({$wpdb->prefix}st_order_item_meta.check_in, '%m/%d/%Y')
			)
			{$string}
			GROUP BY
				origin_id";

			$result = $wpdb->get_row($sql, ARRAY_A);
			return $result;	
		}

		static function _get_free_peple_special($activity_id, $check_in, $check_out, $order_item_id = ''){
			$activity_id = apply_filters('st_get_post_id_origin', $activity_id);
			global $wpdb;
			$string = "";
			if(!empty($order_item_id)){
				$string = " AND order_item_id NOT IN ('{$order_item_id}') ";
			}
			$sql = "SELECT
				activity_id AS activity_id,
				mt.max_people AS max_people,
				mt.max_people - SUM(adult_number + child_number + infant_number) AS free_people
			FROM
				{$wpdb->prefix}st_order_item_meta
			INNER JOIN {$wpdb->prefix}st_activity AS mt ON mt.post_id = origin_id
			WHERE
				origin_id = '{$activity_id}'
			AND status NOT IN ('trash', 'canceled')
			AND st_booking_post_type = 'st_activity'
			AND mt.type_activity = 'specific_date'
			AND (
				(
					STR_TO_DATE('{$check_in}', '%m/%d/%Y') < STR_TO_DATE({$wpdb->prefix}st_order_item_meta.check_in, '%m/%d/%Y')
					AND STR_TO_DATE('{$check_out}', '%m/%d/%Y') > STR_TO_DATE({$wpdb->prefix}st_order_item_meta.check_out, '%m/%d/%Y')
				)
				OR (
					STR_TO_DATE('{$check_in}', '%m/%d/%Y') BETWEEN STR_TO_DATE({$wpdb->prefix}st_order_item_meta.check_in, '%m/%d/%Y')
					AND STR_TO_DATE({$wpdb->prefix}st_order_item_meta.check_out, '%m/%d/%Y')
				)
				OR (
					STR_TO_DATE('{$check_out}', '%m/%d/%Y') BETWEEN STR_TO_DATE({$wpdb->prefix}st_order_item_meta.check_in, '%m/%d/%Y')
					AND STR_TO_DATE({$wpdb->prefix}st_order_item_meta.check_out, '%m/%d/%Y')
				)
			)
			{$string}
			GROUP BY
				origin_id";
			$result = $wpdb->get_row($sql, ARRAY_A);
			
			return $result;	
		}

		static function _get_disable_date(){
			
			$disable = array();

			$activity_id = STInput::request('activity_id','');
			$activity_id = apply_filters('st_get_post_id_origin', $activity_id);
			if(empty($activity_id)){
				echo json_encode($disable);
				die();
			}
			$year = STInput::request('year', date('Y'));

			global $wpdb;

			$sql = "SELECT
				origin_id as activity_id,
				check_in_timestamp as check_in,
				check_out_timestamp as check_out,
				adult_number,
				child_number,
				infant_number
				FROM
					{$wpdb->prefix}st_order_item_meta
				WHERE
					st_booking_post_type = 'st_activity'
				AND origin_id = '{$activity_id}'
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
				$min_max = self::_get_minmax($activity_id, $year);
				if(is_array($min_max) && count($min_max)){
					$max_people = intval(get_post_meta($activity_id, 'max_people', true));
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

		static function _get_minmax($activity_id, $year){
			global $wpdb;
			$activity_id = apply_filters('st_get_post_id_origin', $activity_id);
			$sql = "SELECT
			MIN(check_in_timestamp) as check_in,
			MAX(check_out_timestamp) as check_out
			FROM
				{$wpdb->prefix}st_order_item_meta
			WHERE
				st_booking_post_type = 'st_activity'
			AND status NOT IN ('trash', 'canceled')
			AND origin_id = '{$activity_id}'
			AND YEAR (
				FROM_UNIXTIME(check_in_timestamp)
			) = {$year}
			AND YEAR (
				FROM_UNIXTIME(check_out_timestamp)
			) = {$year}";

			$min_max = $wpdb->get_row($sql, ARRAY_A);
			return $min_max;
		}

		static function checkAvailableActivity($activity_id, $check_in, $check_out){
			global $wpdb;
			$type_activity = get_post_meta($activity_id, 'type_activity', true);

			$activities = AvailabilityHelper::_getdataActivityEachDate($activity_id, $check_in, $check_out);
			if(is_array($activities) && count($activities)){
				if(count($activities) == 1){
					foreach($activities as $activity){
						if($activity->status == 'available'){
							return true;
						}else{
							return false;
						}
					}
				}else{
					return false;
				}
				
			}else{
				if(($type_activity == 'daily_activity' || !$type_activity) && $check_in == $check_out){
					return true;
				}else{
					return false;
				}
			}
		}

		static function _get_free_peple($activity_id, $check_in, $check_out, $order_item_id = ''){
			if(!TravelHelper::checkTableDuplicate('st_activity')) return '';
			global $wpdb;
			$string = "";
			if(!empty($order_item_id)){
				$string = " AND order_item_id NOT IN ('{$order_item_id}') ";
			}
			$sql = "SELECT
				st_booking_id AS activity_id,
				mt.max_people AS max_people,
				mt.max_people - SUM(adult_number + child_number + infant_number) AS free_people
			FROM
				{$wpdb->prefix}st_order_item_meta
			INNER JOIN {$wpdb->prefix}st_activity AS mt ON mt.post_id = st_booking_id
			WHERE
				st_booking_id = '{$activity_id}'
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
	}

	$activityhelper = new ActivityHelper();
	$activityhelper->init();

}
?>