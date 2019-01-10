@extends('public.master')

@section('content')
<div class="panel-body" id="articlePanelBody">
    <div class="contentDiv" id="article{{ $article->id }}">
        @include('article.article', array('shareIcons' => true, 'isEditable' => false, 'isEmail' => false, 'isRepeat' => false, 'hideRepeat' => false))
    </div>
</div>
<div class="panel-footer" id="articlePanelFoot" style="text-align:center;">
    Originally Published <a href="{{ URL::to($instance->name.'/archive/'.$article->originalPublication()) }}">Here</a> |  <a href="{{ URL::to($instance->name.'/archive') }}">Archive</a>
</div>
@stop