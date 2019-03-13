<?php

class AdminController extends BaseController {
    
    public $message;
    public $error;
    
    
	public function dashboard()
	{
		return View::make('admin.dashboard', $this->data);
	}
}
