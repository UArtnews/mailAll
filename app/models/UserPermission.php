<?php

class UserPermission extends Eloquent {

    protected $guarded = array('id');

    protected $table = 'user_permission';

    public $timestamps = false;


    public function user(){
        return $this->hasOne('User', 'id', 'user_id');
    }
}