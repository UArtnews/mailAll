<!DOCTYPE html>
<html>
<head>

    <title>{{$instance->name}} - Archives</title>

    <link async rel="StyleSheet" href="{{ URL::to('css/bootstrap.css') }}" type="text/css" />
    <link async rel="StyleSheet" href="{{ URL::to('css/rrssb.css') }}" type="text/css" />
    <script type="text/javascript">
        document.write("    \<script src='//code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
    </script>
    <script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
    <script src="//uakron.edu/global/includes/js/rrssb.min.js"></script>
    <script type="text/javascript">var addthis_config = {
            "data_track_clickback":false};</script>
    <!--<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js"></script>-->
    @include('...editor.editorStyle',array('default_tweakables' => $default_tweakables, 'tweakables' => $tweakables, 'default_tweakables_names' => $default_tweakables_names))

</head>
<body>
@include('...public.publicNav', array('instanceName' => $instanceName))
<div class="row">
    <div class="">
        <div class="panel panel-default colorPanel">
        </div>
    </div>
</body>
</html>