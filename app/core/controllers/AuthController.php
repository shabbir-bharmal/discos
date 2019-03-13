<?php

class AuthController extends BaseController {
  
    public function login()
    {        
        if(Input::has('username') && Input::has('password'))
        {
            if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password'))))
            {
                return Redirect::intended('admin');
            }
        }        
        
        $this->data['tag'] = 'Login';
        return View::make('admin.login', $this->data);
    }
    
    public function logout()
    {
        Auth::logout();
        return Redirect::to('/');
    }

}
