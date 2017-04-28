<?php
	/**
	* @since 1.1.9
	*/
	if(!class_exists('STAdminlandingpage')){
		class STAdminlandingpage  extends STAdmin
        {            
            public function __construct()
            {
                parent::__construct();
                //add_action('after_switch_theme', array($this, 'redirect_after_set_up_theme'));
                add_action('admin_menu', array($this, 'st_create_submenu'), 11);
                add_action('admin_enqueue_scripts', array($this, 'add_script'));

				/**
				 * @since 1.2.0
				 */
                add_action('admin_init',array($this,'_save_product_registration'));
            }  

            public function redirect_after_set_up_theme()
            { 
                if (!class_exists( 'OT_Loader' )) return ;
                wp_redirect(admin_url('/admin.php?page=st_admin_registration'));
            }

			/**
			 * @since 1.2.0
			 */
			function _save_product_registration()
			{
				if(STInput::post('st_action')=='save_product_registration' and function_exists('ot_options_id'))
				{
					if(check_admin_referer('traveler_update_registration','traveler_update_registration_nonce'))
					{
						$settings=get_option(ot_options_id());
						$settings['envato_username']=STInput::post('tf_username');
						$settings['envato_apikey']=STInput::post('tf_api');
						$settings['envato_purchasecode']=STInput::post('tf_purchase_code');
						update_option(ot_options_id(),$settings);
					}
				}
			}

            public function add_script()
            {
                wp_enqueue_style('landing_page_css', get_template_directory_uri() . "/css/admin/landing_page.css");
                wp_enqueue_script('landing_page_js', get_template_directory_uri() . "/js/admin/landing_page.js",array('jquery'),null,true);
                if(STInput::get('page')=='st_admin_install')
                {
                    wp_enqueue_script('st-import-js',get_template_directory_uri().'/js/admin/import-content.js',array('jquery'),null,true);
                    wp_localize_script('jquery','st_import_localize',array(
                        'confirm_message'=>__('WARNING: Importing data is recommended on fresh installs only once. Importing on sites with content or importing twice will duplicate menus, pages and all posts.',ST_TEXTDOMAIN),
                    ));
                }
            }

            static function sub_menu_list()
            {

                $return = array();
                if (!self::register_completed()) {
                    array_push($return,
                        array(
                            'page_title' => __("Product Registration", ST_TEXTDOMAIN),
                            'menu_title' => __("Product Registration", ST_TEXTDOMAIN),
                            'menu_slug'  => 'st_product_reg'
                        )
                    );
                }
                array_push($return,
                    array(
                        'page_title' => __("Support", ST_TEXTDOMAIN),
                        'menu_title' => __("Support", ST_TEXTDOMAIN),
                        'menu_slug'  => "st_admin_support"
                    ),
                    array(
                        'page_title' => __("Change Log", ST_TEXTDOMAIN),
                        'menu_title' => __("Change Log", ST_TEXTDOMAIN),
                        'menu_slug'  => "st_admin_change_log"
                    ),
                    array(
                        'page_title' => __("Install Demo", ST_TEXTDOMAIN),
                        'menu_title' => __("Install Demo", ST_TEXTDOMAIN),
                        'menu_slug'  => "st_admin_install"
                    ),
                    /*array(
                        'page_title'	=>__("Fusion plugin" , ST_TEXTDOMAIN),
                        'menu_title'	=>__("Fusion plugin" , ST_TEXTDOMAIN),
                        'menu_slug'	=> "st_admin_plugin"
                        ),*/
                    array(
                        'page_title' => __("System status", ST_TEXTDOMAIN),
                        'menu_title' => __("System status", ST_TEXTDOMAIN),
                        'menu_slug'  => "st_admin_system"
                    ));

                return $return;
            }

            function st_create_submenu()
            {
                $sub_menu = self::sub_menu_list();                

                if(!empty($sub_menu) and is_array($sub_menu)){
                    foreach ($sub_menu as $key => $value) {

                        $page_title = $value['page_title'];
                        $menu_title = $value['menu_title'];
                        $menu_slug  = $value['menu_slug'];

                        add_submenu_page(
                            apply_filters('ot_theme_options_menu_slug','st_traveler_options'), 
                            $page_title, 
                            $menu_title, 
                            'manage_options', 
                            $menu_slug, 
                            array($this, 'get_landing_page')
                        );
                    }
                } 
            }

			
			public function get_landing_page(){                
				echo balancetags($this->load_view('landing_page/landing_page'));
			}
			static function register_completed(){
				return false ; 
			}

		}
		$s = new STAdminlandingpage();
		//$s->__construct();

	}