<ul class="list-group" id="articleResults">
    <li class="list-group-item list-group-item-info"><a href="{{ URL::to("edit/$instanceName/search/articles?search=".$searchVal) }}">Articles</a></li>
    @if($articleResults->count() > 0)
    @foreach($articleResults as $article)
    <li class="list-group-item"><a href="{{ URL::to("edit/$instanceName/article/$article->id") }}">{{ $article->title }} {{-- - By {{User::find($article->author_id)->first}} {{User::find($article->author_id)->last}} - --}} {{ date('m-d-Y', strtotime($article->created_at)) }}</a></li>
    @endforeach
    @else
    <li class="list-group-item list-group-item-warning">No Articles Found</li>
    @endif
    @if($articleResults->count() >= 15 && isset($publicationResults))
    <li class="list-group-item list-group-item-success"><a class="btn btn-success" href="{{ URL::to("edit/$instanceName/search/articles?search=".$searchVal) }}">More Articles Found</a></li>
    @elseif($articleResults->links() != '')
        {{ $articleResults->appends(array('search' => $searchVal))->links() }}
    @endif
</ul>