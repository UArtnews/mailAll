@extends('editor.master')
@section('content')
<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="publicationPanelHead">
        Publication Editor
        <a href="{{ URL::to("edit/$instanceName/publications") }}" id="backToListPublication" type="button" class="btn btn-primary pull-right btn-xs"><span class="glyphicon glyphicon-arrow-up"></span>&nbsp&nbspBack To List</a>
    </div>
    <div class="panel-body" id="publicationPanelBody">
            @include('publication.master', array('isEditable' => true, 'isEmail' => false, 'shareIcons' => false))
    </div>
    <div class="panel-footer" id="publicationPanelFoot">
    </div>
</div>
@include('editor.sendEmailModal')
@include('editor.fromCartModals')
@stop
