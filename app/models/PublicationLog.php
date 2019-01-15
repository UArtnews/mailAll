<?php

class PublicationLog extends Eloquent {
	protected $guarded = array('id');

    protected $table = 'publication_log';

    /*public function instance()
    {
        return $this->hasOne('Instance','id','instance_id');
    }*/
}
