<?php
/**
*@since 1.1.8
**/
require_once WP_PLUGIN_DIR . '/streamline-core-nov11/resortpro.php';

if(!class_exists('HotelHelper')){
    class HotelHelper{
        public function init(){
            add_action('wp_ajax_st_get_availability_hotel_room_custom', array(&$this, '_get_availability_hotel_room_custom'));
            add_action('wp_ajax_nopriv_st_get_availability_hotel_room_custom', array(&$this, '_get_availability_hotel_room_custom'));
        }

        /**
         * Get API prices listing(ajax)
         */
        function _get_availability_hotel_room_custom()
        {
            $data = StreamlineCore_Wrapper::GetPropertyRatesRawData( $_POST['post_id'] );
            $result = array();

            foreach ($data['data']['rates'] as $period) {
                $start    = new DateTime($period['period_begin']);
                $end      = new DateTime($period['period_end']);
                $interval = DateInterval::createFromDateString('1 day');
                $period   = new DatePeriod($start, $interval, $end);

                foreach ($period as $dt) {
                    $date = $dt->format("Y-m-d");

                    $result[] = array(
                        'date' => $date,
                        'test' => 'test'
                    );
                }
            }

            wp_send_json($result);
        }
    }

$hotelhelper = new HotelHelper();
$hotelhelper->init();
}
?>