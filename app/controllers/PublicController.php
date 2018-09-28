<?php

class PublicController extends \BaseController {

    public function __construct(){
    }

    public function showPublicHome($instanceName){
        //Fetch Instance out of DB
        $instance = Instance::where('name',strtolower(urldecode($instanceName)))->firstOrFail();
        $data = array(
            'instance'		=> $instance,
            'instanceId'	=> $instance->id,
            'instanceName'	=> $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
            'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
            'isPublication'            => true
        );

        if(isset($data['tweakables']['publication-public-view']) && !$data['tweakables']['publication-public-view']){
            return Redirect::to('/')->withError('This publication does not have a public archive.');
        }else if(!$data['default_tweakables']['publication-public-view']){
            return Redirect::to('/')->withError('This publication does not have a public archive.');
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

        if(Publication::where('instance_id',$instance->id)->where('published','Y')->count() > 0) {
            //Get most recent live publication
            $data['publication'] = Publication::where('instance_id', $instance->id)->livePublication()->first();
        }else{
            $data['publication'] = null;
        }

        return View::make('public.publication')->with($data);
    }

    public function showArticle($instanceName, $article_id){
        //Fetch Instance out of DB
        $instance = Instance::where('name',strtolower(urldecode($instanceName)))->firstOrFail();

        $data = array(
            'instance'		=> $instance,
            'instanceId'	=> $instance->id,
            'instanceName'	=> $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
            'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
            'isArticle'                => true
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

        //Get this publication
        $article = Article::where('instance_id',$instance->id)->where('id',$article_id)->firstOrFail();

        //Populate $data
        $data['article'] = $article;

        return View::make('public.article')->with($data);
    }

    public function showPublication($instanceName, $publication_id){
        //Fetch Instance out of DB
        $instance = Instance::where('name',strtolower(urldecode($instanceName)))->firstOrFail();

        //Get this publication
        $publication = Publication::where('id', $publication_id)->published()->withArticles()->first();

        if(count($publication) > 0){

            $data = array(
                'instance'		=> $instance,
                'instanceId'	=> $instance->id,
                'instanceName'	=> $instance->name,
                'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
                'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
                'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
                'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name')
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

            //Populate $data
            $data['publication'] = $publication;
        }

        return View::make('public.publication')->with($data);
    }

    public function search($instanceName){
        $instance = Instance::where('name',strtolower(urldecode($instanceName)))->firstOrFail();
        $data = array(
            'instance'		=> $instance,
            'instanceId'	=> $instance->id,
            'instanceName'	=> $instance->name,
            'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
            'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
            'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
            'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
            'searchValue'              => Input::get('search'),
            'year'                     => Input::has('year') ? Input::get('year') : '--',
            'month'                    => Input::has('month') ? Input::get('month') : '--',
            'querySummary'             => ''
        );

        //Setup dropdowns for searching
        $data['years'] = array('--' => '--');

        foreach($data['years'] as $index => $value){
            $index = $value;
        }

        foreach(range(date('Y'), date('Y')-5) as $year){
            $data['years'][$year] = $year;
        }

        $data['months'] = array(
            '--' => '--',
            '1' => 'January',
            '2' => 'February',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
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

        $data['articleResults'] = DB::table('article')
            ->join('publication_order', 'article.id', '=', 'publication_order.article_id')
            ->join('publication', 'publication_order.publication_id', '=', 'publication.id')
            ->select('article.id as article_id',
                'article.title as title',
                'article.updated_at as updated_at',
                'article.created_at as created_at',
                'publication.id as publication_id',
                'publication.published',
                'publication.publish_date'
            )->where('publication.instance_id', $instance->id);

        if($data['searchValue'] != ''){
            $data['articleResults'] = $data['articleResults']->where(function ($query) {
                $query->Where('article.title', 'LIKE', '%' . Input::get('search') . '%')
                    ->orWhere('article.content', 'LIKE', '%' . Input::get('search') . '%');
            });
            $data['querySummary'] .= ' where the article contains <strong>' . $data['searchValue'] . '</strong> ';
        }

        if($data['year'] != '--' && $data['month'] == '--'){
            $data['articleResults'] = $data['articleResults']
                ->where('publication.publish_date','LIKE','%'.$data['year'].'%');
            $data['querySummary'] .= ' published in <strong>' . $data['year'] . '</strong>.';
        }elseif($data['year'] != '--' && $data['month'] != '--'){
            $data['articleResults'] = $data['articleResults']
                ->where('publication.publish_date','LIKE','%'.$data['year'].'-'.date('m',strtotime('2014-'.$data['month'].'-01')).'%');
            $data['querySummary'] .= ' published in <strong>' . $data['months'][$data['month']] . '</strong> of <strong>' . $data['year'] . '</strong>.';
        }
        Instance::where('id','>',-1)->paginate(1);
        $data['articleResults'] = $data['articleResults']->orderBy('publication.publish_date', 'DESC')->paginate(15);
        foreach($data['articleResults'] as $article){
            $thisArticle = Article::find($article->article_id);
			if(isset($thisArticle)) {
				$article->original_publish_date = $thisArticle->originalPublishDate();
				$article->original_publication_id = $thisArticle->originalPublication();
			}
			else {
				$article->original_publish_date="";
				$article->original_publication_id="";
			}
        }

        return View::make('public.search')->with($data);
    }

    public function showArchive($instanceName){
        //Fetch Instance out of DB
        $instance = Instance::where('name',strtolower(urldecode($instanceName)))->firstOrFail();

        //Get some pubs
        $publications = Publication::where('instance_id',$instance->id)->published()->orderBy('publish_date','DESC')->paginate(15);

        if(count($publications) > 0){

            $data = array(
                'instance'		=> $instance,
                'instanceId'	=> $instance->id,
                'instanceName'	=> $instance->name,
                'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
                'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
                'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
                'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
            );

            //Setup dropdowns for searching
            $data['years'] = array('--' => '--');

            foreach($data['years'] as $index => $value){
                $index = $value;
            }

            foreach(range(date('Y'), date('Y')-10) as $year){
                $data['years'][$year] = $year;
            }

            $data['months'] = array(
                '--' => '--',
                '1' => 'January',
                '2' => 'February',
                '3' => 'March',
                '4' => 'April',
                '5' => 'May',
                '6' => 'June',
                '7' => 'July',
                '8' => 'August',
                '9' => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December'
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

            //Populate $data
            $data['publications'] = $publications;

        }
        return View::make('public.archive')->with($data);
    }

	public function index()
	{
        $data['publication_id'] = urldecode(Request::segment(4)) ? urldecode(Request::segment(4)) : '';
        //Fetch Instance out of DB
        $instance = Instance::where('name',strtolower($instanceName))->firstOrFail();

        if(Publication::where('instance_id',$instance->id)->published()->count() > 0){

            $data = array(
                'instance'		=> $instance,
                'instanceId'	=> $instance->id,
                'instanceName'	=> $instance->name,
                'tweakables'               => reindexArray($instance->tweakables()->get(), 'parameter', 'value'),
                'default_tweakables'       => reindexArray(DefaultTweakable::all(), 'parameter', 'value'),
                'tweakables_types'         => reindexArray(DefaultTweakable::all(), 'parameter', 'type'),
                'default_tweakables_names' => reindexArray(DefaultTweakable::all(), 'parameter', 'display_name'),
            );

            //Get most recent live publication
            $publication = Publication::where('instance_id',$instance->id)->where('published','Y')->orderBy('promoted')->orderBy('publish_date','desc')->first();

            $articles = array();

            //Get the article order array and grab the articles
            $articleArray = json_decode($publication->article_order);

            foreach($articleArray as $articleID){
                array_push($articles, Article::find($articleID));
            }

            //Populate $data
            $data['publication'] = $publication;
            $data['publication']->articles = $articles;
        }

        return View::make('publication')->with($data);
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