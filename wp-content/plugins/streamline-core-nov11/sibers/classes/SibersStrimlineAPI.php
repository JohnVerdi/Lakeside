<?php

/**
 * Created by PhpStorm.
 * User: kostya
 * Date: 03.02.17
 * Time: 15:57
 */
class SibersStrimlineAPI
{

    public function request(){

        $angryCurl = new AngryCurl('my_callback');
        $angryCurl->request('http://json.parser.online.fr/', 'POST', null, $this->headers, $this->curlOptions);

        $angryCurl->execute(1);

        $url = $postData = $method = $angryCurl = null;
    }


    function my_callback($response, $info, $request)
    {
        var_dump(1);exit;
        $this->response = $response ;
    }

}