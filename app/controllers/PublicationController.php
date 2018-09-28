<?php

class PublicationController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

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
        $publication = new Publication;

        $publication->instance_id = Input::get('instance_id');
        $publication->publish_date = date('Y-m-d',strtotime(Input::get('publish_date')));
        $publication->banner_image = Input::get('banner_image');
        $publication->type = Input::get('type');
        $publication->published = 'N';

        $publication->save();

        $i = 0;
        foreach(json_decode(stripslashes(Input::get('article_order'))) as $article){
            $publicationOrder = new PublicationOrder;

            $publicationOrder->publication_id = $publication->id;
            $publicationOrder->article_id = $article[0];
            $publicationOrder->order = $i;
            $publicationOrder->likeNew = $article[1];

            $publicationOrder->save();

            $i += 1;
        }

        return Response::json(array(
            'success' => 'Publication Saved Successfully',
            'publication_id' => $publication->id
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
        $publication = Publication::where('id', $id)->first();

        $inputArr = array();

        foreach(Input::all() as $index => $value){
            if($index == 'banner_image'){
                $inputArr[$index] = $value;
            }elseif(strlen($value) > 0){
                $inputArr[$index] = $value;
            }
        }

        if(isset($inputArr['publish_date'])){
            $inputArr['publish_date'] = date('Y-m-d', strtotime($inputArr['publish_date']));
        }

        $publication->fill($inputArr)->save();

        if(Request::ajax()){
            return Response::json(array(
                    'success'   => 'Succesfully updated publication!'
                ));
        }else{
            return Redirect::back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        Publication::where('id', $id)->delete();

        PublicationOrder::where('publication_id', $id)->delete();

        return Response::json(array('success' => 'Publication Successfully Deleted!'));
    }

    public function updateOrder()
    {
        $publication_id = urldecode(Request::segment(4));

        //Update Publication Model
        $publication = Publication::where('id', $publication_id)->first()->touch();

        //Update PublicationOrder Model
        PublicationOrder::where('publication_id',$publication_id)->delete();
        $i = 0;

        $added = array();

        foreach(json_decode(stripslashes(Input::get('article_order'))) as $article){
            if(!in_array($article[0], $added)) {
                array_push($added, $article[0]);

                $publicationOrder = new PublicationOrder;
                $publicationOrder->publication_id = $publication_id;
                $publicationOrder->article_id = $article[0];
                $publicationOrder->order = $i;
                $publicationOrder->likeNew = $article[1];
                $publicationOrder->save();

                $i += 1;
            }
        }

        return Response::json(array('success' => 'Publication Order Successfully Saved!'));
    }

    public function updateType($id){
        if(Input::has('type')) {
            $publication = Publication::where('id', $id)->first();

            $publication->type = Input::get('type');

            $publication->save();

            return Response::json(array('success' => 'Publication Type Successfully Saved!'));
        }else{
            return Response::json(array('error' => 'Type must be defined!'));
        }
    }

}