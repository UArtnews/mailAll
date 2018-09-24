<?php

class Submission extends Eloquent {
	protected $guarded = array('id');

	protected $table = 'submission';

	public $timestamps = true;

    public static $rules = array(
		'title'=> 'required',
		'content'=> 'required',
        //'event_start_date'=> 'required|date',
        //'event_end_date'=> 'required|date',
        //'start_time'=> 'required',
        //'end_time'=> 'required',
        //'location'=> 'required',
        'name'=> 'required',
        'email'=> 'required|email',
        'phone'=> 'required',
    );

}
