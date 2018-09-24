@if($contentEditable)
    <div class="article" id="article{{ $article->id }}">
        <h1 id="articleTitle{{ $article->id }}" class="editable articleTitle">{{ stripslashes($article->title) }}</h1>
        @if(isset($tweakables['publication-hr-titles']))
        @if($tweakables['publication-hr-titles'] == 1)
        <hr/>
        @endif
        @elseif($default_tweakables['publication-hr-titles'] == 1)
        <hr/>
        @endif
        <p id="articleContent{{ $article->id }}" class="editable articleContent">{{ stripslashes($article->content) }}<p>
        <div id="articleIndicator{{ $article->id }}" class="side-indicator">
            <div id="articleIndicator{{ $article->id }}" class="side-indicator-hitbox">
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    @if(isset($tweakables['publication-hr-articles']))
    @if($tweakables['publication-hr-articles'] == 1)
    <hr/>
    @endif
    @elseif($default_tweakables['publication-hr-articles'] == 1)
    <hr/>
    @endif
@else
    <div class="article" id="article{{ $article->id }}">
        @if(isset($isRepeat) && $isRepeat)
            <a href="{{ URL::to($instanceName.'/archive/'.$article->originalPublication().'#articleTitle'.$article->id) }}">
                <h1 id="articleTitle{{ $article->id }}" class="articleTitle">{{ stripslashes($article->title) }}</h1>
            </a>
        @else
            <h1 id="articleTitle{{ $article->id }}" class="articleTitle">{{ stripslashes($article->title) }}</h1>
        @endif
        @if(isset($tweakables['publication-hr-titles']))
        @if($tweakables['publication-hr-titles'] == 1)
        <hr/>
        @endif
        @elseif($default_tweakables['publication-hr-titles'] == 1)
        <hr/>
        @endif
        @if(isset($isEmail) && $isEmail)
            @if(isset($isRepeat) && $isRepeat)
                @if($hideRepeat)
                    <p class="repeatedArticleContent">This article originally appeared on
                        <a href="{{ URL::to($instanceName.'/archive/'.$article->originalPublication().'#articleTitle'.$article->id) }}">{{ date('m-d-Y',strtotime(Publication::find( $article->originalPublication() )->publish_date)); }}</a>
                    </p>
                @else
                    <p id="articleContent{{ $article->id }}" class="editable articleContent" style="{{ $hideRepeat?'display:none;':'' }}">{{ stripslashes($article->content) }}<p>

                @endif
            @else
                <p id="articleContent{{ $article->id }}" class="editable articleContent">{{ stripslashes($article->content) }}<p>
            @endif
        @else
            @if(isset($isRepeat) && $isRepeat)
                <p class="repeatedArticleContent" style="{{ $hideRepeat?'':'display:none;' }}">This article originally appeared on
                    <a href="{{ URL::to($instanceName.'/archive/'.$article->originalPublication().'#articleTitle'.$article->id) }}">{{ date('m-d-Y',strtotime(Publication::find( $article->originalPublication() )->publish_date)); }}</a>
                </p>
                <p id="articleContent{{ $article->id }}" class="editable articleContent" style="{{ $hideRepeat?'display:none;':'' }}">{{ stripslashes($article->content) }}<p>
            @else
                <p id="articleContent{{ $article->id }}" class="editable articleContent">{{ stripslashes($article->content) }}<p>
            @endif
        @endif
    </div>
    <div class="clearfix"></div>
    @if($shareIcons)
        @include('share', array('shareURL' => URL::to($instanceName.'/archive/'.$publication->id),'shareTitle' => stripslashes(strip_tags($article->title)) ) )
    @endif
    @if(isset($tweakables['publication-hr-articles']))
        @if($tweakables['publication-hr-articles'] == 1)
            <hr/>
        @endif
    @elseif($default_tweakables['publication-hr-articles'] == 1)
        <hr/>
    @endif
@endif
