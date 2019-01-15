@extends('public.master')

@section('content')
    <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
        <div class="panel panel-default colorPanel">
            <div class="panel-body" id="resultsPanelBody">
                {{ Form::open(array('url' => URL::to($instanceName.'/search'), 'method' => 'get', 'class' => 'form-inline')) }}
                {{ Form::label('search', 'Search Term:') }}
                {{ Form::text('search', isset($searchValue) ? $searchValue : null, array('class' => 'form-control')) }}
                &nbsp;&nbsp;
                {{ Form::label('year', 'Year:') }}
                {{ Form::select('year', $years, $year, array('class' => 'form-control')) }}
                &nbsp;&nbsp;
                {{ Form::label('month', 'Month:') }}
                {{ Form::select('month', $months, $month, array('class' => 'form-control')) }}
                {{ Form::submit('Search', array('class' => 'form-control')) }}
                {{ Form::close() }}<br/>

                <ul class="list-group" id="results">
                    <li class="list-group-item list-group-item-info">Articles {{ $querySummary }}</li>
                    @if(count($articleResults) > 0)
                    @foreach($articleResults as $article)
                        <li class="list-group-item">
                            <a href="{{ URL::to($instance->name.'/archive/'.$article->original_publication_id.'#article'.$article->article_id) }}">
                                @if($article->publication_id == $article->original_publication_id)
                                    <a href="{{ URL::to($instance->name.'/archive/'.$article->original_publication_id.'#article'.$article->article_id) }}">
                                        {{ strip_tags($article->title) }} -- Originally Published on {{ date('m-d-Y', strtotime($article->original_publish_date)) }}
                                    </a>
                                @else
                                    <a href="{{ URL::to($instance->name.'/archive/'.$article->publication_id.'#article'.$article->article_id) }}">
                                        {{ strip_tags($article->title) }} -- Appeared on {{ date('m-d-Y', strtotime($article->publish_date)) }}
                                    </a> --
                                    <a href="{{ URL::to($instance->name.'/archive/'.$article->original_publication_id.'#article'.$article->article_id) }}">
                                        Originally Published on {{ date('m-d-Y', strtotime($article->original_publish_date)) }}
                                    </a>
                                @endif
                            </a>
                        </li>
                    
                    @endforeach
                    @else
                    <li class="list-group-item list-group-item-warning">No Articles Found</li>
                    @endif
                    
                </ul>
                {{ $articleResults->appends(array('search' => $searchValue, 'year' => $year, 'month' => $month))->links() }}
            </div>
    {{-- <div class="panel-footer" id="publicationPanelFoot">
    			</div> --}}
        </div>
    </div>
@stop
