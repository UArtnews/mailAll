@if(!$isEmail)
<div class="article-container">
@endif
    @foreach($publication->articles as $article)
        @if(!$article->isPublished($publication->id))
            @include('article.article', array('isRepeat' => false, 'hideRepeat' => false))
        @elseif( $article->isPublished($publication->id) && $article->likeNew($publication->id) == 'Y' )
            @include('article.article', array('isRepeat' => true, 'hideRepeat' => false))
        @endif
    @endforeach
@if(!$isEmail)
</div>
@endif