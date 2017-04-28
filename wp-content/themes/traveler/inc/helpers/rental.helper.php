<?php 
/**
*@since 1.1.8
**/
if(!class_exists('RentalHelper')){
	class RentalHelper{

		public function init(){
			add_action('wp_ajax_st_get_disable_date_rental',array(__CLASS__,'_get_disable_date'));
            add_action('wp_ajax_nopriv_st_get_disable_date_rental',array(__CLASS__,'_get_disable_date'));
            add_action('wp_ajax_st_get_availability_rental_single', array(&$this, '_get_availability_rental_single'));
			add_action('wp_ajax_nopriv_st_get_availability_rental_single', array(&$this, '_get_availability_rental_single'));
		}
		static function _get_full_ordered($rental_id, $month, $month2, $year, $year2){
			$date1 = $month.'/'.'01'.'/'.$year;
			$date2 = strtotime($year2.'-'.$month2.'-01');
			$date2 = date('m/t/Y',$date2);
			if(!TravelHelper::checkTableDuplicate('st_hotel')) return '';
			global $wpdb;
			$sql = "
			SELECT  
			st_booking_id,
			check_in_timestamp,
			check_out_timestamp
			FROM {$wpdb->prefix}st_order_item_meta
			WHERE st_booking_id = '{$rental_id}'
			AND st_booking_post_type = 'st_rental'
			AND (
				(
					STR_TO_DATE('{$date1}', '%m/%d/%Y') < STR_TO_DATE(check_in, '%m/%d/%Y')
					AND STR_TO_DATE('{$date2}', '%m/%d/%Y') > STR_TO_DATE(check_out, '%m/%d/%Y')
				)
				OR (
					STR_TO_DATE('{$date1}', '%m/%d/%Y') BETWEEN STR_TO_DATE(check_in, '%m/%d/%Y')
					AND STR_TO_DATE(check_out, '%m/%d/%Y')
				)
				OR (
					STR_TO_DATE('{$date2}', '%m/%d/%Y') BETWEEN STR_TO_DATE(check_in, '%m/%d/%Y')
					AND STR_TO_DATE(check_out, '%m/%d/%Y')
				)
			)
			AND status NOT IN ('trash', 'canceled')";
			$result = $wpdb->get_results($sql, ARRAY_A);

			if(is_array($result) && count($result)){
				return $result;
			}
			return '';		
		}

		static function _get_min_max_date_ordered($rental_id, $year, $year2){
			if(!TravelHelper::checkTableDuplicate('st_rental')) return '';
			global $wpdb;
			$sql = "SELECT 
			MIN(check_in_timestamp) as min_date,
			MAX(check_out_timestamp) as max_date
			FROM {$wpdb->prefix}st_order_item_meta
			WHERE st_booking_id = '{$rental_id}'
			AND st_booking_post_type = 'st_rental'
			AND (YEAR(FROM_UNIXTIME(check_in_timestamp)) = {$year}
			OR YEAR(FROM_UNIXTIME(check_out_timestamp)) = {$year2})
			AND status NOT IN ('trash', 'canceled')
			GROUP BY st_booking_id";
			$result = $wpdb->get_row($sql, ARRAY_A);

			if(is_array($result) && count($result))
				return $result;
			return '';
		}
		public function _get_availability_rental_single(){
			$list_date = array();
			$rental_id = STInput::request('post_id', '');
			$check_in = STInput::request('start', '');
			$check_out = STInput::request('end', '');

			$allow_full_day = get_post_meta($rental_id, 'allow_full_day', true);
			if(!$allow_full_day || $allow_full_day == '') $allow_full_day = 'on';

			$year = date('Y', $check_in);
			if(empty($year)) $year = date('Y');
			$year2 = date('Y', $check_out);
			if(empty($year2)) $year2 = date('Y');

			$month = date('m', $check_in);
			if(empty($month)) $month = date('m');

			$month2 = date('m', $check_out);
			if(empty($month2)) $month2 = date('m');

			$result = RentalHelper::_get_full_ordered($rental_id, $month, $month2, $year, $year2);
			
			$number_rental = intval(get_post_meta($rental_id, 'rental_number', true ));
			$min_max = RentalHelper::_get_min_max_date_ordered($rental_id, $year, $year2);
			if(is_array($min_max) && count($min_max) && is_array($result) && count($result)){
				$disable = array();
				$array_fist_half_day  =array();
				$array_last_half_day  =array();
				for($i = intval($min_max['min_date']); $i<= intval($min_max['max_date']); $i = strtotime('+1 day', $i)){
					$num_rental = 0;

					foreach($result as $key => $date){
						/*if($i >= intval($date['check_in_timestamp']) && $i <= intval($date['check_out_timestamp'])){
							$num_rental += 1;
						}*/
						if($allow_full_day == 'on'){
							if($i >= intval($date['check_in_timestamp']) && $i <= intval($date['check_out_timestamp'])){
								$num_rental += 1;
							}
						}else{
							if($i > intval($date['check_in_timestamp']) && $i < intval($date['check_out_timestamp'])){
								$num_rental += 1;
							}
							if($i == intval($date['check_in_timestamp'])){
								$array_fist_half_day[$i] = 1;
							}
							if($i == intval($date['check_out_timestamp'])){
								$array_last_half_day[$i] = 1;
							}
						}
					}

					$disable[$i] = $num_rental;
				}
				if(count($disable)){
					foreach ($disable as $key => $num_rental) {
						if(intval($num_rental) >= $number_rental)
							$list_date[] = date(TravelHelper::getDateFormat(),$key);
					}
				}
				$list_date_fist_half_day = array();
				$list_date_last_half_day = array();

				if(count($array_fist_half_day)){
					foreach ($array_fist_half_day as $key => $num_rental) {
						if(intval($num_rental) >= $number_rental)
						$list_date_fist_half_day[] = date(TravelHelper::getDateFormat(),$key);
					}
				}
				if(count($array_last_half_day)){
					foreach ($array_last_half_day as $key => $num_rental) {
						if(intval($num_rental) >= $number_rental)
						$list_date_last_half_day[] = date(TravelHelper::getDateFormat(),$key);
					}
				}
			}
			$list_date_2 = AvailabilityHelper::_getDisableCustomDateRental($rental_id, $month, $month2, $year, $year2);
		
			
			$date1 = strtotime($year.'-'.$month.'-01');
			$date2 = strtotime($year2.'-'.$month2.'-01');
			$date2 = strtotime(date('Y-m-t',$date2));
			$today = strtotime(date('Y-m-d'));
			$return = array();
			$booking_period = intval(get_post_meta($rental_id, 'rentals_booking_period', true));
			
			for($i = $date1; $i<= $date2; $i = strtotime('+1 day', $i)){
				$period = TravelHelper::dateDiff(date('Y-m-d',$today), date('Y-m-d',$i));
				$d = date(TravelHelper::getDateFormat(), $i);
				if(in_array($d, $list_date) or ( in_array($d, $list_date_fist_half_day) and in_array($d, $list_date_last_half_day) ) ){
					$return[] = array(
						'start' => date('Y-m-d', $i),
						'date' => date('Y-m-d', $i),
						'day' => date('d', $i),
						'status' => 'booked',
					);
				}
				else{
					if($i < $today){
						// past
						$return[] = array(
							'start' => date('Y-m-d', $i),
							'date' => date('Y-m-d', $i),
							'day' => date('d', $i),
							'status' => 'past'
						);
					}else{
						// disabled
						if(in_array($d, $list_date_2)){
							$return[] = array(
								'start' => date('Y-m-d', $i),
								'date' => date('Y-m-d', $i),
								'day' => date('d', $i),
								'status' => 'disabled'
							);
						}else{
							if($period < $booking_period){
								$return[] = array(
									'start' => date('Y-m-d', $i),
									'date' => date('Y-m-d', $i),
									'day' => date('d', $i),
									'status' => 'disabled'
								);
							}else if(in_array($d, $list_date_fist_half_day)){
								$return[] = array(
									'start' => date('Y-m-d', $i),
									'date' => date('Y-m-d', $i),
									'day' => date('d', $i),
									'status' => 'available_allow_fist',
									'price'	=> TravelHelper::format_money(STPrice::getRentalPriceOnlyCustomPrice($rental_id, $i, strtotime('+1 day',$i)))
								);
							}else if(in_array($d, $list_date_last_half_day)){
								$return[] = array(
									'start' => date('Y-m-d', $i),
									'date' => date('Y-m-d', $i),
									'day' => date('d', $i),
									'status' => 'available_allow_last',
									'price'	=> TravelHelper::format_money(STPrice::getRentalPriceOnlyCustomPrice($rental_id, $i, strtotime('+1 day',$i)))
								);
							}else{
								$return[] = array(
									'start' => date('Y-m-d', $i),
									'date' => date('Y-m-d', $i),
									'day' => date('d', $i),
									'status' => 'avalable',
									'price'	=> TravelHelper::format_money(STPrice::getRentalPriceOnlyCustomPrice($rental_id, $i, strtotime('+1 day',$i)))
								);
							}
						}
					}
				}
			}


			echo json_encode($return);
			die;
		}

		static function check_day_cant_order($rental_id, $check_in, $check_out, $num_rental){
			global $wpdb;

			$number_rental = intval(get_post_meta($rental_id, 'rental_number', true));

			$sql = "
			SELECT
				`check_in`,
				`check_out`,
				`number`,
				`status`
			FROM
				{$wpdb->prefix}st_availability
			WHERE
				(
					(
						STR_TO_DATE('{$check_in}', '%Y-%m-%d') < DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d')
						AND STR_TO_DATE('{$check_out}', '%Y-%m-%d') > DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d')
					)
					OR (
						STR_TO_DATE('{$check_in}', '%Y-%m-%d') BETWEEN DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d')
						AND DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d')
					)
					OR (
						STR_TO_DATE('{$check_out}', '%Y-%m-%d') BETWEEN DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d')
						AND DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d')
					)
				)
			AND post_id = '{$rental_id}'";
			
			$results = $wpdb->get_results($sql);
			
			$check_in = strtotime($check_in);
			$check_out = strtotime($check_out);
			if(is_array($results) && count($results)){
				for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
					$in_date = false;
					$status = 'available';
					foreach($results as $key => $val){
						if($i >= $val->check_in && $i <= $val->check_out){
							$status = $val->status;
							if(!$in_date){
								$in_date = true;
							}
						}
					}
					if($in_date){
						if($status != 'available' || $number_rental < $num_rental){
							return false;
						}
					}else{
						if($number_rental < $num_rental){
							return false;
						}
					}
				}
				return true;
			}else{
				if($number_rental < $num_rental){
					return false;
				}else{
					return true;
				}
			}

		}
		static function _rentalValidate($check_in, $check_out, $adult_num, $child_num, $num_rental){
			$cant_book = array();
			$rentals = RentalHelper::_getAllRentalID();
			if(is_array($rentals) && count($rentals)){
				foreach($rentals as $rental){
					$rental_cant_book = RentalHelper::_rentalValidateByID($rental, strtotime($check_in), strtotime($check_out), $adult_num, $child_num, $num_rental);
					$rental_full_ordered = RentalHelper::_get_rental_cant_book_by_id($rental, date('Y-m-d',strtotime($check_in)), date('Y-m-d',strtotime($check_out)), $num_rental);
					
					if(intval($rental_cant_book) > 0){
						$cant_book[] = $rental_cant_book;
					}
					if(intval($rental_full_ordered) > 0){
						$cant_book[] = $rental_full_ordered;
					}
				}
			}
			return $cant_book;
		}

		static function _rentalValidateByID($rental_id, $check_in, $check_out, $adult_num, $child_num, $num_rental){
			global $wpdb;
			$number_rental = intval(get_post_meta($rental_id, 'rental_number', true));

			$adult_number = intval(get_post_meta($rental_id, 'rental_max_adult', true));
			$child_number = intval(get_post_meta($rental_id, 'rental_max_children', true));
			
			if($adult_number < $adult_num || $child_number < $child_num){ // overload people
				return $rental_id;
			}else{
				$data_rental = AvailabilityHelper::_getdataRental($rental_id, $check_in, $check_out);
				
				if(is_array($data_rental) && count($data_rental)){
					$start = $check_in;
					$end = $check_out;
					for($i = $start; $i <= $end; $i = strtotime('+1 day', $i)){
						$in_date = false;
						$status = 'available';
						foreach($data_rental as $key => $val){
							if($i == $val->check_in && $i == $val->check_out){ //in date
								$status = $val->status;
								if(!$in_date) $in_date = true;
							}
						}
						if($in_date){
							if($status == 'available'){
								if($num_rental > $number_rental){
									return  $rental_id;
								}
							}else{
								return  $rental_id;
							}
						}else{
							if($num_rental > $number_rental){
								return $rental_id;
							}
						}
					}
				}else{ // dont have custom price

					if($num_rental > $number_rental){
						return $rental_id;
					}
				}
			}

			return '';
		}

		static function _get_rental_cant_book_by_id($rental_id = '', $check_in = '', $check_out = '', $num_rental = 0){
			if(!TravelHelper::checkTableDuplicate('st_rental')) return '';
			global $wpdb;
			$sql = "
			SELECT
				check_in_timestamp AS check_in,
				check_out_timestamp AS check_out
			FROM
				{$wpdb->prefix}st_order_item_meta
			WHERE
				(
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
			AND st_booking_id = '{$rental_id}'
			AND st_booking_post_type = 'st_rental'
			AND status NOT IN ('trash', 'canceled')";

			$result = $wpdb->get_results($sql);
			
			if(is_array($result) && count($result)){
				$check_in = strtotime($check_in);
				$check_out = strtotime($check_out);
				$number_rental = intval(get_post_meta($rental_id, 'rental_number', true));
				
				for($i = intval($check_in); $i <= intval($check_out); $i = strtotime('+1 day', $i)){
					$num_rental = 0;
					foreach($result as $key => $date){
						if(isset($date->check_in) && isset($date->check_out)){
							if($i >= intval($date->check_in) && $i <= intval($date->check_out)){
								$num_rental += 1;
							}
						}
					}
					if($num_rental >= $number_rental){
						return $rental_id;
					}
				}
			}
			return '';
		}
		static function _getAllRentalID(){
			global $wpdb;
			if(defined('ICL_LANGUAGE_CODE')){
				$sql = "SELECT
					{$wpdb->prefix}posts.ID
				FROM
					{$wpdb->prefix}posts
				JOIN {$wpdb->prefix}icl_translations t ON {$wpdb->prefix}posts.ID = t.element_id
				AND t.element_type = 'post_st_rental'
				JOIN {$wpdb->prefix}icl_languages l ON t.language_code = l. CODE
				AND l.active = 1
				where post_type = 'st_rental'
				and post_status = 'publish'
				AND t.language_code = '".ICL_LANGUAGE_CODE."'";
			}else{
				$sql = "SELECT
					{$wpdb->prefix}posts.ID
				FROM
					{$wpdb->prefix}posts
				where  post_type = 'st_rental'
				and post_status = 'publish'";
			}

			$results = $wpdb->get_col($sql, 0);
			return $results;
		}
		
		static function get_list_not_in($room_number){
			// get rentals don't have any room
			// step 1 get all rental ID ; 
			global $wpdb ; 
			$array_all =  array();
			$query1 = array('post_type' =>'st_rental' , 'posts_per_page'=>-1);
			$posts1 = get_posts($query1);
			wp_reset_postdata(); 
			if(!empty($posts1) and is_array($posts1)){

				foreach ($posts1 as $key => $value) {
					$array_all[] = $value->ID ; 
				}
			}
			$array_sql_all = implode($array_all, ',');
			// list rental have rooms

			$sql2 = "
			select {$wpdb->prefix }posts.ID 
				from {$wpdb->prefix }posts 
				join {$wpdb->prefix }postmeta as mt1 on mt1.meta_key = 'room_parent' and mt1.meta_value= {$wpdb->prefix }posts.ID
				where 
				post_type = 'st_rental'
				and {$wpdb->prefix }posts.ID in ({$array_sql_all})
				group by {$wpdb->prefix }posts.ID 
			";
			$results2 = $wpdb->get_results($sql2 , ARRAY_N);
			$array_have = array();
			if (!empty($results2) and is_array($results2)){
				foreach ($results2 as $key => $value) {
					$array_have[] = $value[0];
				}
			}
			// step 3 => get rental don't have any rooms from 1 and 2 
			$array_have_not = array();
			if (!empty($array_all) and is_array($array_all)){
				foreach ($array_all as $key => $value) {
					if (!(in_array($value, $array_have))){
						$array_have_not[] = $value;
					}
				}
			}	
			

			$sql = "
			SELECT
				meta_value AS rental_id,
				count(ID) AS room_count
			FROM
				{$wpdb->prefix }posts
			JOIN {$wpdb->prefix }postmeta ON (
				{$wpdb ->prefix }postmeta.post_id = {$wpdb->prefix }posts.ID
				AND {$wpdb->prefix}postmeta.meta_key = 'room_parent'
			)
			WHERE
				post_type = 'rental_room'
			GROUP BY
				meta_value";  
			$results = $wpdb->get_results($sql);
			//echo $sql ;
			// Summary rooms of a rental
			$array = array(); 
			if (!empty($results) and is_array($results)){
				
				foreach ($results as $key => $value) {
					if (!empty($value->rental_id) and !empty($value->room_count) and ($value->room_count < $room_number)){
						$array[] = $value->rental_id;
					}
				}
			}
			$return = array_merge($array , $array_have_not);
			if( empty( $return ) ){
				return "''";
			}
			$return = implode($return, ',');		
			return $return ; 

		}
		static function _get_disable_date(){
			$list_date = array();
			$list_date_fist_half_day = array();
			$list_date_last_half_day = array();
			if(!TravelHelper::checkTableDuplicate('st_rental')){
				echo json_encode($list_date);
				die();
			}
			$rental_id = STInput::request('rental_id');
			$allow_full_day = get_post_meta($rental_id, 'allow_full_day', true);
			if(!$allow_full_day || $allow_full_day == '') $allow_full_day = 'on';

            if(!empty($rental_id)){
                $year = STInput::request('year');
                if(empty($year)) $year = date('Y');
				$year2 = $year;
                $month = STInput::request('month');
                if(empty($month)) $month = date('m');
				$month2 = $month;
				if($month == 1){
					$year2 = $year2 - 1;
					$month = 12;
				}else{
					$month = $month - 1;
				}
				if($month2 < 12) $month2 = $month2 + 1;
				if($month2 == 12) {$month2 = 1; $year2 = $year2 + 1; }
				$month = sprintf("%02d", $month);
				$month2 = sprintf("%02d", $month2);



				$result = RentalHelper::_get_full_ordered($rental_id, $month, $month2, $year, $year2);
                $number_rental = intval(get_post_meta($rental_id, 'rental_number', true ));
                $min_max = RentalHelper::_get_min_max_date_ordered($rental_id, $year, $year);
                if(is_array($min_max) && count($min_max) && is_array($result) && count($result)){
                    $disable = array();
					$array_fist_half_day  =array();
					$array_last_half_day  =array();
                    for($i = intval($min_max['min_date']); $i<= intval($min_max['max_date']); $i = strtotime('+1 day', $i)){
                        $num_rental = 0;
                        foreach($result as $key => $date){
							if($allow_full_day == 'on'){
								if($i >= intval($date['check_in_timestamp']) && $i <= intval($date['check_out_timestamp'])){
									$num_rental += 1;
								}
							}else{
								if($i > intval($date['check_in_timestamp']) && $i < intval($date['check_out_timestamp'])){
									$num_rental += 1;
								}
								if($i == intval($date['check_in_timestamp'])){
									$array_fist_half_day[$i] = 1;
								}
								if($i == intval($date['check_out_timestamp'])){
									$array_last_half_day[$i] = 1;
								}
							}

                        }
                        $disable[$i] = $num_rental;
                    }

                    if(count($disable)){
                        foreach ($disable as $key => $num_rental) {
							if(intval($num_rental) >= $number_rental)
								$list_date[] = date('d_m_Y',$key);
                        }
                    }


					if(count($array_fist_half_day)){
						foreach ($array_fist_half_day as $key => $num_rental) {
							if(intval($num_rental) >= $number_rental)
								$list_date_fist_half_day[] = date('d_m_Y',$key);

						}
					}
					if(count($array_last_half_day)){
						foreach ($array_last_half_day as $key => $num_rental) {
							if(intval($num_rental) >= $number_rental)
								$list_date_last_half_day[] = date('d_m_Y',$key);
						}
					}
                }
                $list_date_2 = AvailabilityHelper::_getDisableCustomDateRental($rental_id, $month, $month2, $year, $year2,'d_m_Y');
                if(is_array($list_date_2) && count($list_date_2)){
                    $list_date = array_merge($list_date, $list_date_2);
                }
				if(!empty($list_date_fist_half_day) and !empty($list_date_last_half_day)){
					foreach($list_date_fist_half_day as $k=>$v){
						foreach($list_date_last_half_day as $k2=>$v2){
							if($v == $v2){
								$list_date[] = $v;
								unset($list_date_fist_half_day[$k]);
								unset($list_date_last_half_day[$k2]);
							}
						}

					}
				}
				$data = array(
					'disable'=>$list_date,
					'last_half_day'=>$list_date_last_half_day,
					'fist_half_day'=>$list_date_fist_half_day,
				);
                echo json_encode($data);
                die();
            }


		}

		static function _check_room_available($rental_id, $check_in, $check_out, $order_item_id = ""){
			if(!TravelHelper::checkTableDuplicate('st_rental')) return true;
			global $wpdb;
			$string = "";
			if(!empty($order_item_id)){
				$string = " AND order_item_id NOT IN ('{$order_item_id}') ";
			}
			$sql = "SELECT DISTINCT
						st_booking_id AS rental_id,
						check_in_timestamp AS check_in,
						check_out_timestamp AS check_out
					FROM
						{$wpdb->prefix}st_order_item_meta
					INNER JOIN {$wpdb->prefix}postmeta AS mt ON mt.post_id = st_booking_id AND mt.meta_key = 'rental_number'
					INNER JOIN {$wpdb->prefix}st_rental AS mt1 ON mt1.post_id = st_booking_id
					WHERE
					(
						(
							(
								mt1.allow_full_day = 'on'
								OR mt1.allow_full_day = ''
							)
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
						)
						OR (
							mt1.allow_full_day = 'off'
							AND (
								(
									STR_TO_DATE('{$check_in}', '%Y-%m-%d') <= STR_TO_DATE(check_in, '%m/%d/%Y')
									AND STR_TO_DATE('{$check_in}', '%Y-%m-%d') >= STR_TO_DATE(check_out, '%m/%d/%Y')
								)
								OR (
									(
										STR_TO_DATE('{$check_in}', '%Y-%m-%d') BETWEEN STR_TO_DATE(check_in, '%m/%d/%Y')
										AND STR_TO_DATE(check_out, '%m/%d/%Y')
									)
									AND (
										STR_TO_DATE('{$check_in}', '%Y-%m-%d') < STR_TO_DATE(check_out, '%m/%d/%Y')
									)
								)
								OR (
									(
										STR_TO_DATE('{$check_out}', '%Y-%m-%d') BETWEEN STR_TO_DATE(check_in, '%m/%d/%Y')
										AND STR_TO_DATE(check_out, '%m/%d/%Y')
									)
									AND STR_TO_DATE('{$check_out}', '%Y-%m-%d') > STR_TO_DATE(check_in, '%m/%d/%Y')
								)
							)
						)
					)
					AND st_booking_id = '{$rental_id}'
					AND st_booking_post_type = 'st_rental'
					AND status NOT IN ('trash', 'canceled')
					{$string}";

			$result = $wpdb->get_results($sql, ARRAY_A);
			
			if(is_array($result) && count($result)){
				$list = array();
				foreach($result as $key => $val){
					$list[] = array(
						'check_in' => $val['check_in'],
						'check_out' => $val['check_out'],
					);
				}
			}
			if(isset($list) && is_array($list) && count($list)){
				$check_in = strtotime($check_in);
				$check_out = strtotime($check_out);
				$disable = array();
				$number_room = intval(get_post_meta($rental_id, 'rental_number', true));

				for($i = intval($check_in); $i <= intval($check_out); $i = strtotime('+1 day', $i)){
					$num_room = 0;
					foreach($list as $key => $date){
							
						if($i >= intval($date['check_in']) && $i<= intval($date['check_out'])){
							$num_room += 1;
						}
					}
					if($num_room >= $number_room)
						return false;
				}
			}
			return true;

		}

		static function _get_maxmin_by_date($rental_id, $check_in, $check_out){
			if(!TravelHelper::checkTableDuplicate('st_rental')) return '';
			global $wpdb;

			$sql = "SELECT
						st_booking_id AS rental_id,
						MIN(check_in_timestamp) AS check_in,
						MAX(check_out_timestamp) AS check_out
					FROM
						{$wpdb->prefix}st_order_item_meta
					INNER JOIN {$wpdb->prefix}postmeta AS mt ON mt.post_id = st_booking_id
					AND mt.meta_key = 'rental_number'
					WHERE
						(
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
					AND st_booking_id = '{$rental_id}'
					AND st_booking_post_type = 'st_rental'
					AND status NOT IN ('trash', 'canceled')";

			$result = $wpdb->get_row($sql, ARRAY_A);
			return $result;
		}
	}
	$rentalhelper = new RentalHelper();
	$rentalhelper->init();
}
?>