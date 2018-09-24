<?php

class PublicationOrder extends Eloquent {

    protected $guarded = array('cd');

    public static $key = 'cd';

	protected $table = 'publication_order';

	public $timestamps = false;

}
