<!DOCTYPE html>
<html>
<head>

    <title>{{$instance->name}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link async rel="StyleSheet" href="{{ URL::to('css/bootstrap.css') }}" type="text/css" />
    <link async rel="StyleSheet" href="{{ URL::to('css/rrssb.css') }}" type="text/css" />
    <link async rel="StyleSheet" href="{{ URL::to('css/custom.css') }}" type="text/css" />
    @include('editor.editorStyle', array('default_tweakables' => $default_tweakables, 'tweakables' => $tweakables))
    <script type="text/javascript">
        document.write("    \<script src='//code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
    </script>
    <script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
    <script src="//uakron.edu/global/includes/js/rrssb.min.js"></script>
    <script type="text/javascript">var addthis_config = {
            "data_track_clickback":false};</script>
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js"></script>
    @yield('head')

</head>
<body>
<div class="row">
    {{-- This is to make the background color fill the entire page --}}
    <div class="col-xs-12">
        <div class="colorPanel" style="margin-bottom:0px!important;">
            @include('public.publicNav', array('instanceName' => $instanceName))
            @include('public.messages')
            @yield('content')
        </div>
    </div>
</body>
</html>