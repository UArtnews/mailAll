<?php

class PublicationOrderTableSeeder extends Seeder {

	public function run()
	{
        DB::table('publication_order')->truncate();

        foreach(Publication::orderBy('publish_date','ASC')->get() as $publication){
            $articles = array();

            foreach(range(1,rand(5,12)) as $articleIndex)
            {
                $article = Article::where('instance_id', $publication->instance_id)->orderBy(DB::raw('RAND()'))->first();
                array_push($articles, $article);
            }

            //Write the order to the table
            $i = 0;
            foreach($articles as $article){
                //Check to see if this article has been "published"
                $count = DB::table('publication')
                    ->leftJoin('publication_order','publication.id','=','publication_order.publication_id')
                    ->where('publication.published','=','Y')
                    ->where('publication_order.article_id','=',$article->id)
                    ->count();

                $likeNew = 'N';
                if($count > 0){
                    if(rand(1,16)%4 == 0){
                        $likeNew = 'Y';
                    }
                }

                PublicationOrder::create(array(
                    'publication_id'    => $publication->id,
                    'article_id'        => $article->id,
                    'likeNew'           => $likeNew,
                    'order'             => $i
                ));

                $i += 1;
            }
        }
	}

}
