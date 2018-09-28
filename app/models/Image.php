<?php

class Image extends Eloquent {
	protected $guarded = array('id');

	public static $rules = array();

    public function instance()
    {
        return $this->hasOne('Instance','id','instance_id');
    }
}
