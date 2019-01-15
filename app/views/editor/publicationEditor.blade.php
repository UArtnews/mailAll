@extends('editor.master')
@section('content')
<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="publicationPanelHead">
        Publication Editor
        <a href="{{ URL::to("edit/$instanceName/publications") }}" id="backToListPublication" type="button" class="btn btn-primary pull-right btn-xs"><span class="glyphicon glyphicon-arrow-up"></span>&nbsp&nbspBack To List</a>
		
	<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
		<!-- ++++++++++++++++++++++ FOR TESTING ONLY +++++++++++++++++++++++++++++++++++++ -->
		<p>
		  <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" id="previewEmail">
			EMAIL PREVIEW
		  </a>

		</p>
		<div class="collapse" id="collapseExample">
		  <div class="card card-body" id="emailBody">
			<!-- ++++++++++++++++++++++ INLINE HTML FOR EMAIL +++++++++++++++++++++++++++++++++++++ -->	
			  <iframe id="previewFrame" src="{{ $baseURL }}/preview/{{ $instance->name }}/{{ $publication->id }}" style="width: 100%; height: 500px; overflow: hidden;"  scrolling="no"></iframe>
			<!-- ++++++++++++++++++++++ INLINE HTML FOR EMAIL +++++++++++++++++++++++++++++++++++++ -->	  
		  </div>
		</div> 
		<script>
			  $('#previewEmail').click(function () {
				  $('#previewFrame').attr('src', '{{ $baseURL }}/preview/{{ $instance->name }}/{{ $publication->id }}');
			  });
				window.addEventListener('message', function(e) {
					// message passed can be accessed in "data" attribute of the event object
					var scroll_height = e.data;

					document.getElementById('previewFrame').style.height = scroll_height + 'px'; 
				} , false);
			
			
			
		</script>
			<!-- ++++++++++++++++++++++ FOR TESTING ONLY +++++++++++++++++++++++++++++++++++++ -->
			<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ --> 		
    </div>
    <div class="panel-body" id="publicationPanelBody">
            @include('publication.master', array('isEditable' => true, 'isEmail' => false, 'shareIcons' => false))
    </div>
    {{-- <div class="panel-footer" id="publicationPanelFoot">
    </div> --}}
</div>
{{----}} @include('editor.sendEmailModal')
 @include('editor.fromCartModals') 
@stop
  