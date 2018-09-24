<div class="article" id="article{{ $article->id }}">
    <h1 id="articleTitle{{ $article->id }}" class="editable articleTitle">{{ stripslashes($article->title) }}
    </h1>
    @if(isset($tweakables['publication-hr-titles']))
        @if($tweakables['publication-hr-titles'] == 1)
            <hr/>
        @endif
    @elseif($default_tweakables['publication-hr-titles'] == 1)
        <hr/>
    @endif
    @if(isset($isRepeat) && $isRepeat)
        <p class="repeatedArticleContent" style="{{ $hideRepeat?'':'display:none;' }}">This article originally appeared on <a href="{{ URL::to('edit/'.$instanceName.'/publication/'.$article->originalPublication().'#articleTitle'.$article->id) }}">{{ date('m-d-Y',strtotime(Publication::find( $article->originalPublication() )->publish_date)); }}</a>
            <button type="button" class="btn btn-xs btn-default" onclick="unhideRepeated({{ $article->id }}, '{{ $publication->id or ''}}');">Show Full Article</button>
        </p>
        <p id="articleContent{{ $article->id }}" class="editable articleContent" style="{{ $hideRepeat?'display:none;':'' }}">{{ stripslashes($article->content) }}<p>
    @else
        <p id="articleContent{{ $article->id }}" class="editable articleContent">{{ stripslashes($article->content) }}<p>
    @endif
    <div id="articleIndicator{{ $article->id }}" class="side-indicator">
        <div id="articleIndicator{{ $article->id }}" class="side-indicator-hitbox">
        </div>
        &nbsp;&nbsp;&nbsp;Unsaved<br/>
        &nbsp;&nbsp;&nbsp;Changes
    </div>
    @if(isset($tweakables['publication-hr-articles']))
        @if($tweakables['publication-hr-articles'] == 1)
            <hr/>
        @endif
    @elseif($default_tweakables['publication-hr-articles'] == 1)
        <hr/>
    @endif
</div>

