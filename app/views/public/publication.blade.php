@extends('public.master')

@section('content')
@if($publication !== null)
<div class="panel-body" id="publicationPanelBody">
    @include('publication.master', array('shareIcons' => false, 'isEditable' => false, 'isEmail' => false))
</div>
<div class="panel-footer" id="publicationPanelFoot">
    Published on {{ $publication->publish_date }} |  <a href="{{ URL::to($instance->name.'/archive') }}">Archive</a> |  <a href="{{ URL::to('edit/'.$instance->name) }}">Admin</a>
</div>
@else
<div class="panel-body" id="publicationPanelBody">
    <h1>No Publication to Display!</h1>
</div>
<div class="panel-footer" id="publicationPanelFoot">
</div>

@endif
@stop