@extends('editor.master')
@section('content')
    {{-- Render currently live publications --}}
    @if($publication != '')
    <div class="panel panel-default colorPanel">
        <div class="panel-heading" id="articlePanelHead">Current Live Publication
            <span class="pull-right">
                Published on {{date('m/d/Y',strtotime($publication->publish_date))}}
                &nbsp;&nbsp;<a href="{{ URL::to("/$instanceName/") }}"><span class="pull-right badge" style="background-color:red;">LIVE</span></a>
            </span>
        </div>
        <div class="panel-body" id="livePublicationBody">
            @include('publication.master', array('isEditable' => true, 'isEmail' => false, 'shareIcons' => false))
        </div>
        <div class="panel-footer" id="articlePanelFoot">
        </div>
    </div>
    @include('editor.sendEmailModal')
    @else
    <div class="panel panel-default colorPanel">
        <div class="panel-body">
            <div class="well">
                <h2>No Publication to Display!</h2>
            </div>
        </div>
    </div>
    @endif
@stop