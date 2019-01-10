<?php

class DigestFix extends Seeder {

    public function run()
{
        $articles = Article::all();

        echo "\nArticles Processed:     ";

        $total = count($articles);
        foreach($articles as $current => $article){
            $newContent = $article->content;
            $newContent = preg_replace('/^<p>/','',$newContent);
            $article->content = preg_replace('/<\/p>$/','',$newContent);
            $article->save();
            $this->drawProgress($current, $total);
        }
    }

    public function drawProgress($part, $total){
        echo "\033[5D";
        echo str_pad(round($part/$total*100), 3, ' ', STR_PAD_LEFT) . " %";
    }

}