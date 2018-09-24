@if(!$isEmail)
<div class="repeat-container">
@endif
    @foreach($publication->articles as $article)
        @if($article->isPublished($publication->id) && $article->likeNew($publication->id) == 'N' )
            @include('article.article', array('shareIcons' => false, 'isRepeat' => true, 'hideRepeat' => true))
        @endif
    @endforeach
@if(!$isEmail)
</div>
@endif
