<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 4/16/15
 * Time: 8:49 AM
 */

if(!class_exists('ST_Woocommerce'))
{
    class ST_Woocommerce
    {
        private $_is_inited = FALSE;

        /**
         *
         *
         * @since 1.1.1
         * */
        function __construct()
        {
            if ($this->_is_inited) return;

            $this->_is_inited = TRUE;

            // Hook Init
            add_action('woocommerce_before_main_content', array($this, '_add_before_main_content'), 100);
            add_action('woocommerce_after_main_content', array($this, '_add_after_main_content'), 1);
            add_filter('woocommerce_show_page_title', array($this, '_hide_page_title'));
            add_filter('woocommerce_breadcrumb_defaults', array($this, '_change_bc_class'));
            add_filter('st_shop_sidebar', array($this, '_change_shop_sidebar'));

            add_action('pre_get_posts', array($this, '_change_posts_per_page'));

            add_filter('loop_shop_columns', array($this, '_change_loop_shop_columns'));
            add_action('woocommerce_before_shop_loop_item_title', array($this, '_add_thumb_img_hover'));
			add_action('wp_enqueue_scripts' ,array($this , 'st_woo_fix_cookie')) ;
			
            add_filter('wc_price', array($this, '_change_default_price'), 10, 3);
            add_filter('wc_price_args', array($this, '_change_wc_price_args'));
            add_filter('woocommerce_paypal_args',array($this,'_change_wc_order_cyrrency'));

            //add_filter('woocommerce_order_get_items',array($this,'_change_wc_order_item_rate'));

            //add_filter('woocommerce_order_amount_item_subtotal',array($this,'_change_wc_product_rate'));

            add_filter('woocommerce_order_amount_total',array($this,'_change_order_amount_total'));

            // save order item meta

            add_action('woocommerce_add_order_item_meta',array($this,'_add_booking_order_meta'),50,3);

            /**
             * Show extra item information
             * @since 1.1.7
             */
            add_action( 'woocommerce_order_item_meta_end',array($this,'_show_order_item_information'),10,3 );

            // Send Email Woocommerce to Partner
            add_filter('woocommerce_email_recipient_new_order',array($this,'_add_recipient_partner'),10,2);

            // from 1.1. 9 collapse  cart item 
            add_filter('st_woo_cart_is_collapse'  , array($this, 'is_collapse_cart_item_meta'));
            
        }

        /**
         * @since 1.1.9
         * @param $price
         * @return bool|int
         */
        function _change_wc_product_rate($price)
        {
            $price=TravelHelper::convert_money($price);
            return $price;
        }
        /**
         * Convert the money with the currency
         *
         * @since 1.1.9
         */
        function  _change_wc_order_item_rate($items=array())
        {

            if(!empty($items))
            {
                foreach($items as $key=>$value)
                {
                    $items[$key]['line_total']=TravelHelper::convert_money($value['line_total']);
                }
            }

            return $items;
        }
        function _add_recipient_partner($emails,$objects){

            $order_id=isset($objects->id)?$objects->id:false;
            if($order_id)
            {
                // change order currency
                if($currency=get_post_meta($order_id,'_order_currency',true))
                {
                    TravelHelper::change_current_currency($currency);
                }

                $order=wc_get_order($order_id);
                if ( sizeof( $order->get_items() ) > 0 ) {
                    $partner_email_array=array();
                    foreach ($order->get_items() as $item_id => $item) {
                        if(!empty($item['item_meta']['_st_st_booking_id'][0]) and  $st_booking_id=$item['item_meta']['_st_st_booking_id'][0])
                        {
                            $post_type=!empty($item['item_meta']['_st_st_booking_post_type'])?$item['item_meta']['_st_st_booking_post_type']:false;
                            $post_type=st_wc_parse_order_item_meta($post_type);
                            $partner_email=apply_filters('st_get_owner_email_'.$post_type,$st_booking_id);
                            if($partner_email!=$st_booking_id){
                                if(!in_array($partner_email,$partner_email_array)){
                                    $partner_email_array[]=$partner_email;
                                }
                            }

                        }
                    }
                    if(!empty($partner_email_array))
                    {
                        $emails.=','.implode(',',$partner_email_array);
                    }
                }
            }
            return $emails;
        }


        function _show_order_item_information( $item_id, $item, $order)
        {
            $item_meta=isset($item['item_meta'])?$item['item_meta']:array();

            if(!empty($item_meta['_st_st_booking_post_type']))
            {
                $post_type=$item_meta['_st_st_booking_post_type'];
                if(is_array($post_type) and isset($post_type[0])) $post_type=$post_type[0];

                do_action('st_wc_show_order_item_meta_'.$post_type,$item_id,$item,$order);
            }
        }

        function _add_booking_order_meta($item_id,$cart_data,$cart_item_key)
        {
            
            if(isset($cart_data['st_booking_data']) and !empty($cart_data['st_booking_data']))
            {
                $cart_data['st_booking_data']['user_id']= get_current_user_id();
                $st_booking_data=$cart_data['st_booking_data'];
                foreach($st_booking_data as $key=>$value){
                    wc_add_order_item_meta($item_id,'_st_'.$key,$value);
                }
                $order_id=$this->find_order_by_order_item_id($item_id);
                if($order_id){
                    $st_booking_data['status']=get_post_status($order_id);
                    $st_booking_data['wc_order_id']=$order_id;
                }else{

                }

                do_action('st_save_order_item_meta',$st_booking_data,$item_id,'woocommerce');
            }
        }

        function find_order_by_order_item_id($item_id)
        {
            global $wpdb;
            $table_name=$wpdb->prefix . "woocommerce_order_items";
            return $wpdb->get_var("SELECT order_id FROM  {$table_name} WHERE order_item_id=$item_id LIMIT 0,1");
        }

        function _change_order_amount_total($total)
        {
            $debug=debug_backtrace();

            if(isset($debug[4]['function']) and $debug[4]['function']=='get_formatted_order_total') return $total;
            return TravelHelper::convert_money($total);
        }

        /**
         *
         *
         * @since 1.1.1
         * */
        function _change_wc_order_cyrrency($paypal_arg)
        {
            $paypal_arg['currency_code']=TravelHelper::get_current_currency('name');

            return $paypal_arg;
        }

        /**
         *
         * @since 1.1.1
         * */

        function _change_wc_price_args($arg)
        {
            $arg['thousand_separator']='';
            $arg['decimal_separator']='';
            //$arg['decimals']=0;
            return $arg;
        }
        /**
         *
         *
         * @since 1.1.1
         * */
        function _change_default_price($return,$price,$arg=array())
        {

            $price=$price/pow(10,(int)wc_get_price_decimals());

            return TravelHelper::format_money($price);
        }
        function _add_thumb_img_hover()
        {
            global $product;
            $attachment_ids=$product->get_gallery_attachment_ids();
            if ( $attachment_ids ) {
                foreach($attachment_ids as $key=>$value){
                    $image       = wp_get_attachment_image( $value, apply_filters( 'single_product_small_thumbnail_size', 'shop_catalog' ),false,array('class'=>'product-image-hover') );

                    echo ($image);
                    break;
                }
            }
        }
        function _change_loop_shop_columns()
        {
            return 3;
        }

         function _change_posts_per_page($query)
        {
            if($limit=STInput::get('posts_per_page'))
            {
                $query->set('posts_per_page',$limit);
            }


        }

        static function _hide_page_title()
        {
            return false;
        }


        function _add_before_main_content()
        {
            $sidebar=apply_filters('st_shop_sidebar',array('position'=>'left','id'=>'shop'));
            echo '<div class="container shop_main_container" >';
            ?>

            <div class="row shop_main_row">

            <?php

            if($sidebar['position']=='left'){
                do_action('woocommerce_sidebar');
                remove_all_actions('woocommerce_sidebar');
            }
            ?>
            <div class="shop_product_col col-sm-<?php echo ($sidebar['position']=='no')?12:8; ?> col-md-<?php echo ($sidebar['position']=='no')?12:9; echo ($sidebar['position']!='no')?' padding-'.$sidebar['position'].'-lg':'' ?>">

                <?php
        }
        function _add_after_main_content()
        {
            $sidebar = apply_filters('st_shop_sidebar',array('position'=>'left','id'=>'shop'));

            ?>
                </div><!-- End shop_product_col-->
            <?php
            if($sidebar['position']=='right'){
                do_action('woocommerce_sidebar');
                remove_all_actions('woocommerce_sidebar');
            }

            if ($sidebar['position'] != 'right') {
                echo "</div><!--End shop_main_row-->";
            }
            echo "</div><!-- End shop_main_container-->";


            remove_all_actions('woocommerce_sidebar');
        }

        function _change_shop_sidebar($sidebar)
        {
            if(is_archive('product'))
            {
                $sidebar['position']=st()->get_option('shop_sidebar_pos','left');
                $sidebar['id']=st()->get_option('shop_sidebar_id','shop');
            }

            if(is_singular('product'))
            {
                $sidebar['position']=st()->get_option('shop_single_sidebar_pos','left');
                $sidebar['id']=st()->get_option('shop_single_sidebar_id','shop');

                if($meta=get_post_meta(get_the_ID(),'post_sidebar_pos',true))
                {
                    $sidebar['position']=$meta;
                }

                if($meta=get_post_meta(get_the_ID(),'post_sidebar',true))
                {
                    $sidebar['id']=$meta;
                }

            }

            return $sidebar;
        }

         function _change_bc_class($data)
        {
            $data['wrap_before']='<nav class="woocommerce-breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '><div class="container">';
            $data['wrap_after']='</div></nav>';

            return $data;
        }
        function st_woo_fix_cookie(){
            global $post, $woocommerce;
            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
            wp_deregister_script( 'jquery-cookie' );
            wp_register_script( 'jquery-cookie', get_template_directory_uri() . '/js/woo_fix/jquery_cookie' . $suffix . '.js', array( 'jquery' ), '1.3.1', true );
        }
        function is_collapse_cart_item_meta(){
            $is_collapsed = st()->get_option('st_woo_cart_is_collapse', 'off');
            if ($is_collapsed =='on'){
                return true;
            }
            return false;
            
        }

    }

    new ST_Woocommerce();
}