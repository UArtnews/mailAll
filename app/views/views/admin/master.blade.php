<!DOCTYPE html>
<html>
<head>
    <title>The University of Akron Publication Admin Panel</title>

    {{-- TODO: Conditionally load these js/css resources --}}
    <script type="text/javascript">
        document.write("    \<script src='//code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
    </script>
    <script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ URL::to('js/ckeditor/adapters/jquery.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ URL::to('js/moment.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap-datetimepicker.min.js') }}"></script>

    {{-- Pull in CSS --}}
    <link async rel="StyleSheet" href="{{ URL::to('css/bootstrap-colorpicker.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ URL::to('js/bootstrap-datetimepicker.min.css') }}"/>
    @include('editor.editorStyle')

    @yield('head')

</head>
<body>
@include('admin.adminNav')
<div class="row">
    <div class="col-lg-10 col-lg-offset-1 col-xs-12">
        @include('public.messages')
        @yield('content')
    </div>
</div>
</body>
</html>