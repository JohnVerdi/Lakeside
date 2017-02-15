<?php

/**
 * Created by PhpStorm.
 * User: kostya
 * Date: 03.02.17
 * Time: 15:57
 */
ini_set('max_execution_time',0);
ini_set('memory_limit', '128M');
class SibersStrimlineAPI
{
    public $response = array();

    public function request(){

    $this->getData();
    }

    public function getData($post_data, $method_name){
        $angryCurl = new AngryCurl(array($this, 'my_callback'));
        $company_code = StreamlineCore_Settings::get_options( 'id' );
        $request_json = json_encode( array( 'methodName' => $method_name, 'params' => array_merge( array( 'company_code' => $company_code ), $post_data ) ) );
        $angryCurl->request('https://www.resortpro.net/api/json', 'POST',$request_json, array('Content-Type: application/json') );

        $angryCurl->execute(3);
    }

    public function my_callback($response, $info, $request)
    {
        return $this->setResponse($response) ;
    }
    public function getResponse(){
        return $this->response;
    }
    public function setResponse($response){
        $responseArray = json_decode($response, true);
        if(isset($responseArray['data']['property']) && count($responseArray['data']['property'])){
           $data = $responseArray['data']['property'];
           if($responseArray['data']['available_properties']['pagination']['total_units'] == 1){
               $data =  array($data);
           }
            $this->response = array_merge($this->response , $data);
        }
        return $this;
    }


}