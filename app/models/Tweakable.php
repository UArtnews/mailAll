<?php

class Tweakable extends Eloquent {
	
	protected $guarded = array('id');

	protected $table = 'tweakable';

	public $timestamps = true;

	public function instance(){
		return $this->belongsTo('Instance', 'instance_id', 'id');
	}

    public function defaultTweakable()
    {
        return $this->hasOne('DefaultTweakable','parameter','parameter');
    }

}