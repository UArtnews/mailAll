<?php

class EditorController extends \BaseController
{
    public function __construct(){
    }
    
    public function route($instanceName, $action = null, $subAction = null){
        $app = app();
        $editorController = $app->make('EditorController');
        //Fetch Instance out of DB
        $instance = Instance::where('name', strtolower(urldecode($instanceName)))->firstOrFail();

		$page = "";
		if(Session::has('page')){
			$page = "?page=".Session::get('page');
		}
		if(Input::has('page')){
			Session::put('page', Input::get('page'));
			$page = "?page=".Input::get('page');
		}

		
        //Stuff parameters into array
        $parameters = array(
            'subAction' => $subAction,
            'data'      => array()
        );
        
        $defaultTweakable = DefaultTweakable::all();
        
        //Gather Data Common to all editor views
        $parameters['data'] = array(
            'action'                   => $action,
            'subAction'                => $subAction,
            'instance'                 => $instance,
            'instanceId'               => $instance->id,
            'instanceName'             => $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->orderBy('updated_at', 'ASC')->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray($defaultTweakable, 'parameter', 'value'),
            'tweakables_types'         => reindexArray($defaultTweakable, 'parameter', 'type'),
            'default_tweakables_names' => reindexArray($defaultTweakable, 'parameter', 'display_name'),
			'thisPage' 				   => $page,
			'h1style'                  => '',
        );
		$parameters['emailFavoritesData'] = Tweakable::where('parameter', "emailfavorite")->where('instance_id', $instance->id)->get();
        $parameters['emailAudienceData'] = Tweakable::where('parameter', "emailaudience")->where('instance_id', $instance->id)->get();
		//print_r($parameters['data']['tweakables']);
		
		$util = new Utilities();
		
		$parameters['data']['h1color'] = $util->getTweakableByParam("publication-h1-color", $parameters['data']['tweakables']);
		$parameters['data']['h1fontsize'] = $util->getTweakableByParam("publication-h1-font-size", $parameters['data']['tweakables']);	
		$parameters['data']['h1fontweight'] = $util->getTweakableByParam("publication-h1-font-weight", $parameters['data']['tweakables']);
		$parameters['data']['h1style'] = ' Style="color: ' . $parameters['data']['h1color'] . '; font-size: ' . $parameters['data']['h1fontsize'] . ';  font-weight: bold; "';
		//print( $parameters['data']['tweakables'][0]->id . "font-size");
		
		
        //Stuff session data into data parameter
        if (Session::has('cart')) {
            $cart = Session::get('cart');
            
            if (isset($cart[$instance->id])) {
                $parameters['data']['cart'] = $cart[$instance->id];
            }
        }
        
        //Stuff tweakables into data parameter
        if (isset($parameters['data']['tweakables']['global-accepts-submissions'])) {
            if ($parameters['data']['tweakables']['global-accepts-submissions']) {
                $parameters['data']['submission'] = true;
            } else {
                $parameters['data']['submission'] = false;
            }
        } else {
            if ($parameters['data']['default_tweakables']['global-accepts-submissions']) {
                $parameters['data']['submission'] = true;
            } else {
                $parameters['data']['submission'] = false;
            }
        }
		
        if($action == null){
            return $editorController->callAction('index', $parameters);
		} else {
			return $editorController->callAction($action, $parameters);
        }
    }
    
    
    ////////////////////////
    //  edit/{instanceName}
    ///////////////////////
    public function index($subAction, $data){
		
        //$instanceID = $instance->id;
        //Get most recent live publication
        $data['instance']->id;

        $publication = Publication::where('instance_id', $data['instance']->id)->livePublication()->first();
		
		//Populate $data
        $data['publication'] = $publication;
		$data['emailFavoritesData'] = Tweakable::where('parameter', "emailfavorite")->where('instance_id', $data['instance']->id)->get();
		$data['emailAudienceData'] = Tweakable::where('parameter', "emailaudience")->where('instance_id', $data['instance']->id)->get();
		$data['baseURL'] = url();

        return View::make('editor.editorDefault', $data);
    }

    ////////////////////////
    //  preview/{instanceName}{publicationid}
    ///////////////////////
    public function previewEmail($instanceName, $publication_id){
		$instance = Instance::where('name', $instanceName)->first();
        
        $data = array(
            'instance'		=> $instance,
            'instanceId'	=> $instance->id,
            'instanceName'	=> $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
            'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
            'isEditable'               => false,
            'shareIcons'               => false,
        );
		
		//Get This Publication
        $publication = Publication::where('id', $publication_id)->where('instance_id', $instance->id)->withArticles()->first();
		$data['publication'] = $publication;
		/*++++++++++++++++++EMAILED CONTENT PREVIEW+++++++++++++++++++++++++++++++++++++++++++*/
		$data['isEmail'] = true;
		 $data['h1style'] = "";
		$data['shareIcons'] = '';
		$data['isEditable'] = true;
		$html = View::make('emailPublication', $data)->render();
        $css = View::make('emailStyle', $data)->render();

        $inliner = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles();
        $inliner->setHTML($html);
        $inliner->setCSS($css);

      	$data['inlineHTML'] = $inliner->convert();
		/*++++++++++++++++++EMAILED CONTENT PREVIEW+++++++++++++++++++++++++++++++++++++++++++*/
		
		//print("<!-- Instance OF INLINE HTML -> -->" . $instanceName . $publication_id . "</body></html>  <!-- END OF Instance HTML -> -->");
			//$data['inlineHTML'] = $inlineHTML;
		
        return View::make('article.viewArticle', $data);
    }
	
	
	
	////////////////////////////////////////////////////
    //  downLoadExcel/{instanceName}/publicationLogs //
    ///////////////////////////////////////////////////
    public function downLoadExcel($action,$data){
        //$instanceID = $instance->id;
        //Get most recent live publication
		$convObj = new Utilities();
		$excelData = $convObj->obj_toArray($data['logResults']);
		
		$headers = array('eventname' => 'Log Reference',
						'type' => 'Event',
						'updated_at' => 'Published/Modified',
						'username' => 'Owner',
						'audience' => 'Audience',
						'Subject' => 'Subject',
						'Published' => 'Published',
						'PublicationURL' => 'Publication URL'
						);
		
		$excelData = $convObj->cleanupArrForExcel($excelData,$headers);
		
		Excel::create('Filename'.$convObj->generateRandomString(4), function($excel) use( $excelData) {

			$excel->sheet('Sheetname', function($sheet) use( $excelData) {
				$sheet->fromArray( $excelData);
			});

		})->export('xls'); /**/

		
		 Redirect::back()->withSuccess('Successfully Saved Settings');

}
	
	
    ////////////////////////
    //  show/{instanceName}/publicationLogs
    ///////////////////////
    public function publicationLogs($subAction, $data){
		$div = '<div class="well">';
		$divC = '</div>';
		
        $data['instance']->id;

        $publication = Publication::where('instance_id', $data['instance']->id)->livePublication()->first();

		//++++++++++++++++++++++INIT VALUES+++++++++++++++++++++++++++
		$fFromDate = "";
		$fToDate = "";
		//++++++++++++++++++++++INIT VALUES+++++++++++++++++++++++++++
		$data['fromdate'] = Input::get('fromdate');
		$data['todate'] = Input::get('todate');  
		$data['publishedonly'] = Input::get('publishedonly'); 
		$data['downloadit'] = Input::get('downloadit'); 
		$data['successMessage'] = NULL;
		
        $data['logResults'] = DB::table('publication_log')
            ->join('users', 'users.id', '=', 'publication_log.user_id')
            ->select('publication_log.id as id',
                'publication_log.event_id as event_id',
                'publication_log.eventname as eventname',
                'publication_log.type as type',
				'publication_log.description as description',
				'publication_log.updated_at as updated_at',
                'publication_log.created_at as created_at',
				'users.first as first',	 
				'users.last as last'               
            )->where('publication_log.type', $data['instance']->name);

		
		 if($data['fromdate'] != '' && $data['todate'] != ''){
			 		$fFromDate = date('Y-m-d',strtotime($data['fromdate'])) . " 00:10:00";
					$fToDate = date('Y-m-d',strtotime($data['todate'])) . " 00:10:00";
		            $data['logResults'] = $data['logResults']
                ->where('publication_log.updated_at','>',$fFromDate)->where('publication_log.updated_at','<',$fToDate);
			 $data['downloadit'] = 'true';
		 }
		
		if($data['publishedonly']==''){$data['publishedonly'] = 'N';}
		if($data['publishedonly'] == NULL){$data['publishedonly'] = 'Y';}
		if($data['publishedonly'] == "Y"){ 
			$data['logResults'] = $data['logResults']
                ->where('publication_log.description', 'like', '%<Published>Y</Published>%');
			$data['publishedonly'] = 'true';
		}
		
		$data['ffromdate']=$fFromDate;
		$data['ftodate']=$fToDate;
		$results = $data['logResults']->toSql();
		//$data['query'] = $results;
				
		$data['logResults'] = $data['logResults']->orderBy(
            		'created_at',
            		'desc'
				);
		if(Input::get('downloadit') == 'true'){
					$data['logResults'] = $data['logResults']->get();
		}else{
					$data['logResults'] = $data['logResults']->paginate(8);
		}		
		
		$xmlObj = new XmlConversions();
		$util = new Utilities();
		//+++++++++++++++++CONVERT XML TO ARRAY+++++++++++++++++++++
		foreach ($data['logResults'] as $key => $value){
			//XML to array conversion
			$arrXml = $xmlObj->to_array($data['logResults'][$key]->description);
			$arrXml = $xmlObj->to_array($data['logResults'][$key]->description);
			//Add back to array
			foreach ($arrXml as $xkey => $xvalue){
				$data['logResults'][$key]->$xkey = $xvalue;
			}
			$data['logResults'][$key]->username = $data['logResults'][$key]->first . " " .  $data['logResults'][$key]->last;
			unset($arrXml);
		}
		$data['logCount'] = count($data['logResults']);
		$data['publication'] = $publication;
		//++++++++++++++++++++++++++++++CONVERT TO GET REQUEST VARS+++++++++++++++++++++++++++++++
		$data['getRequestStr'] ='';
		$data['getRequestStr'] = 'fromdate='.$data['fromdate'].'&todate='.$data['todate'].'&publishedonly='.$data['publishedonly'].'&downloadit='.$data['downloadit'];
				$action = "download";
		//$this->callAction('downLoadExcel', $data);
		if(Input::get('downloadit') == 'true'){
			$this->downLoadExcel($action,$data);		
		}
		
        return View::make('editor.publicationLog')->with($data);
    }
	
	
    ////////////////////////////////////////////////
    //  edit/{instanceName}/articles/{subAction}
    ////////////////////////////////////////////////
    public function articles($subAction, $data){
        $data['articles'] = Article::where('instance_id', $data['instance']->id)->orderBy('created_at', 'desc')->paginate(
            10
        );

        if ($subAction != '') {
            $data['directArticle'] = Article::findOrFail($subAction);
        }

        //Check if this article will be loaded and can be shortcut (simplifies save process)
        $data['directIsLoaded'] = false;
        foreach ($data['articles'] as $article) {
            if ($article->id == $subAction) {
                $data['directIsLoaded'] = true;
            }
        }
        $data['subAction'] = 'articles';

		//$data['h1style'] = '';
        return View::make('editor.articleList', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/article/{subAction}
    ////////////////////////////////////////////////
    public function article($subAction, $data){
        $data['article'] = Article::find($subAction);
		$data['subAction'] = 'article';
	
		//print_r($data);
		//$data['h1style'] = '';
		return View::make('editor.articleEditor', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/submissions/{subAction}
    ////////////////////////////////////////////////
    public function submissions($subAction, $data){

        $data['submissions'] = Submission::where('instance_id', $data['instance']->id)->orderBy(
            'created_at',
            'desc'
        )->paginate(15);
        
        return View::make('editor.submissionEditor', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/publications/{subAction}
    ////////////////////////////////////////////////
    public function publications($subAction, $data){
        $data['publications'] = Publication::where('instance_id', $data['instance']->id)->orderBy('publish_date','desc')->paginate(15);

        //Get most recent live publication
        $data['currentLivePublication'] = Publication::where('instance_id', $data['instance']->id)->livePublication()->first();
		
        $calPubs = array();
        foreach (Publication::where('instance_id', $data['instance']->id)->get() as $publication) {
            $button = '';

            if (isset($data['currentLivePublication']) && $publication->id == $data['currentLivePublication']->id) {
                $button = '<a href="' . URL::to($data['instance']->name) . '" class="btn btn-xs btn-danger" >Live</a>';
            } elseif ($publication->published == 'Y') {
                $button = '<button class="btn btn-xs btn-success" disabled="disabled">Published</button>';
            } else {
                $button = '<button class="btn btn-xs btn-default" disabled="disabled">Unpublished</button>';
            }
            if (!array_key_exists($publication->publish_date . ' 10:00:00', $calPubs)) {
                $calPubs[$publication->publish_date . ' 10:00:00'] = array(
                    '<div class="btn-group"><a class="btn btn-default btn-xs" href="' . URL::to(
                        'edit/' . $data['instance']->name . '/publication/' . $publication->id
                    ) . '">' . ucfirst($publication->type) . '</a>' . $button . '</div>'
                );
            } else {
                $calPubs[$publication->publish_date . ' 10:00:00'][0] .= '<br/><div class="btn-group"><a class="btn btn-default btn-xs" href="' . URL::to(
                        'edit/' . $data['instance']->name . '/publication/' . $publication->id
                    ) . '">' . ucfirst($publication->type) . '</a>' . $button . '</div>';
            }
        }

        //Organize Calendar
        $cal = Calendar::make();
        $cal->setBasePath(URL::to('edit/' . $data['instance']->name . '/publications'));
        $cal->setDate(Input::get('cdate'));
        $cal->setView(Input::get('cv')); //'day' or 'week' or null
        $cal->setStartEndHours(8, 20); // Set the hour range for day and week view
        $cal->setTimeClass('ctime'); //Class Name for times column on day and week views
        $cal->setEventsWrap(array('<p>', '</p>')); // Set the event's content wrapper
        $cal->setDayWrap(
            array(
                '<div class="btn-group" style="padding-bottom:.25em;"><button class="btn btn-default btn-disabled" disabled="disabled">',
                '</button><button class="btn btn btn-success" style="padding-left:2px!important;padding-right:2px!important;" onclick="newPublicationFromCal(this)">&nbsp;+&nbsp;</button></div>'
            )
        ); //Set the day's number wrapper
        $cal->setNextIcon(
            '<button class="btn btn-default">&gt;&gt;</button>'
        ); //Can also be html: <i class='fa fa-chevron-right'></i>
        $cal->setPrevIcon('<button class="btn btn-default">&lt;&lt;</button>'); // Same as above
        $cal->setDayLabels(array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat')); //Label names for week days
        $cal->setMonthLabels(
            array(
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December'
            )
        ); //Month names
        $cal->setDateWrap(array('<div class="calendarDay">', '</div>')); //Set cell inner content wrapper
        $cal->setTableClass('table table-bordered calendarTable'); //Set the table's class name
        $cal->setHeadClass('table-header'); //Set top header's class name
        $cal->setNextClass(''); // Set next btn class name
        $cal->setPrevClass(''); // Set Prev btn class name
        $cal->setEvents($calPubs);
        $data['calendar'] = $cal->generate();
		
		

        return View::make('editor.publicationList', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/publication/{subAction}
    ////////////////////////////////////////////////
    public function publication($subAction, $data){
        $data['publication'] = Publication::where('id', $subAction)->
        where('instance_id', $data['instance']->id)->withArticles()->first();
		//print($data['publication']);
        //Package submissions
        $data['publication']->submissions = Article::where('instance_id', $data['instance']->id)->where(
            'issue_dates',
            'LIKE',
            '%' . $data['publication']->publish_date . '%'
        )->get();
		
		$data['emailFavoritesData'] = Tweakable::where('parameter', "emailfavorite")->where('instance_id', $data['publication']->instance_id)->get();
		$data['emailAudienceData'] = Tweakable::where('parameter', "emailaudience")->where('instance_id', $data['publication']->instance_id)->get();
		$data['baseURL'] = url();

        return View::make('editor.publicationEditor', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/newPublication/{subAction}
    ////////////////////////////////////////////////
    public function newPublication($subAction, $data){
        if (Input::has('publish_date')) {
            $data['publish_date'] = date('m/d/Y', strtotime(urldecode(Input::get('publish_date'))));
        }

        return View::make('editor.newPublicationEditor', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/images/{subAction}
    ////////////////////////////////////////////////
    public function images($subAction, $data){
        $data['images'] = Image::where('instance_id', $data['instance']->id)->orderBy('created_at', 'DESC')->paginate(8);
        //->get();

        return View::make('editor.imageEditor', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/settings/{subAction}
    ////////////////////////////////////////////////
    public function settings($subAction, $data){
        if(!Auth::user()->isAdmin($data['instanceId'])){
            return Redirect::to('edit/'.$data['instance']->name);
        }
        if($subAction == null){
            $data['subAction'] = 'appearanceTweakables';
        }

        $data['appearanceTweakables'] = array(
            'global-background-color',
            'publication-background-color',
            'publication-border-color',
            'publication-link-decoration',
            'publication-h1-color',
            'publication-h1-font',
            'publication-h1-font-size',
            'publication-h1-font-weight',
            'publication-h1-line-height',
            'publication-h2-color',
            'publication-h2-font',
            'publication-h2-font-size',
            'publication-h2-font-weight',
            'publication-h2-line-height',
            'publication-h3-color',
            'publication-h3-font',
            'publication-h3-font-size',
            'publication-h3-font-weight',
            'publication-h3-line-height',
            'publication-h4-color',
            'publication-h4-font',
            'publication-h4-font-size',
            'publication-h4-font-weight',
            'publication-h4-line-height',
            'publication-p-color',
            'publication-p-font',
            'publication-p-font-size',
            'publication-p-font-weight',
            'publication-p-line-height',
        );

        $data['contentStructureTweakables'] = array(
            'publication-type',
            'publication-show-titles',
            'publication-banner-image',
            'publication-width',
            'publication-padding',
            'publication-hr-articles',
            'publication-hr-titles',
            'publication-email-content-preview',
            'publication-content-preview-offset',
            'publication-headline-summary',
            'publication-headline-summary-position',
            'publication-headline-summary-width',
            'publication-repeat-separator-toggle',
        );

        $data['headerFooterTweakables'] = array(
            'publication-web-header',
            'publication-email-header',
            'publication-header',
            'publication-footer',
            'publication-email-footer',
            'publication-repeat-separator',
            'publication-headline-summary-header',
            'publication-headline-summary-footer',
            'publication-headline-summary-style',
            'publication-submission-splash'
        );

        $data['workflowTweakables'] = array(
            'publication-email-address',
            'publication-public-view',
            'publication-allow-merge',
            'global-accepts-submissions',
            'global-allow-raw-html',
            'publication-email-address'
        );

        //Grab settings profiles for this instance
                
        if($subAction == 'profiles'){
            $data['profiles'] = array();

            $profiles = Profile::where('instance_id', $data['instance']->id)->groupBy('name')->get();
            $moreToShow = Profile::where('instance_id', $data['instance']->id)->get();
            $data['emailFavoritesData'] = Tweakable::where('parameter', "emailfavorite")->where('instance_id', $data['instance']->id)->get();;
			$data['emailAudienceData'] = Tweakable::where('parameter', "emailaudience")->where('instance_id', $data['instance']->id)->get();

            foreach($profiles as $profile){
                $data['profiles'][$profile->name] = Profile::where('name', $profile->name)->get();
            }
        }

        $publication = Publication::where('instance_id', $data['instance']->id)->livePublication()->first();

        $data['publication'] = $publication;
        $data['isEditable'] = false;
        $data['isEmail'] = false;
        $data['shareIcons'] = false;
		$data['h1style'] = "";

        return View::make('editor.settingEditor', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/search/{subAction}
    ////////////////////////////////////////////////
    public function search($subAction, $data){
        //////////////////////////
        //  Editor Search Tool  //
        //////////////////////////

        //Search everything
        if ($subAction == 'everything') {

            //Get Articles
            $data['articleResults'] = Article::where('instance_id', $data['instance']->id)
                ->where(
                    function ($query) {
                        $query->Where('title', 'LIKE', '%' . Input::get('search') . '%')
                            ->orWhere('content', 'LIKE', '%' . Input::get('search') . '%');
                    }
                )->orderBy('created_at', 'DESC')->paginate(15);

            //Get Images
            $data['imageResults'] = Image::where('instance_id', $data['instance']->id)
                ->where(function($query){
                    $query->Where('title', 'LIKE', '%' . Input::get('search') . '%')
                        ->orWhere('filename', 'LIKE', '%' . Input::get('search') . '%');
                })->orderBy('created_at', 'DESC')->paginate(8);

            //Get Publications
            $data['publicationResults'] = DB::table('publication')
                ->select('publication.id',
                    'publication.published',
                    'publication.publish_date',
                    'publication.created_at',
                    'publication.updated_at',
                    'article.id as article_id',
                    'article.title')
                ->join('publication_order', 'publication.id', '=', 'publication_order.publication_id')
                ->join('article', 'publication_order.article_id', '=', 'article.id')
                ->where('publication.instance_id', $data['instance']->id)
                ->where(function ($query) {
                        $query->Where('article.title', 'LIKE', '%' . Input::get('search') . '%')
                            ->orWhere('article.content', 'LIKE', '%' . Input::get('search') . '%');
                    })->groupBy('publication.id')
                ->orderBy('publication.publish_date', 'DESC')
                ->paginate(15);

        //Search Articles
        } elseif ($subAction == 'articles') {
            //Get Articles
            $data['articleResults'] = Article::where('instance_id', $data['instance']->id)
                ->where(
                    function ($query) {
                        $query->Where('title', 'LIKE', '%' . Input::get('search') . '%')
                            ->orWhere('content', 'LIKE', '%' . Input::get('search') . '%');
                    }
                )->orderBy('created_at', 'DESC')->paginate(15);
            //Search Publications
        } elseif ($subAction == 'publications') {
            //Get Publications where Articles Appear
            $data['publicationResults'] = DB::table('publication')
                ->select('publication.id',
                    'publication.published',
                    'publication.publish_date',
                    'publication.created_at',
                    'publication.updated_at',
                    'article.id as article_id',
                    'article.title')
                ->join('publication_order', 'publication.id', '=', 'publication_order.publication_id')
                ->join('article', 'publication_order.article_id', '=', 'article.id')
                ->where('publication.instance_id', $data['instance']->id)
                ->where(function ($query) {
                        $query->Where('article.title', 'LIKE', '%' . Input::get('search') . '%')
                            ->orWhere('article.content', 'LIKE', '%' . Input::get('search') . '%');
                    })->groupBy('publication.id')
                ->orderBy('publication.publish_date', 'DESC')
                ->paginate(15);

        }elseif ($subAction == 'images'){
            $data['imageResults'] = Image::where('instance_id', $data['instance']->id)
                ->where(function($query){
                        $query->Where('title', 'LIKE', '%' . Input::get('search') . '%')
                            ->orWhere('filename', 'LIKE', '%' . Input::get('search') . '%');
                    })->orderBy('updated_at')->get();
        }
        $data['searchVal'] = Input::get('search');
        return View::make('editor.searchResults', $data);
    }

    ////////////////////////////////////////////////
    //  edit/{instanceName}/help/{subAction}
    ////////////////////////////////////////////////
    public function help($subAction, $data){
        return View::make('editor.help', $data);
    }
    ////////////////////////////////////////////////
    //  edit/{instanceName}/emailFavorites/{subAction}
    ////////////////////////////////////////////////
    public function SaveEmailFavorites($instanceName,$action){
        $instance = Instance::where('name', strtolower(urldecode($instanceName)))->firstOrFail();
        $instanceID = $instance->id;
        $default_tweakables = reindexArray(DefaultTweakable::all(), 'parameter', 'value');
		$action = 'SaveEmailFavorites';
        //+++++++++++++++++FIRST LET'S VALIDATE FORMATED EMAIL   
        $validator = Validator::make(
            array('emailfavorite' => Input::get('emailfavorite')),
            array('emailfavorite' => 'required|email')
        );        
        if ($validator->fails())
        {
            
            $messages = $validator->messages();
            $emailMsg = $messages->first('emailfavorite', 'Incorrect email format.  Try using example@server.com');
            return Redirect::back()->withError($emailMsg);
            // The given data did not pass validation
        }
        //+++++++++++++++++FIRST LET'S VALIDATE FORMATED EMAIL   
       if ($action == 'SaveEmailFavorites') {
            //Admin's only!
            if(!Auth::user()->isAdmin($instanceID)){
                return Redirect::to('edit/'.$data['instance']->name);
            }

            foreach (Input::except('_token') as $parameter => $value) {
                //Check to see if this is a default value, if so don't duplicate things
                if ($default_tweakables['publication-' . $parameter] == $value) {
                    $tweakables = Tweakable::where('parameter', $parameter)->where('instance_id', $instanceID)->get();
                    foreach ($tweakables as $tweakable) {
                        $tweakable->delete();
                    }
                } else {
                    //$createdAt = date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s');
                    //$updatedAt = date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s');
                    $tweakable = Tweakable::firstOrCreate(
                        array('parameter' => $parameter, 'instance_id' => $instanceID, 'value' => '')
                        );
                    
                    $tweakable->instance_id = $instanceID;
                    $tweakable->parameter = $parameter;
                    
                    if ($parameter) {
                        $tweakable->value = stripslashes($value);
                    }
                    //$tweakable->created_at = $createdAt;
                    //$tweakable->updated_at = $updatedAt;
                    $tweakable->save();
                }
            }
            return Redirect::back()->withSuccess('Successfully Saved Settings');
        }
        
        return Redirect::back()->withSuccess( $action);
    }
    
    public function deleteEmailFav($instanceName, $emailFavID){
        $instance = Instance::where('name', strtolower(urldecode($instanceName)))->firstOrFail();
        $instanceID = $instance->id;
        
        //Admin's only!
        if(!Auth::user()->isAdmin($instanceID)){
            return Redirect::to('edit/'.$instanceName);
        }
        //Input::get('emailFavID')
        //Profile::where('name', $profileName)->where('instance_id', $instanceID)->delete();
        Tweakable::where('id', $emailFavID)->delete();
        return Redirect::back()->withSuccess('Successfully deleted Audience. ');
    }
       
    public function deleteEmailAudience($instanceName, $emailAudID){
        $instance = Instance::where('name', strtolower(urldecode($instanceName)))->firstOrFail();
        $instanceID = $instance->id;
        
        //Admin's only!
        if(!Auth::user()->isAdmin($instanceID)){
            return Redirect::to('edit/'.$instanceName);
        }
        //Input::get('emailFavID')
        //Profile::where('name', $profileName)->where('instance_id', $instanceID)->delete();
        Tweakable::where('id', $emailAudID)->delete();
        return Redirect::back()->withSuccess('Successfully deleted email favorite. ');
    }
     
    ////////////////////////////////////////////////
    //  edit/{instanceName}/emailFavorites/{subAction}
    ////////////////////////////////////////////////
    public function SaveEmailAudiences($instanceName,$action,$subAction){
        $instance = Instance::where('name', strtolower(urldecode($instanceName)))->firstOrFail();
        $instanceID = $instance->id;
        $default_tweakables = reindexArray(DefaultTweakable::all(), 'parameter', 'value');
        //+++++++++++++++++FIRST LET'S VALIDATE FORMATED EMAIL   
        $validator = Validator::make(
            array('emailaudience' => Input::get('emailaudience')),
            array('emailaudience' => 'required')
        );        
        if ($validator->fails())
        {
            
            $messages = $validator->messages();
            $emailMsg = $messages->first('emailaudience', 'Response required.');
            return Redirect::back()->withError($emailMsg);
            // The given data did not pass validation
        }
        //+++++++++++++++++FIRST LET'S VALIDATE FORMATED EMAIL   
       if ($action == 'SaveEmailAudience') {
            //Admin's only!
            if(!Auth::user()->isAdmin($instanceID)){
                return Redirect::to('edit/'.$data['instance']->name);
            }

            foreach (Input::except('_token') as $parameter => $value) {
                //Check to see if this is a default value, if so don't duplicate things
				//if(array_key_exists('publication-' . $parameter, $default_tweakables)){
					if ($default_tweakables['publication-' . $parameter] == $value) {
						$tweakables = Tweakable::where('parameter', $parameter)->where('instance_id', $instanceID)->get();
						foreach ($tweakables as $tweakable) {
							$tweakable->delete();
						}
					} else {
						//$createdAt = date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s');
						//$updatedAt = date_format($faker->dateTimeThisYear(), 'Y-m-d H:i:s');
						$tweakable = Tweakable::firstOrCreate(
							array('parameter' => $parameter, 'instance_id' => $instanceID, 'value' => '')
							);

						$tweakable->instance_id = $instanceID;
						$tweakable->parameter = $parameter;

						if ($parameter) {
							$tweakable->value = stripslashes($value);
						}
						//$tweakable->created_at = $createdAt;
						//$tweakable->updated_at = $updatedAt;
						$tweakable->save();
					}
				//}
            }
            return Redirect::back()->withSuccess('Successfully Saved Settings');
        }
        
        return Redirect::back()->withSuccess( $action);
    }    
    public function save($instanceName, $action)
    {
        $instance = Instance::where('name', strtolower(urldecode($instanceName)))->firstOrFail();
        $instanceID = $instance->id;

        $default_tweakables = reindexArray(DefaultTweakable::all(), 'parameter', 'value');

        if ($action == 'settings') {
            //Admin's only!
            if(!Auth::user()->isAdmin($instanceID)){
                return Redirect::to('edit/'.$data['instance']->name);
            }
            foreach (Input::except('_token') as $parameter => $value) {
                //Check to see if this is a default value, if so don't duplicate things
					$tweakables = array();
				$tweakables = Tweakable::where('parameter', $parameter)->where('instance_id', $instanceID)->first();
				if(!isset($tweakables->value)){$tweakables = (object) array('value' => '');}
				print("previous value: " . $tweakables->value );
                if ($default_tweakables[$parameter] == $value) {
					unset($tweakables);
                    $tweakables = Tweakable::where('parameter', $parameter)->where('instance_id', $instanceID)->get();
					//print( $tweakables);
                    foreach ($tweakables as $tweakable) {
                        $tweakable->delete();
                    }
                } else {
                    $tweakable = Tweakable::firstOrCreate(
                        array('parameter' => $parameter, 'instance_id' => $instanceID, 'value' => $tweakables->value)
                    );
                    $tweakable->instance_id = $instanceID;
                    $tweakable->parameter = $parameter;
                    if ($parameter) {
                        $tweakable->value = stripslashes($value);
                    }
					
					//$tweakable->created_at = $createdAt;
					//$tweakable->updated_at = $updatedAt;
					/*iconv("UTF-8", "ISO-8859-1//TRANSLIT", stripslashes($value));*/
					
                   $tweakable->save();
                }
            }
            return Redirect::back()->withSuccess('Successfully Saved Settings');
        }elseif($action == 'profiles'){
            //Admin's only!
            if(!Auth::user()->isAdmin($instanceID)){
                return Redirect::to('edit/'.$data['instance']->name);
            }

            if(Input::has('profileName')){
                if(Profile::where('instance_id', $instanceID)->where('name', Input::get('profileName'))->count() > 0){
                    return Redirect::back()->withError('A profile with that name already exists!  Delete the existing profile or pick a unique name.');
                }
                foreach(Tweakable::where('instance_id', $instanceID)->get() as $tweakable){
                    $profile = new Profile;
                    $profile->name = Input::get('profileName');
                    $profile->instance_id = $instanceID;
                    $profile->tweakable = $tweakable->parameter;
                    $profile->value = $tweakable->value;
                    $profile->save();
                }
            }else{
                return Redirect::back()->withError('No Profile Name!');
            }

            return Redirect::back()->withSuccess('Successfully saved profile ' . Input::get('profileName'));
        }
    }

    public function deleteProfile($instanceName, $profileName){
        $instance = Instance::where('name', strtolower(urldecode($instanceName)))->firstOrFail();
        $instanceID = $instance->id;

        //Admin's only!
        if(!Auth::user()->isAdmin($instanceID)){
            return Redirect::to('edit/'.$instanceName);
        }

        Profile::where('name', $profileName)->where('instance_id', $instanceID)->delete();
        return Redirect::back()->withSuccess('Successfully deleted profile ' . Input::get('profileName'));
    }

    public function loadProfile($instanceName, $profileName){
        $instance = Instance::where('name', strtolower(urldecode($instanceName)))->firstOrFail();
        $instanceID = $instance->id;

        //Admin's only!
        if(!Auth::user()->isAdmin($instanceID)){
            return Redirect::to('edit/'.$instanceName);
        }

        $profile = Profile::where('name', $profileName)->where('instance_id', $instanceID)->get();

        //Kill old settings
        Tweakable::where('instance_id', $instanceID)->delete();
        foreach($profile as $setting){
            //Load in new settings
            $tweakable = new Tweakable;
            $tweakable->instance_id = $instanceID;
            $tweakable->parameter = $setting->tweakable;
            $tweakable->value = $setting->value;
            $tweakable->save();
        }

        return Redirect::back()->withSuccess('Successfully loaded profile ' . Input::get('profileName'));
    }
}