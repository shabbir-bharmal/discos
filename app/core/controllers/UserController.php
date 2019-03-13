<?php

use Illuminate\Support\Facades\Hash;

class UserController extends AdminController {
    
    private $notifications = array(
        'create' => 'User has been added',
        'update' => 'User has been saved'
    );

    function __construct()
    {
        parent::__construct();
        $this->data['tag'] = 'User management';
    }
    
    public function getIndex()
    {
        return $this->getAll();
    }
    
    public function getAll()
    {
        $this->data['users'] = User::where('deleted','=',0)->get();
        return View::make('admin.users', $this->data);
    }

    public function postUser()
    {  
        $input = Input::except('_token');
        
        if(Input::has('delete')) {
            return $this->deleteUser($input['id']);
        }
        
        if(Input::has('id')) {
            
            $rules = User::$validation['update'];
            $rules['username'] .= Input::get('id') . ',id,deleted,0';
            
            $validation = Validator::make($input, $rules);
            
            if($validation->fails()) {  
                \Input::flash();      
                return Redirect::to('admin/users')
                    ->with('message', $this->message)
                    ->with('error', $validation->messages());
            }
            
            unset($input['password_confirmation']);
            
            if ($input['password'] == '') {
                unset($input['password']);
            }
            
            $user = User::find($input['id']);
            
            if ($user->update($input)) {
                $this->message = $this->notifications['update']; 
            } else {
                $this->error = 'An error occured';
                // todo: record the msg (or do this in a filter?)
            }		
            
        } else {
            
            $validation = Validator::make($input, User::$validation['create']);
            
            if($validation->fails()) {     
                \Input::flash();   
                return Redirect::to('admin/users')
                    ->with('message', $this->message)
                    ->with('error', $validation->messages());
            } 
            
            unset($input['password_confirmation']);
			$user = new \User();
            if ($user->fill($input)->save()) {
                $this->message = $this->notifications['create'];
						/*save role_user details */
						$role = $input['role'];
						$role_data = Role::where('name', $role)->first();
						$role_id = $role_data['id'];
						$userinfo = User::where('username', '=', $input['username'] )->first();
						$user->roles()->save($role_data);
						//$user->roles()->attach([$userinfo['id'],$role_id]);
			
            } else {
                $this->error = 'An error occured';
            }
			
			
        }
		
        return Redirect::to('admin/users/')
            ->with('message', $this->message)
            ->with('error', $this->error);
    }

    public function getDelete($id)
    {
        return $this->deleteUser($id);
    }
    
    public function deleteUser($id)
    {
        if(intval($id) > 0) {
            $user = User::find($id);
            $user->deleted = 1;
            $user->save();
        }
        //$user->roles()->sync([]);
        return Redirect::to('admin/users');        
    }    
    
    public function getUser($id)
    {
        if(intval($id) == 0) {
            return Redirect::to('admin/users');
        }
        
        $data = User::find($id);
        
        return json_encode($data);
    }

    public function getEdit($id)
    {
        $this->data['user'] = \User::find($id);
        return \View::make('admin.users-edit', $this->data);
    }

    public function getRegenerateLoginToken($id)
    {
        $user = User::find($id);
        $user->login_token = Hash::make(microtime());
        $user->save();

        return Redirect::to('admin/users');
    }
}
