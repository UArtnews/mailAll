@extends('public.master')

@section('head')
<script src="{{ URL::to('js/ckeditor/ckeditor.js') }}"></script>
<script src="{{ URL::to('js/ckeditor/adapters/jquery.js') }}"></script>
<link rel="stylesheet" href="{{ URL::to('js/bootstrap-datetimepicker.min.css') }}" />
<script src="{{ URL::to('js/moment.js') }}"></script>
<script src="{{ URL::to('js/bootstrap-datetimepicker.min.js') }}"></script>
@stop

@section('content')
@if(isset($message) && $message != '')
<div class="editorMessage alert alert-info alert-dismissible" >
    <button type="button" class="close" onclick="$('.editorMessage').hide()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <strong>{{ $message }}</strong>
</div>
@endif
@if(isset($success) && $success != '')
<div class="editorSuccess alert alert-success alert-dismissible" >
    <button type="button" class="close" onclick="$('.editorSuccess').hide()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <strong>{{ $success }}</strong>
</div>
@endif
@if(isset($error) && $error != '')
<div class="editorError alert alert-danger alert-dismissible" >
    <button type="button" class="close" onclick="$('.editorError').hide()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <strong>{{ $error }}</strong>
</div>
@endif
<div class="well col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12">
    {{ isset($tweakables['publication-submission-splash']) ? stripslashes($tweakables['publication-submission-splash']) : stripslashes($default_tweakables['publication-submission-splash']) }}

    <div class="row">
        <div class="col-xs-12">
            <a type="button" class="btn btn-primary btn-block" href="{{ URL::to('submit/'.$instanceName) }}">I Agree</a>
        </div>
    </div>
</div>
@stop