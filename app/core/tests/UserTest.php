<?php


class UserTest extends TestCase
{
    
    /** @test */
    public function add_new_user()
    {
        $input = array(
            'name' => 'wer',
            'username' => 'wer',
            'password' => 'wer',
            'password_confirmation' => 'wer'
        );
        
        $this->action('POST', 'UserController@postUser', null, $input);
        $this->assertRedirectedTo('admin/users');
        $this->assertSessionHas('message', 'User has been added');
    }
    
    /** @test */
    public function add_existing_user_fails()
    {
        $user = new User;
        $user->name = 'sdfsf';
        $user->username = 'wer';
        $user->password = 'wersdf';
        $user->save();
        
        $input = array(
            'name' => 'wer',
            'username' => 'wer',
            'password' => 'wer',
            'password_confirmed' => 'wer'
        );
        
        $this->action('POST', 'UserController@postUser', null, $input);
        
        $this->assertRedirectedTo('admin/users');
        $this->assertSessionHas('error');
        $error = Session::get('error')->getMessages();
        $this->assertEquals("The username has already been taken.", $error['username'][0]);
    }
    
    /** @test */
    public function mismatching_passwords_fail_validation()
    {
        $input = array(
            'name' => 'wer',
            'username' => 'wer',
            'password' => 'wer',
            'password_confirmed' => 'werdf'
        );
        
        $this->action('POST', 'UserController@postUser', null, $input);
        
        $this->assertRedirectedTo('admin/users');
        $this->assertSessionHas('error');
        $error = Session::get('error')->getMessages();
        $this->assertEquals("The password confirmation does not match.", $error['password'][0]);
    }
    
    /** @test */
    public function username_can_be_updated()
    {
        $user = new User;
        $user->name = 'sdfsf';
        $user->username = 'wer';
        $user->password = 'wersdf';
        $user->save();
        
        $input = array(
            'id' => $user->id,
            'name' => 'new name',
            'username' => 'new_username',
            'password' => '',
            'password_confirmation' => ''
        );
        
        $this->action('POST', 'UserController@postUser', null, $input);
        
        $updatedUser = User::find($user->id);
        $this->assertEquals('new_username', $updatedUser->username);
        $this->assertEquals('new name', $updatedUser->name);
    }
    
    /** @test */
    public function password_does_not_change_if_left_blank()
    {     
        $user = new User;
        $user->name = 'sdfsf';
        $user->username = 'wer';
        $user->password = 'old_password';
        $user->save();
        
        $input = array(
            'id' => $user->id,
            'name' => 'new name',
            'username' => 'new_username',
            'password' => '',
            'password_confirmation' => ''
        );
        
        $this->action('POST', 'UserController@postUser', null, $input);
        
        $updatedUser = User::find($user->id);
        $this->assertTrue(Hash::check('old_password', $updatedUser->password)); 
    }
    
    
}

?>
