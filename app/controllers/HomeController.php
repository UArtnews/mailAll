<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function index()
	{

		$data = array(
			'instances' => Instance::all(),
		);

        $data['types'] = array('success', 'info', 'warning', 'danger');

        return View::make('publicLanding', $data);

	}

	public function editors()
	{

		$data = array(
			'instances' => Instance::all(),
		);

        $data['types'] = array('success', 'info', 'warning', 'danger');
        return View::make('editorLanding', $data);
	}



}