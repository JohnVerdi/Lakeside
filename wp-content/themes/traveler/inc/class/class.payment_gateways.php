<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STPaymentGateways
 *
 * Created by ShineTheme
 *
 */

if(!class_exists('STPaymentGateways'))
{
    class STPaymentGateways
    {
        static $_payment_gateways=array();
        static function _init()
        {
            //load abstract class
            STTraveler::load_libs(array(
                'abstract/class-abstract-payment-gateway'
            ));

            //Load default gateways
            self::_load_default_gateways();


            if(class_exists('STGatewaySubmitform'))
            {
                self::$_payment_gateways['st_submit_form']=new STGatewaySubmitform();
            }
            if(class_exists('STGatewayPaypal'))
            {
                self::$_payment_gateways['st_paypal']=new STGatewayPaypal();
            }
            if(class_exists('ST_Stripe_Payment_Gateway'))
            {
                self::$_payment_gateways['st_stripe']=new ST_Stripe_Payment_Gateway();
            }

            add_action('init',array(__CLASS__,'_do_add_gateway_options'));
        }
        static function _do_add_gateway_options()
        {
            if(function_exists('ot_settings_id'))
            add_filter( ot_settings_id() . '_args', array(__CLASS__,'_add_gateway_options') );
        }

        static function _add_gateway_options($settings=array())
        {
            $option_fields=array();

            $all=self::get_payment_gateways();
            if(is_array($all) and !empty($all))
            {
                foreach($all as $key=>$value)
                {
                    $field=$value->get_option_fields();

                    $default=array(
                        array(

                           'id'     => 'pm_gway_'.$key.'_tab',
                           'label'  =>sprintf(__('%s Options',ST_TEXTDOMAIN),$value->get_name()),
                            'type'  =>'tab',
                            'section'  =>'option_pmgateway'
                        ),
                        array(

                           'id'     => 'pm_gway_'.$key.'_enable',
                           'label'  =>sprintf(__('Enable %s',ST_TEXTDOMAIN),$value->get_name()),
                            'type'  =>'on-off',
                            'std'   =>$value->get_default_status()?'on':'off',
                            'section'  =>'option_pmgateway'
                        ),

                    );

                    $option_fields=array_merge($option_fields,$default);

                    if($field and is_array($field))
                    {
                        $option_fields=array_merge($option_fields,$field);
                    }
                }
            }

            if(!empty($option_fields)){
                $settings['sections'][]=array(
                    'id'          => 'option_pmgateway',
                    'title'       => __( '<i class="fa fa-money"></i> Payment Options', ST_TEXTDOMAIN )
                );

                $settings['settings']=array_merge($settings['settings'],$option_fields);
            }

            return $settings;
        }

        static function get_payment_gateways()
        {
            $all= apply_filters('st_payment_gateways',self::$_payment_gateways);
			return $all;
        }
        /**
         *
         *
         * @since 1.0.1
         * @update 1.1.7
        */
        static function get_payment_gateways_html($post_id=false)
        {

            $all=self::get_payment_gateways();
                    
            if(is_array($all) and !empty($all))
            {
                $i=1;
                $available=array();

                foreach($all as $key=>$value)
                {
                    if(method_exists($value,'is_available') and $value->is_available())
                    {

                        if(!$post_id){
                            $post_id=STCart::get_booking_id();
                        }
                        if($value->is_available($post_id)){
                            $available[$key]=$value;
                        }

                    }
                }
                if(!empty($available))
                {
					if(count($available)==1)
					{
						foreach($available as $key=>$value){
							echo "<div class='payment_gateway_item'>  ";
							$value->html();
							echo '<input type="hidden" name="st_payment_gateway" value="'.$key.'">';
							echo "</div>";

							$i++;
						}
					}else{

						?>
						<div class="st-payment-tabs-wrap">
							<ul class="st-payment-tabs clearfix">
								<?php
								$i=0;
								foreach($available as $key=>$value){
									?>
									<li class="payment-gateway payment-gateway-<?php echo esc_attr($key);  ?> <?php echo (!$i)?'active':FALSE; ?>" data-gateway="<?php echo esc_attr($key) ?>">
										<label class="payment-gateway-wrap" >
											<div class="logo">
												<div class="h-center">
													<?php printf('<img src="%s" alt="%s">',$value->get_logo(),$value->get_name()) ?>
												</div>
											</div>
											<h4 class="gateway-name"><?php echo esc_html($value->get_name()); ?></h4>
											<input type="radio" class="i-radio payment-item-radio" name="st_payment_gateway" <?php echo (!$i)?'checked':FALSE; ?> value="<?php echo esc_attr($key) ?>">
										</label>
									</li>

									<?php
									$i++;
								}
								?>
							</ul>
							<div class="st-payment-tab-content">
								<?php
								foreach($available as $key=>$value){
									?>
									<div class="st-tab-content" data-id="<?php echo esc_attr($key) ?>">
										<?php $value->html(); ?>
									</div>
									<?php
								}
								?>
							</div>

						</div>
						<?php
					}

                }
            }
        }

        /**
         *
         *
         * @param $id
         * @param bool $post_id
         * @return mixed
         */
        static function get_gateway($id,$post_id=false)
        {
            $all=self::get_payment_gateways();
            if(isset($all[$id]))
            {
                $value=$all[$id];
                if(method_exists($value,'is_available') and $value->is_available($post_id))
                {
                    return $value;
                }
            }

        }

        static function get_gatewayname($id)
        {
            $all=self::get_payment_gateways();
            if(isset($all[$id]))
            {
                $value=$all[$id];
                if(method_exists($value,'get_name'))
                {
                    return $value->get_name();
                }else return $id;
            }
        }


		/**
		 * Check if a gateway is allow to show the booking infomation by gived gateway id
		 * @param bool|FALSE $id
		 * @return bool
		 */
        static function gateway_success_page_validate($id=false)
        {
            $all=self::get_payment_gateways();
            if(isset($all[$id]))
            {
                $value=$all[$id];
                if(method_exists($value,'get_name'))
                {
					$order_code=STInput::get('order_code');
					$order_token_code=STInput::get('order_token_code');
					if($order_token_code)
					{
						$order_code=STOrder::get_order_id_by_token($order_token_code);
					}

					$status=get_post_meta($order_code,'status',true);

					$result=true;


					if($status=='incomplete')
					{
						if($value->is_check_complete_required())
						{
							$r=$value->check_complete_purchase($order_code);
						}else{
							$r=array(
								'status'=>true
							);
						}

						if($r)
						{
							if(isset($r['status'])){
								if($r['status'])
								{
									$result=true;
									update_post_meta($order_code,'status','complete');
									$status='complete';

									STCart::send_mail_after_booking($order_code, true);
									do_action('st_booking_change_status','complete',$order_code,$value->getGatewayId());

								}elseif(isset($r['message']) and $r['message'])
								{
									$result=false;
									STTemplate::set_message($r['message'],'danger');
								}

								if(isset($r['redirect_url']) and $r['redirect_url'])
								{
									echo "<script>window.location.href='".$r['redirect_url']."'</script>";die;
								}
							}
						}

					}

					if($status=='incomplete')
					{
						$result=false;
						STTemplate::set_message(__("Sorry! Your payment is incomplete." , ST_TEXTDOMAIN) );
					}


					return $result;
                }

            }else{
                STTemplate::set_message(__('Sorry! Your Payment Gateway is not valid',ST_TEXTDOMAIN),'danger');
            }
        }


		/**
		 * Process the check out
		 * @param $gateway
		 * @param $order_id
		 * @return array
		 */
		static function do_checkout($gateway,$order_id)
		{
			$total = get_post_meta($order_id, 'total_price', true);
			// check status complete first
			if(get_post_meta($order_id,'status',true)=='complete')
			{
				return array(
					'status'=>true,
					'redirect'=>STCart::get_success_link($order_id)
				);
			}


			if(!$gateway->stop_change_order_status()){
				update_post_meta($order_id,'status','incomplete');
				do_action('st_booking_change_status','incomplete',$order_id,$gateway->getGatewayId());
			}

			try{

				$res=$gateway->do_checkout($order_id);

				if($res['status']){
					if(!$gateway->is_check_complete_required() and !$gateway->stop_change_order_status()){

						update_post_meta($order_id,'status','complete');
						STCart::send_mail_after_booking($order_id, true);
						do_action('st_booking_change_status','complete',$order_id,$gateway->getGatewayId());

					}
					if(!isset($res['redirect']) or !$res['redirect']){
						$res['redirect']=STCart::get_success_link($order_id);
					}
				}else{
					if(!isset($res['message'])){
						$res['message']=FALSE;
					} else{
						$res['message']=sprintf(__('<br>Message: %s',ST_TEXTDOMAIN),$res['message']);
					}
					$res['message']=sprintf(__('Your order has been made but we can process the payment with %s. %s ',ST_TEXTDOMAIN),$gateway->get_name(),$res['message']);
				}

			}catch(Exception $e){
				$res['status']=0;
				$message=sprintf(__('<br>Message: %s',ST_TEXTDOMAIN),$e->getMessage());
				$res['exception']=$e;
				$res['message']=sprintf(__('Your order has been made but we can process the payment with %s. %s',ST_TEXTDOMAIN),$gateway->get_name(),$message);
			}


			$res['step']='do_checkout';

			$res['order_id']=(int)$order_id;

			return $res;
		}


        static function _load_default_gateways()
        {
			if(!class_exists('Omnipay\Omnipay')) return false;

            $path = STTraveler::dir('gateways');
            $results = scandir($path);

            foreach ($results as $result) {
                if ($result === '.' or $result === '..') continue;
                if (is_dir($path . '/' . $result)) {

                    $file=$path.'/'.$result.'/'.$result.'.php';
                    if(file_exists($file))
                    {
                        include_once $file;
                    }

                }
            }
        }
    }

    STPaymentGateways::_init();
}
