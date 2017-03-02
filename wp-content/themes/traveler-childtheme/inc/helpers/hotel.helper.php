<?php
/**
 *@since 1.1.8
 **/
if(!class_exists('HotelHelper')){
    class HotelHelper{
        public function init(){
            global $wpdb;
            add_action('wp_ajax_st_get_disable_date_hotel',array(__CLASS__,'_get_disable_date'));
            add_action('wp_ajax_nopriv_st_get_disable_date_hotel',array(__CLASS__,'_get_disable_date'));

            add_action('wp_ajax_st_get_availability_hotel_room', array(&$this, '_get_availability_hotel_room'));
            add_action('wp_ajax_nopriv_st_get_availability_hotel_room', array(&$this, '_get_availability_hotel_room'));

            add_action('wp_ajax_st_get_availability_hotel_room_custom', array(&$this, '_get_availability_hotel_room_custom'));
            add_action('wp_ajax_nopriv_st_get_availability_hotel_room_custom', array(&$this, '_get_availability_hotel_room_custom'));
        }
        static function _get_max_number_room($hotel_id){
            global $wpdb;

            $sql = "SELECT 
				MAX(mt.meta_value) as max_room
				FROM {$wpdb->prefix}posts
				INNER JOIN {$wpdb->prefix}postmeta as mt ON mt.post_id = {$wpdb->prefix}posts.ID and mt.meta_key = 'number_room'
				INNER JOIN {$wpdb->prefix}postmeta as mt1 ON mt1.post_id = {$wpdb->prefix}posts.ID and mt1.meta_key = 'room_parent'
				WHERE
				mt1.meta_value = '{$hotel_id}'
				AND post_type = 'hotel_room'";

            $result = $wpdb->get_row($sql, ARRAY_A);

            if(is_array($result) && count($result))
                return intval($result['max_room']);
            else return 0;
        }
        static function get_all_room_cant_book($check_in = '', $check_out = '', $adult_number = '', $children_number = '', $number_room = 0){
            global $wpdb;

            $list = array();
            if(!TravelHelper::checkTableDuplicate('st_hotel')) return "''";

            if(empty($check_in) || empty($check_out))
                return "''";

            $sql = "
					SELECT
						st_booking_id,
						room_id,
						mt.meta_value AS number_room,
						SUM(DISTINCT room_num_search) AS booked_room,
						mt.meta_value - SUM(DISTINCT room_num_search) AS free_room,
						check_in,
						check_out,
					mt1.allow_full_day
					FROM
						{$wpdb->prefix}st_order_item_meta
					INNER JOIN {$wpdb->prefix}posts ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}st_order_item_meta.room_id
					INNER JOIN {$wpdb->prefix}postmeta AS mt ON mt.post_id = {$wpdb->prefix}st_order_item_meta.room_id
					AND mt.meta_key = 'number_room'
					INNER JOIN {$wpdb->prefix}st_hotel AS mt1 ON mt1.post_id = {$wpdb->prefix}st_order_item_meta.st_booking_id
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
										AND STR_TO_DATE('{$check_out}', '%Y-%m-%d') >= STR_TO_DATE(check_out, '%m/%d/%Y')
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
					AND st_booking_post_type = 'st_hotel'
					AND STATUS NOT IN ('trash', 'canceled')
					GROUP BY
						room_id
					HAVING
						number_room - SUM(DISTINCT room_num_search) < {$number_room}
				";
            $results = $wpdb->get_results($sql, ARRAY_A);

            if(is_array($results) && count($results)){
                $list = array();
                foreach($results as $key => $val){
                    $list[] = $val['room_id'];
                }
            }else{
                $list = "''";
            }
            $list = self::get_all_hotel_can_order($list, $adult_number, $children_number, $number_room);
            return $list;
        }

        static function get_all_hotel_can_order($list_room, $adult_number = '', $children_number = '', $number_room = 0){
            if(!TravelHelper::checkTableDuplicate('st_hotel')) return "''";
            global $wpdb;
            $list = array();

            if(is_array($list_room) && count($list_room))
                $list_room = implode(',', $list_room);
            else $list_room = "''";

            $sql = "
					SELECT DISTINCT SQL_CALC_FOUND_ROWS CAST(mt.meta_value AS UNSIGNED) as hotel_id, ID as room_id FROM {$wpdb->prefix}posts 
					INNER JOIN {$wpdb->prefix}postmeta as mt ON mt.post_id = {$wpdb->prefix}posts.ID AND mt.meta_key = 'room_parent'
					INNER JOIN {$wpdb->prefix}postmeta as mt1 ON mt1.post_id = {$wpdb->prefix}posts.ID AND mt1.meta_key = 'adult_number'
					INNER JOIN {$wpdb->prefix}postmeta as mt2 ON mt2.post_id = {$wpdb->prefix}posts.ID AND mt2.meta_key = 'children_number'
					INNER JOIN {$wpdb->prefix}postmeta as mt3 ON mt3.post_id = {$wpdb->prefix}posts.ID AND mt3.meta_key = 'number_room'
					WHERE post_type = 'hotel_room'
					AND {$wpdb->posts}.ID NOT IN ({$list_room})
					AND CAST(mt1.meta_value AS UNSIGNED) >= {$adult_number}
					AND CAST(mt2.meta_value AS UNSIGNED) >= {$children_number}
					AND CAST(mt3.meta_value AS UNSIGNED) >= {$number_room}
					GROUP BY hotel_id
					ORDER BY mt.meta_value ASC
				";
            $results = $wpdb->get_results($sql, ARRAY_A);
            if(false === $results || !is_array($results) || count($results) <= 0){
                return "''";
            }
            foreach($results as $key => $val){
                $list[] = $val['hotel_id'];
            }
            return $list;
        }

        static function _get_room_cant_book_by_id($hotel_id = '', $check_in = '', $check_out = '', $number_room = 0){
            if(!TravelHelper::checkTableDuplicate('st_hotel')) return "''";
            global $wpdb;

            if(empty($check_in) || empty($check_out))
                return "''";
            $sql = "
					SELECT
						st_booking_id,
						room_id,
						mt.meta_value AS number_room,
						SUM(DISTINCT room_num_search) AS booked_room,
						mt.meta_value - SUM(DISTINCT room_num_search) AS free_room,
						check_in,
						check_out
					FROM
						{$wpdb->prefix}st_order_item_meta
					INNER JOIN {$wpdb->prefix}postmeta AS mt ON mt.post_id = {$wpdb->prefix}st_order_item_meta.room_id
					AND mt.meta_key = 'number_room'
					INNER JOIN {$wpdb->prefix}st_hotel AS mt1 ON mt1.post_id = {$wpdb->prefix}st_order_item_meta.st_booking_id
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
										AND STR_TO_DATE('{$check_out}', '%Y-%m-%d') >= STR_TO_DATE(check_out, '%m/%d/%Y')
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
					AND st_booking_post_type = 'st_hotel'
					AND st_booking_id = '{$hotel_id}'
					AND status NOT IN ('trash', 'canceled')
					GROUP BY
						room_id
					HAVING
						number_room - SUM(DISTINCT room_num_search) < {$number_room}
				";
            $results = $wpdb->get_col($sql, 1);

            return $results;
        }

        static function _get_room_can_book_by_id($list_room, $hotel_id = '',  $adult_number = '', $children_number = '', $number_room = 0){
            if(!TravelHelper::checkTableDuplicate('st_hotel')) return "''";
            global $wpdb;

            $list = array();

            if(is_array($list_room) && count($list_room))
                $list_room = implode(',', $list_room);
            else $list_room = "''";

            $join=$where='';
            if($children_number)
            {
                $join.=" INNER JOIN {$wpdb->prefix}postmeta as mt2 ON mt2.post_id = {$wpdb->prefix}posts.ID AND mt2.meta_key = 'children_number' ";
                $where.= " AND CAST(mt2.meta_value AS UNSIGNED) >= {$children_number} ";
            }

            $sql = "
				SELECT DISTINCT SQL_CALC_FOUND_ROWS ID as room_id FROM {$wpdb->prefix}posts 
				INNER JOIN {$wpdb->prefix}postmeta as mt ON mt.post_id = {$wpdb->prefix}posts.ID AND mt.meta_key = 'room_parent'
				INNER JOIN {$wpdb->prefix}postmeta as mt1 ON mt1.post_id = {$wpdb->prefix}posts.ID AND mt1.meta_key = 'adult_number'
				INNER JOIN {$wpdb->prefix}postmeta as mt3 ON mt3.post_id = {$wpdb->prefix}posts.ID AND mt3.meta_key = 'number_room'
				  {$join}
				WHERE post_type = 'hotel_room'
				AND {$wpdb->posts}.ID NOT IN ({$list_room})
				AND mt.meta_value = '{$hotel_id}'
				AND CAST(mt1.meta_value AS UNSIGNED) >= {$adult_number}
				AND CAST(mt3.meta_value AS UNSIGNED) >= {$number_room}
				  {$where}
				GROUP BY ID
				ORDER BY mt.meta_value ASC";

            $results = $wpdb->get_results($sql, ARRAY_A);
            if(false === $results || !is_array($results) || count($results) <= 0){
                return "''";
            }
            foreach($results as $key => $val){
                $list[] = $val['room_id'];
            }
            return $list;
        }

        static function _check_room_only_available($room_id = '', $check_in = '', $check_out = '', $number_room = 0, $order_item_id = ''){
            if(!TravelHelper::checkTableDuplicate('hotel_room')) return true;
            global $wpdb;
            $string = "";
            if(!empty($order_item_id)){
                $string = " AND order_item_id NOT IN ('{$order_item_id}') ";
            }
            $sql = "
				SELECT
					room_id,
					mt.meta_value AS number_room,
					mt.meta_value - SUM(room_num_search) AS free_room
				FROM
					{$wpdb->prefix}st_order_item_meta
				INNER JOIN {$wpdb->prefix}postmeta AS mt ON mt.post_id = {$wpdb->prefix}st_order_item_meta.room_id AND mt.meta_key = 'number_room'
				INNER JOIN {$wpdb->prefix}hotel_room AS mt1 ON mt1.post_id = {$wpdb->prefix}st_order_item_meta.st_booking_id
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
				AND room_id = '{$room_id}'
				AND status NOT IN ('trash', 'canceled')
				{$string}
				GROUP BY
					room_id
				HAVING
					number_room - SUM(room_num_search) < {$number_room}";

            $results = $wpdb->get_row($sql, ARRAY_A);

            if($results == NULL || !is_array($results) || count($results) <= 0)
                return true;
            return false;
        }

        static function _check_room_available($room_id = '', $check_in = '', $check_out = '', $number_room = 0, $order_item_id = ''){
            if(!TravelHelper::checkTableDuplicate('st_hotel')) return true;
            global $wpdb;
            $string = "";
            if(!empty($order_item_id)){
                $string = " AND order_item_id NOT IN ('{$order_item_id}') ";
            }
            $sql = "
				SELECT
					room_id,
					mt.meta_value AS number_room,
					mt.meta_value - SUM(room_num_search) AS free_room
				FROM
					{$wpdb->prefix}st_order_item_meta
				INNER JOIN {$wpdb->prefix}postmeta AS mt ON mt.post_id = {$wpdb->prefix}st_order_item_meta.room_id
				INNER JOIN {$wpdb->prefix}st_hotel AS mt1 ON mt1.post_id = {$wpdb->prefix}st_order_item_meta.st_booking_id
				AND mt.meta_key = 'number_room'
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
				AND room_id = '{$room_id}'
				AND status NOT IN ('trash', 'canceled')
				{$string}
				GROUP BY
					room_id
				HAVING
					number_room - SUM(room_num_search) < {$number_room}";

            $results = $wpdb->get_row($sql, ARRAY_A);

            if($results == NULL || !is_array($results) || count($results) <= 0)
                return true;
            return false;
        }

        static function check_day_cant_order($room_id, $check_in, $check_out, $number_room){
            global $wpdb;

            $default_state = get_post_meta($room_id, 'default_state', true);
            $room = intval(get_post_meta($room_id, 'number_room', true));
            if(!$default_state) $default_state = 'available';

            $check_in = strtotime($check_in);
            $check_out = strtotime($check_out);

            $sql = "
				SELECT
					`check_in`,
					`check_out`,
					`number`,
					`status`,
					DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d')
				FROM
					{$wpdb->prefix}st_availability
				WHERE
					(
						(
							{$check_in} <= CAST(check_in as UNSIGNED)
							AND {$check_out} >= CAST(check_out as UNSIGNED)
						)
						OR (
							{$check_in} BETWEEN CAST(check_in AS UNSIGNED)
							AND CAST(check_out as UNSIGNED)
						)
						OR (
							{$check_out} BETWEEN CAST(check_in AS UNSIGNED)
							AND CAST( check_out AS UNSIGNED )
						)
					)
				AND post_id = '{$room_id}'";

            $results = $wpdb->get_results($sql);

            $price = STPrice::getRoomPriceOnlyCustomPrice($room_id, $check_in, $check_out, $number_room = 1);
            if($price <= 0){
                return false;
            }
            if(is_array($results) && count($results)){
                for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
                    $in_date = false;
                    $status = 'available';
                    $count_room = 0;
                    foreach($results as $key => $val){
                        if($i >= $val->check_in && $i <= $val->check_out){
                            $status = $val->status;
                            $in_date = true;
                        }
                    }
                    if($in_date){
                        if($status != 'available' || $room < $number_room){
                            return false;
                        }
                    }else{
                        if($default_state != 'available' || $room < $number_room){
                            return false;
                        }
                    }
                }
                return true;
            }else{
                if($default_state == 'available' && $room >= $number_room){
                    return true;
                }else{
                    return false;
                }
            }


        }
        static function _get_full_ordered($room_id, $month, $month2, $year, $year2){
            $date1 = $month.'/'.'01'.'/'.$year;
            $date2 = strtotime($year2.'-'.$month2.'-01');
            $date2 = date('m/t/Y',$date2);
            if(!TravelHelper::checkTableDuplicate('st_hotel')) return '';

            $hotel_id = intval(get_post_meta($room_id, 'room_parent', true));
            if(!empty($hotel_id)){
                $key_post_type = "st_hotel";
            }else{
                $key_post_type = "hotel_room";
            }

            global $wpdb;
            $sql = "
				SELECT  
				room_id,
				check_in_timestamp,
				check_out_timestamp,
				room_num_search as number_room
				FROM {$wpdb->prefix}st_order_item_meta
				WHERE room_id = '{$room_id}'
				AND st_booking_post_type = '{$key_post_type}'
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

        static function _get_min_max_date_ordered($room_id, $year, $year2){
            if(!TravelHelper::checkTableDuplicate('st_hotel')) return '';
            global $wpdb;
            $hotel_id = intval(get_post_meta($room_id, 'room_parent', true));
            if(!empty($hotel_id)){
                $key_post_type = "st_hotel";
            }else{
                $key_post_type = "hotel_room";
            }
            $sql = "SELECT 
				MIN(check_in_timestamp) as min_date,
				MAX(check_out_timestamp) as max_date
				FROM {$wpdb->prefix}st_order_item_meta
				WHERE room_id = '{$room_id}'
				AND st_booking_post_type = '{$key_post_type}'
				AND (YEAR(FROM_UNIXTIME(check_in_timestamp)) = {$year}
				OR YEAR(FROM_UNIXTIME(check_out_timestamp)) = {$year2})
				AND status NOT IN ('trash', 'canceled')
				GROUP BY room_id";

            $result = $wpdb->get_row($sql, ARRAY_A);

            if(is_array($result) && count($result))
                return $result;
            return '';
        }

        static function _get_disable_date(){
            $list_date = array();
            $list_date_fist_half_day = array();
            $list_date_last_half_day = array();
            if(!TravelHelper::checkTableDuplicate('st_hotel')){
                echo json_encode($list_date);
                die();
            }
            $room_id = STInput::request('room_id');
            $hotel_id = intval(get_post_meta($room_id, 'room_parent', true));
            if(!empty($hotel_id)){
                $allow_full_day = get_post_meta($hotel_id, 'allow_full_day', true);
                if(!$allow_full_day || $allow_full_day == '') $allow_full_day = 'on';
                $year = STInput::request('year');
                if(empty($year)) $year = date('Y');

                $month = STInput::request('month');
                if(empty($month)) $month = date('m');

                $year2 = $year;
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


                $result = HotelHelper::_get_full_ordered($room_id, $month, $month2, $year, $year2);
                $number_room = get_post_meta($room_id, 'number_room', true );
                $min_max = HotelHelper::_get_min_max_date_ordered($room_id, $year, $year);

                if(is_array($min_max) && count($min_max) && is_array($result) && count($result)){
                    $disable = array();
                    $array_fist_half_day  =array();
                    $array_last_half_day  =array();
                    for($i = intval($min_max['min_date']); $i<= intval($min_max['max_date']); $i = strtotime('+1 day', $i)){
                        $num_room = 0;
                        foreach($result as $key => $date){
                            if($allow_full_day == 'on'){
                                if($i >= intval($date['check_in_timestamp']) && $i <= intval($date['check_out_timestamp'])){
                                    $num_room += $date['number_room'];
                                }
                            }else{
                                if($i > intval($date['check_in_timestamp']) && $i < intval($date['check_out_timestamp'])){
                                    $num_room += $date['number_room'];
                                }
                                if($i == intval($date['check_in_timestamp'])){
                                    $array_fist_half_day[$i] = $date['number_room'];
                                }
                                if($i == intval($date['check_out_timestamp'])){
                                    $array_last_half_day[$i] = $date['number_room'];
                                }
                            }
                        }
                        $disable[$i] = $num_room;
                    }
                    if(count($disable)){
                        foreach ($disable as $key => $num_room) {
                            if(intval($num_room) >= $number_room)
                                $list_date[] = date('d_m_Y',$key);
                        }
                    }
                    if(count($array_fist_half_day)){
                        foreach ($array_fist_half_day as $key => $num_room) {
                            if(intval($num_room) >= $number_room)
                                $list_date_fist_half_day[] = date('d_m_Y',$key);

                        }
                    }
                    if(count($array_last_half_day)){
                        foreach ($array_last_half_day as $key => $num_room) {
                            if(intval($num_room) >= $number_room)
                                $list_date_last_half_day[] = date('d_m_Y',$key);
                        }
                    }
                }
                $list_date_2 = AvailabilityHelper::_getDisableCustomDate($room_id, $month, $month2, $year, $year2, 'd_m_Y');
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
            }else{
                $year = STInput::request('year');
                if(empty($year)) $year = date('Y');
                $month = STInput::request('month');
                if(empty($month)) $month = date('m');
                $year2 = $year;
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

                $result = HotelHelper::_get_full_ordered($room_id, $month, $month2, $year, $year2);
                $number_room = get_post_meta($room_id, 'number_room', true );
                $min_max = HotelHelper::_get_min_max_date_ordered($room_id, $year, $year);

                $allow_full_day = get_post_meta($room_id, 'allow_full_day', true);
                if(!$allow_full_day || $allow_full_day == '') $allow_full_day = 'on';

                if(is_array($min_max) && count($min_max) && is_array($result) && count($result)){
                    $disable = array();
                    $array_fist_half_day  =array();
                    $array_last_half_day  =array();
                    for($i = intval($min_max['min_date']); $i<= intval($min_max['max_date']); $i = strtotime('+1 day', $i)){
                        $num_room = 0;
                        foreach($result as $key => $date){
                            if($allow_full_day == 'on'){
                                if($i >= intval($date['check_in_timestamp']) && $i <= intval($date['check_out_timestamp'])){
                                    $num_room += $date['number_room'];
                                }
                            }else{
                                if($i > intval($date['check_in_timestamp']) && $i < intval($date['check_out_timestamp'])){
                                    $num_room += $date['number_room'];
                                }
                                if($i == intval($date['check_in_timestamp'])){
                                    $array_fist_half_day[$i] = $date['number_room'];
                                }
                                if($i == intval($date['check_out_timestamp'])){
                                    $array_last_half_day[$i] = $date['number_room'];
                                }
                            }
                        }
                        $disable[$i] = $num_room;
                    }
                    if(count($disable)){
                        foreach ($disable as $key => $num_room) {
                            if(intval($num_room) >= $number_room)
                                $list_date[] = date('d_m_Y',$key);
                        }
                    }
                    if(count($array_fist_half_day)){
                        foreach ($array_fist_half_day as $key => $num_room) {
                            if(intval($num_room) >= $number_room)
                                $list_date_fist_half_day[] = date('d_m_Y',$key);

                        }
                    }
                    if(count($array_last_half_day)){
                        foreach ($array_last_half_day as $key => $num_room) {
                            if(intval($num_room) >= $number_room)
                                $list_date_last_half_day[] = date('d_m_Y',$key);
                        }
                    }
                }
                $list_date_2 = AvailabilityHelper::_getDisableCustomDate($room_id, $month, $month2, $year, $year2,'d_m_Y');

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

        public function _get_availability_hotel_room(){
            $list_date = array();
            $room_id = STInput::request('post_id', '');
            $check_in = STInput::request('start', '');
            $check_out = STInput::request('end', '');

            $hotel_id = intval(get_post_meta($room_id, 'room_parent', true));

            //if empty hotel ->>>> room only
            if(empty($hotel_id)){
                $hotel_id = $room_id;
            }

            $allow_full_day = get_post_meta($hotel_id, 'allow_full_day', true);
            if(!$allow_full_day || $allow_full_day == '') $allow_full_day = 'on';

            $default_state = get_post_meta( $room_id, 'default_state', true );
            if( !$default_state || $default_state == ''){
                $default_state = 'available';
            }

            $year = date('Y', $check_in);
            if(empty($year)) $year = date('Y');
            $year2 = date('Y', $check_out);
            if(empty($year2)) $year2 = date('Y');

            $month = date('m', $check_in);
            if(empty($month)) $month = date('m');

            $month2 = date('m', $check_out);
            if(empty($month2)) $month2 = date('m');


            $result = HotelHelper::_get_full_ordered($room_id, $month, $month2, $year, $year2);

            $number_room = get_post_meta($room_id, 'number_room', true );
            $min_max = HotelHelper::_get_min_max_date_ordered($room_id, $year, $year2);
            if(is_array($min_max) && count($min_max) && is_array($result) && count($result)){
                $disable = array();
                for($i = intval($min_max['min_date']); $i<= intval($min_max['max_date']); $i = strtotime('+1 day', $i)){
                    $num_room = 0;
                    foreach($result as $key => $date){
                        if($allow_full_day == 'on'){
                            if($i >= intval($date['check_in_timestamp']) && $i <= intval($date['check_out_timestamp'])){
                                $num_room += $date['number_room'];
                            }
                        }else{
                            /*if($i >= intval($date['check_in_timestamp']) && $i < intval($date['check_out_timestamp'])){
                                $num_room += $date['number_room'];
                            }*/
                            if($i > intval($date['check_in_timestamp']) && $i < intval($date['check_out_timestamp'])){
                                $num_room += $date['number_room'];
                            }
                            if($i == intval($date['check_in_timestamp'])){
                                $array_fist_half_day[$i] = $date['number_room'];
                            }
                            if($i == intval($date['check_out_timestamp'])){
                                $array_last_half_day[$i] = $date['number_room'];
                            }
                        }
                    }
                    $disable[$i] = $num_room;
                }
                if(count($disable)){
                    foreach ($disable as $key => $num_room) {
                        if(intval($num_room) >= $number_room)
                            $list_date[] = date(TravelHelper::getDateFormat(),$key);
                    }
                }
                $list_date_fist_half_day = array();
                $list_date_last_half_day = array();
                if(count($array_fist_half_day)){
                    foreach ($array_fist_half_day as $key => $num_room) {
                        if(intval($num_room) >= $number_room)
                            $list_date_fist_half_day[] = date(TravelHelper::getDateFormat(),$key);
                    }
                }
                if(count($array_last_half_day)){
                    foreach ($array_last_half_day as $key => $num_room) {
                        if(intval($num_room) >= $number_room)
                            $list_date_last_half_day[] = date(TravelHelper::getDateFormat(),$key);
                    }
                }
            }

            $list_date_2 = AvailabilityHelper::_getDisableCustomDate($room_id, $month, $month2, $year, $year2);


            $date1 = strtotime($year.'-'.$month.'-01');
            $date2 = strtotime($year2.'-'.$month2.'-01');
            $date2 = strtotime(date('Y-m-t',$date2));
            $today = strtotime(date('Y-m-d'));
            $return = array();

            $booking_period = intval(get_post_meta($hotel_id, 'hotel_booking_period', true));

            for($i = $date1; $i<= $date2; $i = strtotime('+1 day', $i)){
                $period = TravelHelper::dateDiff(date('Y-m-d',$today), date('Y-m-d',$i));
                $d = date(TravelHelper::getDateFormat(), $i);
                if(in_array($d, $list_date) or ( in_array($d, $list_date_fist_half_day) and in_array($d, $list_date_last_half_day) ) ){
                    $return[] = array(
                        'start' => date('Y-m-d', $i),
                        'date' => date('Y-m-d', $i),
                        'day' => date('d', $i),
                        'status' => 'booked'
                    );
                }else{
                    if($i < $today){
                        $return[] = array(
                            'start' => date('Y-m-d', $i),
                            'date' => date('Y-m-d', $i),
                            'day' => date('d', $i),
                            'status' => 'past'
                        );
                    }else{
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
                                    'price'	=> TravelHelper::format_money( STPrice::getRoomPriceOnlyCustomPrice($room_id, $i, strtotime('+1 day', $i), 1))
                                );
                            }else if(in_array($d, $list_date_last_half_day)){
                                $return[] = array(
                                    'start' => date('Y-m-d', $i),
                                    'date' => date('Y-m-d', $i),
                                    'day' => date('d', $i),
                                    'status' => 'available_allow_last',
                                    'price'	=> TravelHelper::format_money( STPrice::getRoomPriceOnlyCustomPrice($room_id, $i, strtotime('+1 day', $i), 1))
                                );
                            }else{
                                $return[] = array(
                                    'start' => date('Y-m-d', $i),
                                    'date' => date('Y-m-d', $i),
                                    'day' => date('d', $i),
                                    'status' => 'avalable',
                                    'price'	=>   TravelHelper::format_money( STPrice::getRoomPriceOnlyCustomPrice($room_id, $i, strtotime('+1 day', $i), 1))
                                );
                            }

                        }

                    }
                }
            }

            echo json_encode($return);
            die;
        }

        static function _getAllRoomHotelID($hotel_id){
            global $wpdb;
            if(defined('ICL_LANGUAGE_CODE')){
                $sql = "SELECT
						{$wpdb->prefix}posts.ID
					FROM
						{$wpdb->prefix}posts
					INNER JOIN {$wpdb->prefix}postmeta as mt on mt.post_id = {$wpdb->prefix}posts.ID and mt.meta_key = 'room_parent'
					JOIN {$wpdb->prefix}icl_translations t ON {$wpdb->prefix}posts.ID = t.element_id
					AND t.element_type = 'post_hotel_room'
					JOIN {$wpdb->prefix}icl_languages l ON t.language_code = l. CODE
					AND l.active = 1
					where mt.meta_value = '{$hotel_id}'
					and post_type = 'hotel_room'
					and post_status = 'publish'
					AND t.language_code = '".ICL_LANGUAGE_CODE."'";
            }else{
                $sql = "SELECT
						{$wpdb->prefix}posts.ID
					FROM
						{$wpdb->prefix}posts
					INNER JOIN {$wpdb->prefix}postmeta as mt on mt.post_id = {$wpdb->prefix}posts.ID and mt.meta_key = 'room_parent'
					where 
					mt.meta_value = '{$hotel_id}'
					and post_type = 'hotel_room'
					and post_status = 'publish'";
            }
            $rooms = $wpdb->get_col($sql);
            return $rooms;
        }

        static function _getAllHotelID(){
            global $wpdb;
            if(defined('ICL_LANGUAGE_CODE')){
                $sql = "SELECT
						{$wpdb->prefix}posts.ID
					FROM
						{$wpdb->prefix}posts
					JOIN {$wpdb->prefix}icl_translations t ON {$wpdb->prefix}posts.ID = t.element_id
					AND t.element_type = 'post_st_hotel'
					JOIN {$wpdb->prefix}icl_languages l ON t.language_code = l. CODE
					AND l.active = 1
					where post_type = 'st_hotel'
					and post_status = 'publish'
					AND t.language_code = '".ICL_LANGUAGE_CODE."'";
            }else{
                $sql = "SELECT
						{$wpdb->prefix}posts.ID
					FROM
						{$wpdb->prefix}posts
					where 
					post_type = 'st_hotel'
					and post_status = 'publish'";
            }

            $results = $wpdb->get_col($sql, 0);
            return $results;
        }

        static function _getTotalRoom($hotel_id){
            global $wpdb;
            if(defined('ICL_LANGUAGE_CODE')){
                $sql = "SELECT
						count({$wpdb->prefix}posts.ID) as num_room
					FROM
						{$wpdb->prefix}posts
					INNER JOIN {$wpdb->prefix}postmeta as mt on mt.post_id = {$wpdb->prefix}posts.ID and mt.meta_key = 'room_parent'
					JOIN {$wpdb->prefix}icl_translations t ON {$wpdb->prefix}posts.ID = t.element_id
					AND t.element_type = 'post_hotel_room'
					JOIN {$wpdb->prefix}icl_languages l ON t.language_code = l. CODE
					AND l.active = 1

					where 
					mt.meta_value = '{$hotel_id}'
					and post_type = 'hotel_room'
					and post_status = 'publish'
					AND t.language_code = '".ICL_LANGUAGE_CODE."'";
            }else{
                $sql = "SELECT
						count(ID) as num_room
					FROM
						{$wpdb->prefix}posts
					INNER JOIN {$wpdb->prefix}postmeta as mt on mt.post_id = {$wpdb->prefix}posts.ID and mt.meta_key = 'room_parent'
					where 
					mt.meta_value = '{$hotel_id}'
					and post_type = 'hotel_room'
					and post_status = 'publish'";
            }

            $results = $wpdb->get_var($sql);
            return $results;
        }
        static function _hotelValidate($check_in, $check_out, $adult_num, $child_num, $number_room){
            $cant_book = array();
            $hotels = HotelHelper::_getAllHotelID();
            if(is_array($hotels) && count($hotels)){
                foreach($hotels as $hotel){
                    $total_room = HotelHelper::_getTotalRoom($hotel);
                    $room_cant_book = HotelHelper::_hotelValidateByID($hotel, strtotime($check_in), strtotime($check_out), $adult_num, $child_num, $number_room);
                    $total_room_cant_book = count($room_cant_book);
                    if($total_room <= $total_room_cant_book){
                        $cant_book[] = $hotel;
                    }
                }
            }
            return $cant_book;
        }

        static function _hotelValidateByID($hotel_id, $check_in, $check_out, $adult_num, $child_num, $number_room){

            $cant_book = array();
            global $wpdb;
            $rooms = HotelHelper::_getAllRoomHotelID($hotel_id);
            if(is_array($rooms) && count($rooms)){
                foreach($rooms as $room){
                    $default_state = get_post_meta($room, 'default_state', true);
                    if(!$default_state) $default_state = 'available';

                    $number_room_ori = intval(get_post_meta($room, 'number_room', true));
                    $room_price = STPrice::getRoomPriceOnlyCustomPrice($room, $check_in, $check_out, 1);

                    if($room_price <= 0){
                        $cant_book[] = $room;
                    }else{
                        $adult_number = intval(get_post_meta($room, 'adult_number', true));
                        $child_number = intval(get_post_meta($room, 'children_number', true));
                        if($adult_number < $adult_num || $child_number < $child_num){ // overload people
                            $cant_book[] = $room;
                        }else{
                            $data_room = AvailabilityHelper::_getdataHotel($room, $check_in, $check_out);

                            if(is_array($data_room) && count($data_room)){
                                $start = $check_in;
                                $end = $check_out;
                                for($i = $start; $i <= $end; $i = strtotime('+1 day', $i)){
                                    $in_date = false;
                                    $status = 'available';
                                    $num_room = 0;
                                    foreach($data_room as $key => $val){
                                        if($i == $val->check_in && $i == $val->check_out){ //in date
                                            $status = $val->status;
                                            if(!$in_date) $in_date = true;
                                        }
                                    }
                                    if($in_date){
                                        if($status != 'available'){
                                            $cant_book[] = $room;
                                            break;
                                        }
                                    }else{
                                        if($default_state == 'available'){
                                            if($number_room > $number_room_ori){
                                                $cant_book[] = $room;
                                                break;
                                            }
                                        }else{
                                            $cant_book[] = $room;
                                            break;
                                        }
                                    }
                                }
                            }else{ // dont have custom price

                                if($default_state == 'available'){
                                    if($number_room > $number_room_ori){
                                        $cant_book[] = $room;
                                    }
                                }else{
                                    $cant_book[] = $room;
                                }
                            }
                        }
                    }
                }
            }
            $room_full_ordered = HotelHelper::_get_room_cant_book_by_id($hotel_id, date('Y-m-d',$check_in), date('Y-m-d',$check_out), $number_room);
            if(is_array($room_full_ordered) && count($room_full_ordered)){
                $cant_book = array_unique(array_merge($cant_book, $room_full_ordered));
            }
            return $cant_book;
        }
        /**
         *
         *
         * Update 1.2.4
         */
        static function get_minimum_price_hotel($hotel_id){
            if(empty($hotel_id))$hotel_id=get_the_ID();
            $price = get_post_meta($hotel_id,'min_price',true);
            return $price;
        }
        static function get_maximum_price_hotel($hotel_id){
            global $wpdb;
            $rooms = self::_getAllRoomHotelID($hotel_id);
            $min_price = 0;
            $check_in = STInput::request('start', '');
            $check_out = STInput::request('end', '');
            if(empty($check_in)){
                $check_in = date('m/d/Y');
            }else{
                $check_in = TravelHelper::convertDateFormat($check_in);
            }
            if(empty($check_out)){
                $check_out = date('m/d/Y', strtotime("+1 day"));
            }else{
                $check_out = TravelHelper::convertDateFormat($check_out);
            }
            $room_num_search = STInput::request('room_num_search', 1);
            if(intval($room_num_search) <= 0){
                $room_num_search = 1;
            }
            $room_full_ordered = HotelHelper::_get_room_cant_book_by_id($hotel_id, date('Y-m-d',strtotime($check_in)), date('Y-m-d',strtotime($check_out)), $room_num_search);

            if(is_array($rooms) && count($rooms)){
                foreach($rooms as $room){
                    if(!in_array($room, $room_full_ordered)){
                        $price = STPrice::getRoomPriceOnlyCustomPrice($room, strtotime($check_in), strtotime($check_out), $room_num_search);
                        if($min_price == 0 && $price > 0){
                            $min_price = $price;
                        }
                        if($min_price > 0 && $min_price > $price && $price > 0){
                            $min_price = $price;
                        }
                    }

                }
            }
            return $min_price;

        }
        static function get_avg_price_hotel($hotel_id){

            if(empty($hotel_id))$hotel_id=get_the_ID();
            $price = get_post_meta($hotel_id,'price_avg',true);
            return $price;
        }

        static function _get_query_where_validate( $where )
        {
            return $where;
        }

        /**
         * Get API prices listing(ajax)
         */
        function _get_availability_hotel_room_custom()
        {
            $all_days = StreamlineCore_Wrapper::GetPropertyRatesRawData( $_POST['post_id'] );
            $blocked_days = StreamlineCore_Wrapper::GetPropertyAvailabilityCalendarRawData( $_POST['post_id'] );
            $all_days = $this->parseAllDaysPeriod($all_days['data']['rates']);
            $blocked_days = $this->parseBlockedDaysPeriod($blocked_days['data']['blocked_period']);

            $result = array();
            $merged = array_merge( $all_days, $blocked_days );
            foreach ($merged as $key => $val){
                $result[] = $val;
            }
            wp_send_json($result);
        }

        /**
         * Build all days dates array from period
         * @param $apiData array
         * @return array
         */
        private function parseAllDaysPeriod($allDays)
        {
            $today = date("Y-m-d");
            $result = array();

            foreach ($allDays as $period) {
                $price = $period["daily_first_interval_price"];
                $start = new DateTime($period['period_begin']);
                $end = new DateTime($period['period_end']);
                $end->modify('+1 day');
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($start, $interval, $end);

                foreach ($period as $dt) {
                    $date = $dt->format("Y-m-d");
                    $day = $dt->format('d');
                    $status = (strtotime($date) < strtotime($today)) ? 'past' : 'avalable';

                    $result[$date] = array(
                        'date' => $date,
                        'day' => $day,
                        'price' => $price,
                        'start' => $date,
                        'status' => $status,
                    );
                }
            }

            return $result;
        }

        /**
         * Build blocked days dates array from period
         * @param $apiData array
         * @return array
         */
        private function parseBlockedDaysPeriod($blockedDays)
        {
            $result = array();

            foreach ( $blockedDays as $period ) {
                $price = 0;
                $start = new DateTime($period['startdate']);
                $end = new DateTime($period['enddate']);
                $end->modify('+1 day');
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($start, $interval, $end);

                $count = iterator_count($period);
                foreach ($period as $key => $dt) {
                    $date = $dt->format("Y-m-d");
                    $day = $dt->format('d');

                    $status = 'booked';
                    if ( $count-1 ==  $key){
                        $status = 'still';
                    }

                    $result[$date] = array(
                        'date' => $date,
                        'day' => $day,
                        'price' => $price,
                        'start' => $date,
                        'status' => $status,
                    );
                }
            }

            return $result;
        }

    }

    $hotelhelper = new HotelHelper();
    $hotelhelper->init();
}
?>