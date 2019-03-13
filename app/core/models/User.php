<?php
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Zizaco\Entrust\HasRole;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait, HasRole;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');
    
    protected $guarded = array('password_confirmation');
    
    protected $fillable = array('username', 'password', 'name');

    protected $appends = ['login_link'];
    
    public static $validation = array(
        'create' => array(
            'name' => 'required',
            'username' => 'unique:users|required',
            'password' => 'required|confirmed'
        ),
        'update' => array(
            'name' => 'required',
            'username' => 'required|unique:users,username,',
            'password' => 'confirmed'
        )
    );
    
    public function setPasswordAttribute($value)
    {
        if ($value != '') {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    public function getLoginLinkAttribute()
    {
        return url('admin') . '?login_token=' . $this->login_token;
    }

    public function roles() {

        return $this->belongsToMany('Role');
    
    }
    
    /*public function save(array $options = array()) {
        // remove password fields if not sent
        
        if(!isset($this->attributes['password']) || $this->attributes['password'] == '' || !isset($this->attributes['password_confirmation'])) {
            unset($this->password);
            unset($this->attributes['password']);
        } else if($this->isDirty('password')) {
            echo 'is dirty';
            $this->password = Hash::make($this->password);
        }
        
        unset($this->password_confirmation);

        return parent::save($options);
    }*/

}
