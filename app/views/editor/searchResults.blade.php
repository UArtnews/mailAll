@extends('editor.master')
@section('content')
<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="searchResultsHead">
        Search Results
        <button id="backToListArticle" type="button" class="btn btn-primary pull-right btn-xs" onclick=""><span class="glyphicon glyphicon-arrow-up"></span>&nbsp&nbspBack To List</button>
    </div>
    <div class="panel-body" id="searchResultsPanelBody">
        @if($subAction == 'everything')
            @include('searchResults.articleResults',array('articleResults' => $articleResults))
            @include('searchResults.publicationResults',array('publicationResults' => $publicationResults))
            @include('editor.imagePanel',array('images' => $imageResults, 'isSearch' => true))
        @elseif($subAction == 'articles')
            @include('searchResults.articleResults',array('articleResults' => $articleResults))
        @elseif($subAction == 'publications')
            @include('searchResults.publicationResults',array('publicationResults' => $publicationResults))
        @elseif($subAction == 'images')
            @include('editor.imagePanel',array('images' => $imageResults, 'isSearch' => true))
        @endif
    </div>
    <div class="panel-footer" id="searchResultsPanelFoot">
    </div>
</div>
@stop