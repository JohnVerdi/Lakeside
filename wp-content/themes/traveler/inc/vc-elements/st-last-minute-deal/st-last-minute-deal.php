<?php
$list1 = ( STLocation::get_post_type_list_active() );

$list = array();
$list = array( __( '--Select--' , ST_TEXTDOMAIN ) => '' );
if(!empty( $list1 ) and is_array( $list1 )) {
    foreach( $list1 as $key => $value ) {
        if($value == 'st_cars') {
            $list[ __( 'Car' , ST_TEXTDOMAIN ) ] = $value;
        }
        if($value == 'st_tours') {
            $list[ __( 'Tour' , ST_TEXTDOMAIN ) ] = $value;
        }
        if($value == 'st_hotel') {
            $list[ __( 'Hotel' , ST_TEXTDOMAIN ) ] = $value;
        }
        if($value == 'st_rental') {
            $list[ __( 'Rental' , ST_TEXTDOMAIN ) ] = $value;
        }
        if($value == 'st_activity') {
            $list[ __( 'Activity' , ST_TEXTDOMAIN ) ] = $value;
        }
        if($value == 'hotel_room') {
            $list[ esc_html__('Room',ST_TEXTDOMAIN)] = $value;
        }
    }
}
if(function_exists( 'vc_map' )) {
    vc_map( array(
        "name"            => __( "ST Last Minute Deal" , ST_TEXTDOMAIN ) ,
        "base"            => "st_last_minute_deal" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => "Shinetheme" ,
        "params"          => array(
            array(
                "type"        => "dropdown" ,
                'admin_label' => true,
                "heading"     => __( "Post type" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_post_type" ,
                "description" => "" ,
                'value'       => $list ,
            ) ,
        )
    ) );
}
if(!function_exists( 'st_vc_last_minute_deal' )) {
    function st_vc_last_minute_deal( $attr , $content = false )
    {
        $data = shortcode_atts(
            array(
                'st_post_type' => 'st_hotel' ,
            ) , $attr , 'st_last_minute_deal' );
        extract( $data );
        $html = "";
        global $wpdb;
        $query = null;
        $where = $join = "";
        $where = TravelHelper::edit_where_wpml($where);
        switch($st_post_type){
            case "st_hotel":
                $join = TravelHelper::edit_join_wpml($join , 'hotel_room') ;
                $date_now = strtotime("now");

                $query = "SELECT
                            *,
                            {$wpdb->posts}.ID as post_id,
                            meta2.meta_value as hotel_id,
                            meta1.meta_value as discount_rate
                            FROM
                            {$wpdb->posts}
                            INNER JOIN {$wpdb->postmeta} AS meta1 ON meta1.post_id = {$wpdb->posts}.ID AND meta1.meta_key = 'discount_rate' AND meta1.meta_value > 0
                            INNER JOIN {$wpdb->postmeta} AS meta2 ON meta2.post_id = {$wpdb->posts}.ID AND meta2.meta_key = 'room_parent' AND meta2.meta_value is not null
                            INNER JOIN {$wpdb->prefix}st_availability ON {$wpdb->posts}.ID = {$wpdb->prefix}st_availability.post_id
                            {$join}
                            WHERE 1=1
                            {$where} AND
                            {$wpdb->posts}.post_type = 'hotel_room'
                            AND {$wpdb->prefix}st_availability.post_type = 'hotel_room'
                            AND {$wpdb->prefix}st_availability.check_in > {$date_now}
                            GROUP BY check_in
                            ORDER BY {$wpdb->prefix}st_availability.check_in ASC
                            LIMIT 0, 1";

//                $result_discount = $wpdb->get_row($query);
//
//                if(empty($result_discount)){
//                    $query = "SELECT
//                            *,
//                            {$wpdb->posts}.ID as post_id,
//                            {$wpdb->postmeta}.meta_value as hotel_id
//                            FROM {$wpdb->posts}
//                            INNER JOIN {$wpdb->postmeta} ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID and {$wpdb->postmeta}.meta_key = 'room_parent'
//                            JOIN {$wpdb->prefix}st_availability ON {$wpdb->posts}.ID = {$wpdb->prefix}st_availability.post_id
//                            {$join}
//                            WHERE 1=1
//                            {$where}
//                            AND {$wpdb->posts}.post_type  = 'hotel_room'
//                            AND {$wpdb->prefix}st_availability.post_type = 'hotel_room'
//                            AND {$wpdb->prefix}st_availability.check_in > {$date_now}
//                            GROUP BY check_in
//                            ORDER BY {$wpdb->prefix}st_availability.check_in ASC
//                            LIMIT 0,1";
//                }
                break;
            case 'hotel_room':
                $join = TravelHelper::edit_join_wpml($join , 'hotel_room') ;
                $date_now = strtotime("now");
                $query = "SELECT
                            *,
                            meta2.post_id as post_id,
                            meta2.meta_value as discount_rate
                            FROM
                            {$wpdb->posts}
                            LEFT JOIN {$wpdb->postmeta} as meta1 ON meta1.post_id = {$wpdb->posts}.ID AND meta1.meta_key = 'room_parent'
                            INNER JOIN {$wpdb->postmeta} as meta2 ON meta2.post_id = {$wpdb->posts}.ID AND meta2.meta_key = 'discount_rate' AND meta2.meta_value > 0
                            INNER JOIN {$wpdb->prefix}st_availability ON {$wpdb->posts}.ID = {$wpdb->prefix}st_availability.post_id
                            {$join}
                            WHERE 1=1 AND
                            {$wpdb->posts}.post_type = 'hotel_room' AND
                            (meta1.meta_value is null or meta1.meta_value='') AND
                            {$wpdb->prefix}st_availability.check_in > {$date_now}
                            GROUP BY check_in
                            ORDER BY {$wpdb->prefix}st_availability.check_in ASC
                            LIMIT 0, 1
                            ";
                break;
            case "st_rental":
                $join = TravelHelper::edit_join_wpml($join , $st_post_type) ;
                $query = "
                        SELECT *
                        FROM {$wpdb->posts}
                        JOIN {$wpdb->prefix}{$st_post_type} ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}{$st_post_type}.post_id
                        {$join}
                        WHERE 1=1
                        {$where}
                        AND {$wpdb->posts}.post_type = '{$st_post_type}'
                        AND {$wpdb->prefix}{$st_post_type}.is_sale_schedule = 'on'
                        AND {$wpdb->prefix}{$st_post_type}.sale_price_to >= CURDATE()
                        AND {$wpdb->prefix}{$st_post_type}.discount_rate > 0
                        ORDER BY {$wpdb->prefix}{$st_post_type}.sale_price_to ASC
                        LIMIT 0,1
                ";
                break;
            case "st_cars":
            case "st_tours":
            case "st_activity":
                $join = TravelHelper::edit_join_wpml($join , $st_post_type) ;
                $query = "
                        SELECT *
                        FROM {$wpdb->posts}
                        JOIN {$wpdb->prefix}{$st_post_type} ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}{$st_post_type}.post_id
                        {$join}
                        WHERE 1=1
                        {$where}
                        AND {$wpdb->posts}.post_type = '{$st_post_type}'
                        AND {$wpdb->prefix}{$st_post_type}.is_sale_schedule = 'on'
                        AND {$wpdb->prefix}{$st_post_type}.sale_price_to >= CURDATE()
                        AND {$wpdb->prefix}{$st_post_type}.discount > 0
                        ORDER BY {$wpdb->prefix}{$st_post_type}.sale_price_to ASC
                        LIMIT 0,1
                ";
                break;
        }
        $rs = $wpdb->get_row($query);
        if(!empty($rs)){
//            if($st_post_type == 'st_hotel' || $st_post_type == 'hotel_room') {
//                $min_query = "SELECT
//                *
//                FROM {$wpdb->prefix}st_availability
//                WHERE
//                {$wpdb->prefix}st_availability.check_in > {$date_now} AND
//                {$wpdb->prefix}st_availability.post_id = $rs->post_id AND
//                {$wpdb->prefix}st_availability.price = (SELECT
//                                                  MIN(CAST({$wpdb->prefix}st_availability.price AS DECIMAL(20.2)))
//                                                  FROM {$wpdb->prefix}st_availability WHERE {$wpdb->prefix}st_availability.post_id = $rs->post_id)
//                ORDER BY {$wpdb->prefix}st_availability.check_in ASC
//                LIMIT 0,1
//                ";
//                $rs_min_day = $wpdb->get_row($min_query);
//                $data['rs_min_day'] = $rs_min_day;
//            }
            $data['rs'] = $rs;
            $html =  st()->load_template('vc-elements/st-last-minute-deal/html',$st_post_type, $data);
        }
        return $html;
    }
}
st_reg_shortcode( 'st_last_minute_deal' , 'st_vc_last_minute_deal' );