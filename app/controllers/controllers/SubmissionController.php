<?php
header("Access-Control-Allow-Origin: *");
class SubmissionController extends BaseController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        //Grab Instance Name from URI
        $instanceName = urldecode(Request::segment(2));

        //Fetch Instance out of DB
        $instance = Instance::where('name', strtolower($instanceName))->firstOrFail();

        $data = array(
            'instance'                 => $instance,
            'instanceId'               => $instance->id,
            'instanceName'             => $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'isEdit'                   => false
        );

        if(isset($data['tweakables']['global-accepts-submissions'])){
            if($data['tweakables']['global-accepts-submissions']){
                $data['submission'] = true;
            }else{
                $data['submission'] = false;
            }
        }else{
            if($data['default_tweakables']['global-accepts-submissions']){
                $data['submission'] = true;
            }else{
                $data['submission'] = false;
            }
        }

        //Get upcoming publications to submit to
        $data['publications'] = Publication::where('instance_id', $instance->id)->mostRecent(4)->regular()->get();
        //return $data;
        return View::make('public.submission', $data);
	}

    public function preSubmit(){
        //Grab Instance Name from URI
        $instanceName = urldecode(Request::segment(2));

        //Fetch Instance out of DB
        $instance = Instance::where('name', strtolower($instanceName))->firstOrFail();


        $data = array(
            'instance'                 => $instance,
            'instanceId'               => $instance->id,
            'instanceName'             => $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'isEdit'                   => false
        );
        if((isset($data['tweakables']['publication-submission-splash']) ? $data['tweakables']['publication-submission-splash'] : $data['default_tweakables']['publication-submission-splash']) == ''){
            return Redirect::to('/submit/'.$instanceName);
        }

        if(isset($data['tweakables']['global-accepts-submissions'])){
            if($data['tweakables']['global-accepts-submissions']){
                $data['submission'] = true;
            }else{
                $data['submission'] = false;
            }
        }else{
            if($data['default_tweakables']['global-accepts-submissions']){
                $data['submission'] = true;
            }else{
                $data['submission'] = false;
            }
        }

        return View::make('public.preSubmission', $data);
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('submissions.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $instance = Instance::findOrFail(Input::get('instance_id'));
		
        $validator = Validator::make(Input::all(),Submission::$rules);

        if($validator->fails()){
            return Response::json(array(
                    'error' => 'Incorrect or Missing Fields',
                    'messages' => $validator->failed()
                ));
        }else{
            $submission = new Submission;

            $submission->instance_id = Input::get('instance_id');
            $submission->user_id = Auth::user()->id;
            $submission->uanet = Auth::user()->uanet;
            $submission->title = Input::get('title');
            $submission->content = Input::get('content');
            $submission->event_start_date = date('Y-m-d',strtotime(Input::get('event_start_date')));
            $submission->event_end_date = date('Y-m-d',strtotime(Input::get('event_end_date')));
            $submission->start_time = date('H:i:s',strtotime(Input::get('start_time')));
            $submission->end_time = date('H:i:s',strtotime(Input::get('end_time')));
            $submission->location = Input::get('location');
			$submission->contactName = Input::get('contactName');
			$submission->contactEmail = Input::get('contactEmail');
			$submission->contactPhone = Input::get('contactPhone');
            $submission->issue_dates = Input::get('issue_dates');
            $submission->name = Input::get('name');
            $submission->email = Input::get('email');
            $submission->phone = Input::get('phone');
            $submission->organization = Input::get('organization');
            $submission->department = '';
            $submission->publish_contact_info = Input::get('publish_contact_info');

            $submission->save();
			
			$view = 'Thank you for the submission.  To review and/or edit the submission http://share.uakron.edu/mailAll/resource/submission/'. $submission['id'] .'.';
			$to   = Input::get('email');
			$subject = 'Zipmail Submission - ' . Input::get('title');
			$message = $view;
			$headers = 'From: webmaster@uakron.edu' . "\r\n" .
				'Reply-To: webmaster@uakron.edu' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
			
			mail($to, $subject, $message, $headers);
			
            return Response::json(array(
                'success'   => 'Publication Submitted Successfully',
                'submission_id' => $submission->id
            ));
			
        }

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        //Grab Instance Name from submission
        $submission = Submission::find($id);

        $instanceId = $submission->instance_id;

        //Fetch Instance out of DB
        $instance = Instance::find($instanceId);

        $data = array(
            'instance'                 => $instance,
            'instanceId'               => $instance->id,
            'instanceName'             => $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'article'                  => $submission
        );

        if(isset($data['tweakables']['global-accepts-submissions'])){
            if($data['tweakables']['global-accepts-submissions']){
                $data['submission'] = true;
            }else{
                $data['submission'] = false;
            }
        }else{
            if($data['default_tweakables']['global-accepts-submissions']){
                $data['submission'] = true;
            }else{
                $data['submission'] = false;
            }
        }

        return View::make('submissions.show', $data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        //Grab Instance Name from submission
        $submission = Submission::find($id);

        $instanceId = $submission->instance_id;

        //Fetch Instance out of DB
        $instance = Instance::find($instanceId);

        //Ensure this user is the author
        if($submission->uanet != Auth::user()->uanet){
            return Redirect::back()->withError('You are not the author of this article!');
        }

        $data = array(
            'instance'                 => $instance,
            'instanceId'               => $instance->id,
            'instanceName'             => $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'article'                  => $submission,
            'id'                       => $id,
            'isEdit'                   => true,
            'issue_dates'              => $submission->issue_dates == '' ? array() : json_decode(stripslashes($submission->issue_dates)),
        );

        if(isset($data['tweakables']['global-accepts-submissions'])){
            if($data['tweakables']['global-accepts-submissions']){
                $data['submission'] = true;
            }else{
                $data['submission'] = false;
            }
        }else{
            if($data['default_tweakables']['global-accepts-submissions']){
                $data['submission'] = true;
            }else{
                $data['submission'] = false;
            }
        }

        //Get upcoming publications to submit to
        $data['publications'] = Publication::where('instance_id', $instance->id)->where('publish_date','>',date('Y-m-d'))->where('type','regular')->orderBy('publish_date','ASC')->limit(4)->get();

        return View::make('public.submission', $data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $instance = Instance::findOrFail(Input::get('instance_id'));

        $validator = Validator::make(Input::all(),Submission::$rules);

        if($validator->fails()){
            return Response::json(array(
                    'error' => 'Incorrect or Missing Fields',
                    'messages' => $validator->failed()
                ));
        }else{
            $submission = Submission::find($id);

            $submission->instance_id = Input::get('instance_id');
            $submission->user_id = Auth::user()->id;
            $submission->uanet = Auth::user()->uanet;
            $submission->title = Input::get('title');
            $submission->content = Input::get('content');
            $submission->event_start_date = date('Y-m-d',strtotime(Input::get('event_start_date')));
            $submission->event_end_date = date('Y-m-d',strtotime(Input::get('event_end_date')));
            $submission->start_time = date('H:i:s',strtotime(Input::get('start_time')));
            $submission->end_time = date('H:i:s',strtotime(Input::get('end_time')));
            $submission->location = Input::get('location');
			$submission->contactName = Input::get('contactName');
			$submission->contactEmail = Input::get('contactEmail');
			$submission->contactPhone = Input::get('contactPhone');
            $submission->issue_dates = Input::get('issue_dates');
            $submission->name = Input::get('name');
            $submission->email = Input::get('email');
            $submission->phone = Input::get('phone');
            $submission->organization = Input::get('organization');
            $submission->department = '';
            $submission->publish_contact_info = Input::get('publish_contact_info');

            $submission->save();
			
			$view = 'Thank you for the submission.  To review and/or edit the submission http://share.uakron.edu/mailAll/resource/submission/'. $submission['id'] .'.';
			$to   = Input::get('email');
			$subject = 'Zipmail Submission - ' . Input::get('title');
			$message = $view;
			$headers = 'From: webmaster@uakron.edu' . "\r\n" .
				'Reply-To: webmaster@uakron.edu' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
			
			mail($to, $subject, $message, $headers);
			
            return Response::json(array(
                    'success'   => 'Publication Submitted Successfully',
                    'submission_id' => $submission->id
                ));
        }
		/*return Response::json(array(
                    'success'   => 'Publication Submitted Successfully',
                    'submission_id' => $submission->id
                ));*/
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
        $submission = Submission::findOrFail($id);
        $submission->delete();

        return Response::json(array(
            'success'   => 'Submission Successfully Deleted'
        ));
	}

	public function promoteSubmission()
	{
        $instanceName = urldecode(Request::segment(2));
        $id = urldecode(Request::segment(3));

        $instance = Instance::where('name',$instanceName)->first();

        $submission = Submission::findOrFail($id);

        $content = $submission->content;

        $content .= '<p><strong>Date:</strong>&nbsp;'.date('F jS', strtotime($submission->event_start_date)).
            '<br/><strong>Time:</strong>&nbsp;'.date('g:ia', strtotime($submission->start_time)).' to '.date('g:ia', strtotime($submission->end_time)).
            '<br/><strong>Location:</strong>&nbsp;'.$submission->location;

        if($submission->publish_contact_info == 'Y'){
            $content .= '<br/><strong>Contact:</strong>&nbsp;'.$submission->contactName.', '.$submission->contactEmail;
        }
		$content .= '</p>';

        $article = new Article;

        $article->instance_id = $instance->id;
        $article->title = stripslashes($submission->title);
        $article->content = stripslashes($content);
        $article->issue_dates = stripslashes($submission->issue_dates);
		$article->author_id = '1';
        $article->published = 'N';
        $article->submission = 'Y';

        $article->save();

        $submission->promoted = 'Y';

        $submission->save();
 
        return Response::json(array(
            'success'   => 'Submission Promoted to Article',
            'article_id'=> $article->id
        ));

	}

}
