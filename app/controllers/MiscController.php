<?php

class MiscController extends BaseController {

    public function showLogs($instanceName, $fileName){
        echo '/web_content/share/mailAllSource/public/logs/'.$instanceName.'/'.$fileName;die;
        return file_get_contents('/web_content/share/mailAllSource/public/logs/'.$instanceName.'/'.$fileName);
    }

    public function imageJSON($instanceName){
        $instance = Instance::where('name',strtolower(urldecode($instanceName)))->first();
        //Grab all the images for that instance and send them to the user
        $images = array();
        foreach(Image::where('instance_id',$instance->id)->orderBy('created_at', 'desc')->get() as $image){
            $imageLocation = str_replace('https','http', URL::to('images/'.preg_replace('/[^\w]+/', '_', $instance->name).'/'.$image->filename));
            array_push($images,array(
                'image'  => $imageLocation,
            ));
        }
        return Response::json($images);
    }
	public function imagePicker($instanceName){
        $instance = Instance::where('name',strtolower(urldecode($instanceName)))->first();
        //Grab all the images for that instance and send them to the user
        $images = array();
        /*foreach(Image::where('instance_id',$instance->id)->orderBy('created_at', 'desc')->paginate(15) as $image){
            $imageLocation = str_replace('https','http', URL::to('images/'.preg_replace('/[^\w]+/', '_', $instance->name).'/'.$image->filename));
            array_push($images,array(
                'image'  => $imageLocation,
				'id' => '25'
            ));
			$images[] = array(
                'image'  => $imageLocation,
				'id' => $image->id,
				'filename' => $image->filename
            );
        }*/
		$data = array();
		$data['images'] = Image::where('instance_id',$instance->id)->orderBy('created_at', 'desc')->paginate(15);
		$data['count'] = count($data['images']);
		$data['instance'] = $instance;
		$data['instanceId'] = $instance->id;
		$data['instanceName'] = $instance->name;
		$data['action'] = "";
		$data['default_tweakables'] = reindexArray(DefaultTweakable::all(), 'parameter', 'value');
		
        return View::make('editor.imagePicker', $data);
    }

    public function cartAdd($instanceName){
        $instance = Instance::where('name',strtolower(urldecode($instanceName)))->first();
        $article_id = Input::get('article_id');

        if(Session::has('cart')){
            $cart = Session::get('cart');

            if(isset($cart[$instance->id])){
                if(isset($cart[$instance->id][$article_id])){
                    return Response::json(array(
                        'error'  => 'Article already in cart',
                        'cart'   => $cart[$instance->id]
                    ));
                }else{
                    $cart[$instance->id][$article_id] = Article::findOrFail($article_id)->title;
                    Session::put('cart', $cart);
                    return Response::json(array(
                        'success'   => 'Article added to cart',
                        'cart'      => $cart[$instance->id]
                    ));
                }
            }else{
                $cart[$instance->id][$article_id] = Article::findOrFail($article_id)->title;
                Session::put('cart', $cart);
                return Response::json(array(
                    'success'   => 'Article added to cart',
                    'cart'      => $cart[$instance->id]
                ));
            }
        }else{
            $cart = array();
            $cart[$instance->id][$article_id] = Article::findOrFail($article_id)->title;
            Session::put('cart', $cart);
            return Response::json(array(
                'success'   => 'Article added to cart',
                'cart'      => $cart[$instance->id]
            ));
        }
    }
	
	public function cartAddIssued(){
		$instanceName = urldecode(Request::segment(2));
        $publicationId = Input::get('publication_id');
		$instance = Instance::where('name',strtolower(urldecode($instanceName)))->first();
        $publication = Publication::where('id',$publicationId)->first();
		$publish_date = $publication->publish_date;
		$start_date = date("Y-m-d", strtotime("-1 year", time()));
		$raw = 'Date(created_at) > date("'.$start_date.'")';
		$articles = Article::where('instance_id', $instance->id);
		$articles = $articles->whereRaw($raw);
		$count = $articles->count();
		$articles = $articles->get()->toArray();
		$match = "";
		$articleCount=0;
		$fail=0;
		$cart = "";
		foreach($articles as $article) {
			$article_dates = str_replace(',',', ',str_replace(']','',str_replace('[','',str_replace('"','', stripslashes($article['issue_dates'])))));
			$date_array = explode(",",$article_dates);
			foreach($date_array as $date) {
				if(trim($date)==$publish_date) {
					$match = $match.$article['id']." ";
					 if(Session::has('cart')){
						$cart = Session::get('cart');
			
						if(isset($cart[$instance->id])){
							if(isset($cart[$instance->id][$article['id']])){
								$fail++;
								$articleCount++;
							}else{
								$cart[$instance->id][$article['id']] = Article::findOrFail($article['id'])->title;
								Session::put('cart', $cart);
								$articleCount++;
							}
						}else{
							$cart[$instance->id][$article['id']] = Article::findOrFail($article['id'])->title;
							Session::put('cart', $cart);
							$articleCount++;
						}
					}else{
						$cart = array();
						$cart[$instance->id][$article['id']] = Article::findOrFail($article['id'])->title;
						Session::put('cart', $cart);
						$articleCount++;
					}
					
				}
			}
		}
		$match = trim($match);
		
		/*return Response::json(array(
                        'Publish Date'  => $publish_date,
						'Articles Match' => $match,
						'Count' => $count
                    ));*/
					if($articleCount==0) {
						return Response::json(array(
										'error'   => 'No articles to add'
									));
					} else {
						if($fail==$articleCount && $articleCount>0){
							return Response::json(array(
										'error'   => 'Articles already in cart',
										'cart'      => $cart[$instance->id]
									));
						} else {
							return Response::json(array(
										'success'   => 'Articles added to cart',
										'cart'      => $cart[$instance->id]
									));
						}
					}
    }

    public function cartRemove($instanceName){
        $instance = Instance::where('name',strtolower(urldecode($instanceName)))->first();
        $article_id = Input::get('article_id');

        if(Session::has('cart')){
            $cart = Session::get('cart');

            if(isset($cart[$instance->id])){
                if(isset($cart[$instance->id][$article_id])){
                    unset($cart[$instance->id][$article_id]);
                    Session::put('cart', $cart);
                    return Response::json(array(
                        'success'  => 'Article removed from cart',
                        'cart'   => $cart[$instance->id]
                    ));
                }else{
                    return Response::json(array(
                        'error'   => 'Article not in cart',
                        'cart'      => $cart[$instance->id]
                    ));
                }
            }else{
                return Response::json(array(
                    'error'   => 'Article not in cart.',
                    'cart'      => array()
                ));
            }
        }else{
            return Response::json(array(
                'error'   => 'Article not in cart ',
                'cart'      => array()
            ));
        }
    }

    public function cartClear($instanceName){
        $instance = Instance::where('name',strtolower(urldecode($instanceName)))->first();

        if(Session::has('cart')){
            $cart = Session::get('cart');

            if(isset($cart[$instance->id])){
                unset($cart[$instance->id]);
                Session::put('cart',$cart);
                return Response::json(array(
                    'success'   => 'Cart cleared'
                ));
            }else{
                return Response::json(array(
                    'error' => 'Cart already empty'
                ));
            }
        }
    }

    public function articleAJAX($article_id, $publication_id = ''){
        $article = Article::findOrFail($article_id);
        //Grab instance ID from article

        $instanceId = $article->instance_id;

        $instance = Instance::findOrFail($instanceId);

        $data = array(
            'instance'                 => $instance,
            'instanceId'               => $instance->id,
            'instanceName'             => $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'article'                  => $article,
            'isRepeat'                 => $article->isPublished($publication_id) ? true : false,
            'hideRepeat'               => $article->isPublished($publication_id) ? true : false,
            'isEmail'                  => false,
            'isEditable'               => true,
            'shareIcons'               => false,
			'h1style'               => '',
        );
		//+++++++++++++++++GET STYLING FOR H1 TAG++++++++++++++++++++++++++++++++++++++
		$util = new Utilities();
				
		$data['h1color'] = $util->getTweakableByParam("publication-h1-color", $data['tweakables']);
		$data['h1fontsize'] = $util->getTweakableByParam("publication-h1-font-size", $data['tweakables']);	
		$data['h1fontweight'] = $util->getTweakableByParam("publication-h1-font-weight", $data['tweakables']);
		$data['h1font'] = $util->getTweakableByParam("publication-h1-font", $data['tweakables']);
		$data['h1style'] = ' Style="color: ' . $data['h1color'] . '; font-size: ' . $data['h1fontsize'] . ';  font-weight: '  . $data['h1fontweight'] .  '; font-family:'. str_replace('"', '', $data['h1font']) . '!important "';
		//+++++++++++++++++GET STYLING FOR H1 TAG++++++++++++++++++++++++++++++++++++++

        if($publication_id != ''){
            $data['publication'] = Publication::where('id', $publication_id)->first();
        }
		
        return View::make('article.article', $data);
    }
	public function uploadDataMigration($instanceName){
		$data = array();
		 //Get most recent live publication
		$instance = Instance::where('name', strtolower(urldecode($instanceName)))->firstOrFail();
		$data = array(
			'instance'                 => $instance,
			'instanceId'               => $instance->id,
			'instanceName'             => $instance->name,
			'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
			'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
			'isEmail'                  => false,
			'isEditable'               => true,
			'shareIcons'               => false,
			'action'				   => "",
		);
		if(!Input::hasFile('xlsfile')){return Redirect::back()->withError('Did you select a file to upload?  We never received one, try again.');}

		
		
		//return $editorController->callAction('preprocessReportMigration', $data);
		//'/preprocess/reportMigration/'.$instance->name
		//$extension = Input::file('xlsfile')->getClientOriginalExtension();
		$file = Input::file('xlsfile');
		$destinationPath = 'uploads';
		// If the uploads fail due to file system, you can try doing public_path().'/uploads' 
		$fileKey = str_random(5);
		$data['fileName'] = $file->getClientOriginalName();
		$extension =$file->getClientOriginalExtension(); 
		//
		//. "?filename=".$data['fileName']
		$extensionArr = array('xlsx','xls','csv');
		if(!in_array($extension, $extensionArr)){return Redirect::back()->withError('Sorry you can upload csv and xls files only, try again.');}
		$upload_success = Input::file('xlsfile')->move($destinationPath, $fileKey.$data['fileName']);
		return Redirect::to('/preprocess/reportMigration/'.$instance->name)->with('filename', $fileKey.$data['fileName'])->with('filetype', $extension );
		//$data['view'] = "preprocess";
		//return View::make('editor.reportMigration', $data);		
	}
	public function getDataMigration($instanceName){
		
		$data = array();
		        //Get most recent live publication
		$instance = Instance::where('name', strtolower(urldecode($instanceName)))->firstOrFail();
			$data = array(
				'instance'                 => $instance,
				'instanceId'               => $instance->id,
				'instanceName'             => $instance->name,
				'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
				'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
				'isEmail'                  => false,
				'isEditable'               => true,
				'shareIcons'               => false,
				'action'				   => "",
			);		
		
        $data['view'] = "viewData";
		return View::make('editor.reportMigration', $data);		
	}
	public function preprocessReportMigration($instanceName){
		//print(Session::get('filename'). " <-file name filetype->".Session::get('filetype'));
		$data = array();
		        //Get most recent live publication
		$instance = Instance::where('name', strtolower(urldecode($instanceName)))->firstOrFail();
			$data = array(
				'instance'                 => $instance,
				'instanceId'               => $instance->id,
				'instanceName'             => $instance->name,
				'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
				'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
				'isEmail'                  => false,
				'isEditable'               => true,
				'shareIcons'               => false,
				'action'				   => "",
			);		
		$data['fileName'] = Session::get('filename');
			//$spreadsheet = $reader->load("/uploads/FilenamezTRt.xls");
		$xldata = array();
		$i = 0;
		define('ROOTPATH', dirname(__FILE__));
		//print(dirname(__FILE__));//FilenamezTRt.xls
		$objXLS = new ExcelGet;
		$fileName = base_path('public/uploads/'.$data['fileName']);
		$columnNames = "Log Reference,"."Event,"."Published/Modified,"."Owner,"."Audience,"."Subject,"."Published,"."Publication,"."URL";
		$columnNames = explode(",",$columnNames);
		
		//print_r($columnNames);
		
		$xldata = $objXLS->asArray($fileName);	
		array_unshift($columnNames, $xldata);	
		//$xldata = $columnNames + $xldata;
		
		$data['xldata']	= $xldata;
		
		
        $data['view'] = "preprocess";
		return View::make('editor.reportMigration', $data);		
	}
	
	
	
	public function saveReportMigration($instanceName){
		$data = array();
		        //Get most recent live publication
		$instance = Instance::where('name', strtolower(urldecode($instanceName)))->firstOrFail();
			$data = array(
				'instance'                 => $instance,
				'instanceId'               => $instance->id,
				'instanceName'             => $instance->name,
				'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
				'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
				'isEmail'                  => false,
				'isEditable'               => true,
				'shareIcons'               => false,
				'action'				   => "",
			);		
		$xldata = array();
		$i = 0;
		define('ROOTPATH', dirname(__FILE__));
		$objXLS = new ExcelGet;
		$fileName = base_path('uploads/FilenamezTRt.xls');
		$xldata = $objXLS->asArray($fileName);
		$pubHeadingsArr = "instance_id,"."publish_date,"."banner_image,"."published,"."type,"."created_at";
		$pubData = array();
		$pubData[0] = explode(",", $pubHeadingsArr);
		
		$pubLogHeadingsArr = "event_id,"."user_id,"."instance,"."logtype,"."description";
		$pubLogData[0] = explode(",", $pubLogHeadingsArr);
		
		$pub = new Publication;
		$pubCountObj = new Publication;
		$xmlObj = new XmlConversions();
		$pubLog = new PublicationLog;
		$rowIndex = 0;
		foreach($xldata as $key => $row){
			//if($rowIndex > 0){
				$dataKey = $key + 1;
				$instanceEventName = $row['event'];
				$instance = Instance::where('name', $instanceEventName)->first();
				
				$count = $pubCountObj::where('instance_id', $instance->id)->where('publish_date', $row['publishedmodified'])->count();
				print("count: " . $count);

				$pub->instance_id = $instance->id;
				$pub->publish_date = $row['publishedmodified'];
				$pub->banner_image = "";
				$pub->published = $row['published'];
				$pub->type = "regular";
				$pub->created_at = date("Y-m-d", strtotime("-1 year", time()));

				//$fdData[$key][''] = ;	
				$pubData[$dataKey]['instance_id'] = $pub->instance_id;
				$pubData[$dataKey]['publish_date'] = $pub->publish_date;
				$pubData[$dataKey]['banner_image'] = $pub->banner_image;
				$pubData[$dataKey]['published'] = $pub->published;
				$pubData[$dataKey]['type'] = $pub->type;
				$pubData[$dataKey]['created_at'] = $pub->created_at;
				
				//+++++++PUBLICATION LOG UPDATES++++++++++++++++++++++
				$dataXML = array(
							'audience' => $row['audience'],
							'Subject' => $row['subject'],
							'Published' => $row['published'],
							'PublicationURL' => $row['publication_url']		
				);
				//CONVERT TO XML
				
				$rootItem = new SimpleXMLElement('<content/>');
				$xmlText =  $xmlObj->from_array($dataXML,$rootItem);
				$userId = Auth::id();
				//++++++++++NOW LOG TO DB ++++++++++++++++++++++++++++++++
				
				$pubLog->event_id = $pub->instance_id;
				$pubLog->user_id = $userId;
				$pubLog->eventname = $row['event'];
				$pubLog->type = $row['log_reference'];
				$pubLog->description = $xmlText ;
				
				$pubLog->event_id = $pub->instance_id;
				$pubLog->user_id = $userId;
				$pubLog->eventname = $row['event'];
				$pubLog->type = $row['log_reference'];
				$pubLog->description = $xmlText ;

				$pubLogData[$dataKey]['event_id'] = $pubLog->event_id;	
				$pubLogData[$dataKey]['user_id'] = $pubLog->user_id;	
				$pubLogData[$dataKey]['eventname'] = $pubLog->eventname ;	
				$pubLogData[$dataKey]['type'] = $pubLog->type;	
				$pubLogData[$dataKey]['description'] = $pubLog->description;
			//}//
			//$rowIndex++;
		}

		// add more fields (all fields that users table contains without id)
		//$pubLog->save();
			//Get This Publication
//`id`, `instance_id`, `publish_date`, `banner_image`, `published`, `type`, `created_at`, `updated_at`		
		
/*
0log_reference	1event	2publishedmodified	3owner	4audience	5subject	6published	7publication_url
$dataXML = array(
			'audience' => implode("," , Input::get('myAudience')),
			'Subject' => Input::get('subject'),
			'Published' => $logData['isPublished'],
			'PublicationURL' => 'https://share.uakron.edu/mailAll/'.$instanceName		
		);
		//CONVERT TO XML
		$xmlObj = new XmlConversions();
		$rootItem = new SimpleXMLElement('<content/>');
 		$xmlText =  $xmlObj->from_array($dataXML,$rootItem);














*/
		
		
		
		$data['pubData'] = $pubData;
		$data['pubLogData'] = $pubLogData;
        $data['view'] = "save";
		return View::make('editor.reportMigration', $data);			
	}
	
}