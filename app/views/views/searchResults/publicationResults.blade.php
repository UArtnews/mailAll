<ul class="list-group" id="publicationResults">
    <li class="list-group-item list-group-item-info"><a href="{{ URL::to("edit/$instanceName/search/publications?search=".$searchVal) }}">Publications</a></li>
    @if(count($publicationResults) > 0)
    @foreach($publicationResults as $publication)
    <li class="list-group-item">
        <a href="{{ URL::to("edit/$instanceName/publication/".$publication->id."#articleTitle".$publication->article_id) }}">
            {{ $publication->title }} - included in
            @if($publication->published == 'N')
            Unpublished Publication created on {{ $publication->created_at }} - last modified {{ $publication->updated_at }}
            @else
            Live Publication published on {{ date('m-d-Y',strtotime($publication->publish_date)) }}
            @endif
        </a>
    </li>
    @endforeach
    @else
    <li class="list-group-item list-group-item-warning">No Publications Found</li>
    @endif
    @if(count($publicationResults) >= 15 && isset($imageResults))
    <li class="list-group-item list-group-item-success">
        <a class="btn btn-success" href="{{ URL::to("edit/$instanceName/search/publications?search=".$searchVal) }}">More Publications Found</a>
    </li>
    @elseif($publicationResults->links() != '')
        {{ $publicationResults->appends(array('search' => $searchVal))->links() }}
    @endif
</ul>