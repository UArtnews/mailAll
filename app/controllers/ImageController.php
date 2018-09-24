<?php

class ImageController extends \BaseController
{

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

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $instance = Instance::findOrFail(Input::get('instance_id'));

        if (Image::where('filename', Input::file('imageFile')->getClientOriginalName())->where('instance_id', $instance->id)->count() > 0) {
            return Redirect::back()->with(array('error' => 'An Image with that Filename Already Exists'));
        }

        $pathName = preg_replace('/[^\w]+/', '_', $instance->name);

        $image = new Image;
        Input::file('imageFile')->move(
            app_path() . '/../images/' . $pathName,
            Input::file('imageFile')->getClientOriginalName()
        );

        $image->instance_id = $instance->id;
        $image->title = Input::get('title');
        $image->filename = Input::file('imageFile')->getClientOriginalName();
        $image->save();

        return Redirect::back()->with(array('success' => 'File Uploaded Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
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
        $image = Image::findOrFail($id);
        $instance = Instance::findOrFail($image->instance_id);

        $pathName = preg_replace('/[^\w]+/', '_', $instance->name);

        if (Input::hasFile('imageFile')) {
            if (Image::where('id', '!=', $id)->where(
                    'filename',
                    Input::file('imageFile')->getClientOriginalName()
                )->count() > 0
            ) {
                return Redirect::back()->with(array('error' => 'An Image with that Filename Already Exists'));
            }
            unlink(app_path() . '/../images/' . $pathName . '/' . $image->filename);
            Input::file('imageFile')->move(
                app_path() . '/../images/' . $pathName,
                Input::file('imageFile')->getClientOriginalName()
            );

            $image->filename = Input::file('imageFile')->getClientOriginalName();
            $image->save();

            return Redirect::back()->with(array('success' => 'Image Re-Uploaded'));

        } elseif (Input::has('filename')) {

            if (Input::get('filename') == $image->filename && Image::where('id', '!=', $id)->where(
                    'filename',
                    Input::get('filename')
                )->count() <= 1
            ) {

            } elseif (Image::where('id', '!=', $id)->where('filename', Input::get('filename'))->count() > 0) {
                return Response::json(array('error' => 'An Image with that Filename Already Exists'));
            }
            rename(
                app_path() . '/../images/' . $pathName . '/' . $image->filename,
                app_path() . '/../images/' . $pathName . '/' . Input::get('filename')
            );

            $image->title = Input::get('title');
            $image->filename = Input::get('filename');

            $image->save();

        }

        return Response::json(array('success' => 'Image Saved Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $image = Image::findOrFail($id);
        $image->delete();

        return Response::json(array('success' => 'Image Deleted Successfully'));
    }

}