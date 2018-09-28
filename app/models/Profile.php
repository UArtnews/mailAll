<?php

class Profile extends Eloquent {
	protected $guarded = array('id');

    protected $table = 'profiles';

    public function instance()
    {
        return $this->hasOne('Instance','id','instance_id');
    }
}
