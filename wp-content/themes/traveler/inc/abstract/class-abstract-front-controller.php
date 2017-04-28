<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 4/9/15
 * Time: 3:12 PM
 */

if(!class_exists('ST_Abstract_Front_Controller'))
{
    abstract class ST_Abstract_Front_Controller extends ST_Abstract_Controller
    {


        function __construct($arg=array())
        {
            parent::__construct($arg);
        }

        /**
         *
         * @since 1.0.9
         *
         *
         * */
        function init()
        {
            //Add Stats display for posted review
            add_action('st_review_more_content',array($this,'display_posted_review_stats'));

            add_action('save_post', array($this,'update_avg_rate'));

            add_filter('post_class',array($this,'change_post_class'));

            add_filter('pre_get_posts', array($this,'_admin_posts_for_current_author'));

            add_action('wp_ajax_st_top_ajax_search',array($this,'_top_ajax_search'));
            add_action('wp_ajax_nopriv_st_top_ajax_search',array($this,'_top_ajax_search'));

            /**
             *
             * @since 1.0.9
             * */
            add_action('st_search_fields_name',array($this,'get_search_fields_name'),2);

        }

        function get_search_fields_name($array,$post_type='')
        {
            return $array;
        }

        /**
         *
         *
         *
         *
         * @since 1.0.9
         * */
        function _admin_posts_for_current_author($query)
        {
            if($query->is_admin) {
                $post_type=$query->get('post_type');

                if(!current_user_can('manage_options') and (!is_string($post_type) or $post_type!='location'))
                {
                    global $user_ID;
                    $query->set('author',  $user_ID);
                }
            }
            return $query;
        }
        /**
         *
         *
         *
         *
         * @since 1.0.9
         * */
        function _top_ajax_search(){

            if(STInput::request('action')!='st_top_ajax_search')  return;

            //Small security
            check_ajax_referer( 'st_search_security', 'security' );

            $s=STInput::get('s');
            $arg=array(
                'post_type'=>array('post','st_hotel','st_rental','location','st_tours','st_cars','st_activity','hotel_room'),
                'posts_per_page'=>10,
                's'=>$s,
                'suppress_filters'=>false
            );

            $query=new WP_Query();
            $query->is_admin=false;
            $query->query($arg);
            $r=array();

            while($query->have_posts()){
                $query->the_post();
                $post_type=get_post_type(get_the_ID());
                $obj=get_post_type_object($post_type);

                $item=array(
                    'title'=> get_the_title(),
                    'id'=>get_the_ID(),
                    'type'=>$obj->labels->singular_name,
                    'url'=>get_permalink(),
                    'obj'=>$obj
                );

                if($post_type=='location'){
                    $item['url']=home_url(esc_url_raw('?s=&post_type=st_hotel&location_id='.get_the_ID()));
                }


                $r['data'][]=$item;
            }

            wp_reset_query();
            echo json_encode($r);

            die();
        }
        /**
         *
         *
         *
         *
         * @since 1.0.9
         * */
        function change_post_class($class)
        {
            return $class;
        }

        /**
         *
         *
         *
         *
         * @since 1.0.9
         * */
        function update_avg_rate($post_id){
            $avg=STReview::get_avg_rate($post_id);
            update_post_meta($post_id,'rate_review',$avg);
        }

        /**
         *
         *
         *
         *
         * @since 1.0.9
         * */
        function get_review_stats()
        {
            return array();
        }

        /**
         *
         *
         *
         *
         * @since 1.0.9
         * */
        function display_posted_review_stats($comment_id)
        {
            if(get_post_type()==$this->post_type) {
                $data=$this->get_review_stats();

                $output[]='<ul class="list booking-item-raiting-summary-list mt20">';

                if(!empty($data) and is_array($data))
                {
                    foreach($data as $value)
                    {
                        $key=$value['title'];

                        $stat_value=get_comment_meta($comment_id,'st_stat_'.sanitize_title($value['title']),true);

                        $output[]='
                    <li>
                        <div class="booking-item-raiting-list-title">'.$key.'</div>
                        <ul class="icon-group booking-item-rating-stars">';
                        for($i=1;$i<=5;$i++)
                        {
                            $class='';
                            if($i>$stat_value) $class='text-gray';
                            $output[]='<li><i class="fa fa-smile-o '.$class.'"></i>';
                        }

                        $output[]='
                        </ul>
                    </li>';
                    }
                }

                $output[]='</ul>';


                echo implode("\n",$output);
            }
        }
    }
}