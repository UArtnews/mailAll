<!DOCTYPE html>
<html>
<head>
    <title>{{ $instance->name }} Editor - The University of Akron</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- TODO: Conditionally load these js/css resources --}}
    <script type="text/javascript">
        document.write("    \<script src='//code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
    </script>
    <script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
    <!-- <script src="{{ URL::to('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ URL::to('js/ckeditor/adapters/jquery.js') }}"></script> -->
	<script src="{{ URL::to('js/tinymce4/tinymce.min.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ URL::to('js/moment.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap-datetimepicker.min.js') }}"></script>
	 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
	
    <style type="text/css">
		.cke_source {
			white-space: pre-wrap !important;
		}
		.panel-footer {
           text-align: center;
        }
		
		/*++++++++++++++++TOOLTIP++++++++++++++++++++++++++++++++display: inline-block; position: relative; */
		/* Tooltip container */
		.tooltipC {
		  
		  
		  border-bottom: 1px dotted black; /* If you want dots under the hoverable text */
		}

		/* Tooltip text */
		.tooltipC .tooltiptext {
		  visibility: hidden;
		  width: 120px;
		  background-color: #555;
		  color: #fff;
		  text-align: center;
		  padding: 5px 0;
		  border-radius: 6px;

		  /* Position the tooltip text */
		  position: absolute;
		  z-index: 1;
		  bottom: 125%;
		  left: 50%;
		  margin-left: -60px;

		  /* Fade in tooltip */
		  opacity: 0;
		  transition: opacity 0.3s;
		}

		/* Tooltip arrow */
		.tooltipC .tooltiptext::after {
		  content: "";
		  position: absolute;
		  top: 100%;
		  left: 50%;
		  margin-left: -5px;
		  border-width: 5px;
		  border-style: solid;
		  border-color: #555 transparent transparent transparent;
		}

		/* Show the tooltip text when you mouse over the tooltip container */
		.tooltipC:hover .tooltiptext {
		  visibility: visible;
		  opacity: 1;
		}
		.mce-window                         {width:auto !important; top:0px !important; left:0px !important; right:0px !important; bottom:0px !important; background:none !important;}
.mce-window-head                    {background:#FFFFFF !important;}
.mce-window-body                    {background:#FFFFFF !important;}
.mce-foot > .mce-container-body     {padding:10px !important; width:80% !important;}
.mce-panel                          {max-width:100% !important;}
.mce-container                      {max-width:96% !important; height:auto !important; overflow:auto;}
.mce-container-body                 {max-width:96% !important; height:auto !important; overflow:auto;}
.mce-form                           {padding:10px !important;}
.mce-tabs                           {max-width:100% !important;}
.mce-formitem                       {margin:10px 0 !important;}
.mce-abs-layout-item                {position:static !important; width:auto !important;}
.mce-abs-layout-item.mce-label      {display:block !important;}
.mce-abs-layout-item.mce-textbox    {-webkit-box-sizing:border-box !important; -moz-box-sizing:border-box !important; box-sizing:border-box !important; display:block !important; width:80% !important;}
.mce-abs-layout-item.mce-combobox   {display:flex !important;}
.mce-abs-layout-item.mce-combobox > .mce-textbox {-ms-flex:1 1 auto; -webkit-flex:1 1 auto; flex:1 1 auto; height:29px !important; width:80% !important;}
.mce-container-body.mce-window-body.mce-abs-layout iframe {height:500px !important;} /*this only use with responsive file manager 9*/
	</style>

    @include('editor.editorJavascript')

    {{-- Pull in CSS --}}
    <link async rel="StyleSheet" href="{{ URL::to('css/bootstrap-colorpicker.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ URL::to('js/bootstrap-datetimepicker.min.css') }}"/>
    @include('editor.editorStyle')

    @yield('head')

</head>
<body>
@include('editor.editorNav')
@include('editor.cartModal')
<div class="row">
    <div class="col-lg-10 col-lg-offset-1 col-xs-12">
        @include('public.messages')
        @yield('content')
    </div>
</div>

{{-- TODO:  Make this footer useful and modular --}}
<div class="row" style="text-align:center;">
    <a href="{{URL::to('/')}}  ">Publication List</a>
    &nbsp;|&nbsp;
    <a href="{{ URL::to($instance->name) }}">Live Publication View</a>
</div>
</body>
</html>