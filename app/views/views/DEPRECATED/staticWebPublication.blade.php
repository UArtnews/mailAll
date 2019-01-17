<div class="contentDiv" id="publication{{ $publication->id }}" @if(isset($hide)) style="display:none;" @endif>
    <img class="publicationBanner img-responsive" src="{{$publication->banner_image}}"/>
    {{ isset($tweakables['publication-header']) ? $tweakables['publication-header'] : '' }}
    {{-- Insert Article Summary Conditionally --}}
    @if( (isset($tweakables['publication-headline-summary']) ? $tweakables['publication-headline-summary'] : $default_tweakables['publication-headline-summary']) == 1)
        {{-- Center Headline Summary --}}
        <div class="headline-summary">
        @if( (isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position']) == 'center')
            <div class="headline-summary headline-summary-center">
                <h3 class="headline-summary-header">Today's News:</h3>
                @foreach($publication->articles as $article)
                    <a href="#articleTitle{{ $article->id }}">{{ strip_tags($article->title) }}</a><br/>
                @endforeach
            </div>
        {{-- Left Hand Headline Summary --}}
        @else
            <div class="headline-summary headline-summary-{{ isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position'] }}">
                <h3 class="headline-summary-header">Today's News:</h3>
                @foreach($publication->articles as $article)
                    <a href="#articleTitle{{ $article->id }}">{{ strip_tags($article->title) }}</a><br/>
                @endforeach
            </div>
        @endif
        </div>
    @endif
    <div class="article-container">
        @foreach($publication->articles as $article)
            @if(!$article->isPublished($publication->id))
                @include('snippet.article', array('contentEditable' => false, 'shareIcons' => false ))
            @elseif( $article->isPublished($publication->id) && $article->likeNew($publication->id) == 'Y' )
                @include('snippet.article', array('contentEditable' => false, 'shareIcons' => false, 'isRepeat' => true, 'hideRepeat' => false))
            @endif
        @endforeach
    </div>
    <div class="repeat-container">
        @foreach($publication->articles as $article)
            @if($article->isPublished($publication->id) && $article->likeNew($publication->id) == 'N' )
                @include('snippet.article', array('contentEditable' => false, 'shareIcons' => false, 'isRepeat' => true, 'hideRepeat' => true))
            @endif
        @endforeach
    </div>
    {{ isset($tweakables['publication-footer']) ? $tweakables['publication-footer'] : $default_tweakables['publication-footer'] }}
</div>