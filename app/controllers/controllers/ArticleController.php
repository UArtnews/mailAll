<?php

class ArticleController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index()
	{
        //
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $article = new Article;

        $article->instance_id = Input::get('instance_id');
        $article->title = Input::get('title');
        $article->content = Input::get('content');
		$article->issue_dates = Input::get('issue_dates');
        $article->author_id = '1';
        $article->published = 'N';
		$article->_token = Input::get('_token');

        $article->save();

        return Response::json(array('success' => 'New Article Saved Successfully'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $article = Article::findOrFail($id);

        return Response::json($article);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $article = Article::findOrFail($id);
        $article->instance_id = Input::get('instance_id');
        $article->title = Input::get('title');
        $article->content = Input::get('content');
        $article->submission =
        $article->save();

        return Response::json(array('success' => 'Article Saved Successfully'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$article = Article::findOrFail($id);

        $article->delete();

        return Response::json(array('success' => 'Article Deleted Successfully'));
    }

}