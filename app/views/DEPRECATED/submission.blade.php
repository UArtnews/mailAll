<!DOCTYPE html>
<html>
<head>
    <title>{{$instance->name}} Article Submission Form</title>

    <link async rel="StyleSheet" href="{{ URL::to('css/bootstrap.css') }}" type="text/css" />
    <script type="text/javascript">
        document.write("    \<script src='//code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
    </script>
    <script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ URL::to('js/ckeditor/adapters/jquery.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ URL::to('js/moment.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::to('js/bootstrap-datetimepicker.min.css') }}" />
    @include('...editor.editorStyle')

</head>
<body>
@include('...public.publicNav', array('instanceName' => $instanceName))
<div class="row">
</div>
</body>
</html>