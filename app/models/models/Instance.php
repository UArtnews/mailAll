<?php

class Instance extends Eloquent {
	
	protected $guarded = array('id');

	protected $table = 'instance';

	protected $softDelete = true;

	public $timestamps = true;

	public function tweakables(){
		return $this->hasMany('Tweakable', 'instance_id', 'id');
	}

    public function publications()
    {
        return $this->hasMany('Publication','instance_id','id');
    }

}