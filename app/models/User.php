<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

    public $guarded = array('id');

    protected $softDelete = true;
<<<<<<< HEAD
=======
    
    protected $primaryKey = 'id';
>>>>>>> mailAllProd/master

    public static $rules = array(
        'uanet' => 'required',
        'email' => 'required|email',
        'last'  => 'required',
        'first' => 'required'
    );

    public function permissions(){
        return $this->hasMany('UserPermission', 'user_id', 'id');
    }

    public function hasPermission($instanceId, $node){
        if($this->isSuperAdmin()) {
            return true;
        }elseif(UserPermission::where('user_id', $this->id)->where('instance_id', $instanceId)->where('node', $node)->count() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function isEditor($instanceId){
        if($this->isSuperAdmin()){
            return true;
        }elseif($this->isAdmin($instanceId)){
            return true;
        }elseif($this->hasPermission($instanceId, 'edit') || $this->hasPermission(0, 'edit')){
            return true;
        }else{
            return false;
        }
    }

    public function isAdmin($instanceId){
        if($this->isSuperAdmin()){
            return true;
        }elseif($this->hasPermission($instanceId, 'admin') || $this->hasPermission(0, 'admin')){
            return true;
        }else{
            return false;
        }
    }

    public function isSuperAdmin(){
        if(UserPermission::where('user_id', $this->id)->where('instance_id', 0)->where('node', 'superAdmin')->count() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	public function getAuthPassword()
	{
		return $this->password;
	}

	public function getReminderEmail()
	{
		return $this->email;
	}

	public function getRememberToken()
	{
	    return $this->remember_token;
	}

	public function setRememberToken($value)
	{
	    $this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
	    return 'remember_token';
	}

}