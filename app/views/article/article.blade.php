@if(!$isEmail)
<div class="article" id="article{{ $article->id }}">
@endif
    {{--                        --}}
    {{--     ARTICLE TITLES     --}}
    {{--                        --}}
    {{-- Setup Href Headlines for Repeats --}}
    @if( $isEmail && ( isset($tweakables['publication-show-titles']) ? $tweakables['publication-show-titles'] : $default_tweakables['publication-show-titles'] ) == false)
        {{-- Suppress titles --}}
    @elseif($isRepeat && $hideRepeat && !$isEditable)
        <a name="articleTitle{{ $article->id }}"></a>
        <a href="{{ preg_replace('/https/','http', URL::to($instanceName.'/archive/'.$article->originalPublication().'#article'.$article->id), 1) }}">
            <h1 id="articleTitle{{ $article->id }}" class="articleTitle{{ $isEditable ? ' editable' : '' }}">{{ $article->getTitle() }}</h1>
            <p>[Click to read more]</p>
        </a>
    {{-- Setup Href Headlines for Emails --}}
    @elseif($isEmail)
        <a name="articleTitle{{ $article->id }}"></a>
        <a href="{{ preg_replace('/https/','http', URL::to($instanceName.'/archive/'.$publication->id.'#article'.$article->id), 1) }}">
            <h1 id="articleTitle{{ $article->id }}" class="articleTitle">{{ $article->getTitle() }}</h1>
        </a>
    @elseif($isEditable && isset($publication))
        <a name="articleTitle{{ $article->id }}"></a>
        <h1 id="articleTitle{{ $article->id }}" class="articleTitle editable">{{ $article->getTitle() }}</h1>
        <a href="{{ preg_replace('/https/','http', URL::to($instanceName.'/archive/'.$publication->id.'#article'.$article->id), 1) }}">
        </a>
    @elseif($isEditable)
        <a name="articleTitle{{ $article->id }}"></a>
        <h1 id="articleTitle{{ $article->id }}" class="articleTitle editable">{{ $article->getTitle() }}</h1>
        </a>
    @else
        <a name="articleTitle{{ $article->id }}"></a>
        <a href="{{ preg_replace('/https/','http', URL::to($instanceName.'/article/'.$article->id), 1) }}">
            <h1 id="articleTitle{{ $article->id }}" class="articleTitle{{ $isEditable ? ' editable' : '' }}">{{ $article->getTitle() }}</h1>
        </a>
    @endif
    {{--                               --}}
    {{-- Conditional HR's after Titles --}}
    {{--                               --}}
    @if(isset($tweakables['publication-hr-titles']))
        @if($tweakables['publication-hr-titles'] == 1)
            <hr/>
        @endif
    @elseif($default_tweakables['publication-hr-titles'] == 1)
        <hr/>
    @endif
    {{--                        --}}
    {{--  ARTICLE CONTENT BODY  --}}
    {{--                        --}}
    {{-- Email Article Content Body --}}
    @if($isEmail && $isRepeat && $hideRepeat)
        {{--<div class="repeatedArticleContent">--}}
            {{--<p>This article originally appeared on--}}
                {{--<a href="{{ preg_replace('/https/','http', URL::to($instanceName.'/archive/'.$article->originalPublication().'#articleTitle'.$article->id), 1) }}">{{ date('n-d-Y',strtotime($article->originalPublishDate())); }}</a>--}}
            {{--</p>--}}
        {{--</div>--}}
    @elseif($isEmail)
        <div id="articleContent{{ $article->id }}" class="articleContent">
            <p>
            @if(( isset($tweakables['publication-email-content-preview']) ? $tweakables['publication-email-content-preview'] : $default_tweakables['publication-email-content-preview'] ) == true)
                {{ $article->getContentPreview() }}&nbsp;&nbsp;
                <a href="{{ preg_replace('/https/','http', URL::to($instanceName.'/archive/'.$publication->id.'#article'.$article->id), 1) }}">[Read More]</a>
            @else
                {{ $article->getContent() }}
            @endif
            </p>
        </div>
    {{-- Non-Email Article Content Body --}}
    @elseif($isRepeat)
        <{{ $isEditable ? 'div' : 'div' }} class="repeatedArticleContent" style="{{ $hideRepeat?'':'display:none;' }}">This article originally appeared on
            <a href="{{ preg_replace('/https/','http', URL::to($instanceName.'/archive/'.$article->originalPublication().'#articleTitle'.$article->id), 1) }}">{{ date('n-d-Y',strtotime($article->originalPublishDate())); }}</a>
            @if($isEditable)
                <button type="button" class="btn btn-xs btn-default" onclick="unhideRepeated({{ $article->id }}, '{{ $publication->id or ''}}');">Show Full Article</button>
            @endif
        </{{ $isEditable ? 'div' : 'div' }}>
        <{{ $isEditable ? 'div' : 'div' }} id="articleContent{{ $article->id }}" class="articleContent{{ $isEditable ? ' editable' : '' }}" style="{{ $hideRepeat?'display:none;':'' }}">{{ $article->getContent() }}</{{ $isEditable ? 'div' : 'div' }}>
    @else
        <{{ $isEditable ? 'div' : 'div' }} id="articleContent{{ $article->id }}" class="articleContent{{ $isEditable ? ' editable' : '' }}">{{ $article->getContent() }}</{{ $isEditable ? 'div' : 'div' }}>
    @endif
    {{--                         --}}
    {{-- Conditional Share Icons --}}
    {{--                         --}}
    @if($shareIcons)
        @include('public.share', array('shareURL' => preg_replace('/https/','http', URL::to($instanceName.'/article/'.$article->id), 1),'shareTitle' => (strip_tags($article->getTitle()) ) ))
    @endif
    {{--                 --}}
    {{-- Editor Controls --}}
    {{--                 --}}
    @if($isEditable)
        <div id="articleIndicator{{ $article->id }}" class="side-indicator">
            <div id="articleIndicator{{ $article->id }}" class="side-indicator-hitbox">
            </div>
            &nbsp;&nbsp;&nbsp;Unsaved<br/>
            &nbsp;&nbsp;&nbsp;Changes
        </div>
    @endif
    @yield('details')
@if(!$isEmail)
</div>
@else
    @if(isset($tweakables['publication-hr-articles']) && $isEmail)
        @if($tweakables['publication-hr-articles'] == 1)
            <hr/>
        @endif
    @elseif($default_tweakables['publication-hr-articles'] == 1 && $isEmail)
        <hr/>
    @endif
@endif

