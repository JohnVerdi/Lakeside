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

                foreach ($period as $dt) {
                    $date = $dt->format("Y-m-d");
                    $day = $dt->format('d');
                    $status = 'booked';

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