<?php

class ApiController extends AjaxController {

    protected function replySuccess($content)
    {
        return $this->reply($content, 200);
    }

    protected function replyError($content, $http_code = 500)
    {
        return $this->reply($content, $http_code);
    }
    
    protected function reply($content, $http_code = 200)
    {
        $data = ['http' => $http_code, 'data' => $content];
        $response = parent::reply($data, $http_code);
        $response->header('Access-Control-Allow-Origin', '*');
        return $response;
    }
}
