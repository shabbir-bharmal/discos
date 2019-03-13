<?php

class AjaxController extends BaseController {
    
    public $message;
    public $error;
    
    protected $response;
    
    protected function reply($content, $http_code = 200)
    {
        $response = LResponse::make(json_encode($content), $http_code);
        return $response;
    }
}
