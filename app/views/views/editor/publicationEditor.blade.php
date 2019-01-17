@extends('editor.master')
@section('content')
<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="publicationPanelHead">
        Publication Editor
        <a href="{{ URL::to("edit/$instanceName/publications") }}" id="backToListPublication" type="button" class="btn btn-primary pull-right btn-xs"><span class="glyphicon glyphicon-arrow-up"></span>&nbsp&nbspBack To List</a>
		
		<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
		<!-- ++++++++++++++++++++++ FOR TESTING ONLY +++++++++++++++++++++++++++++++++++++ -->
		<p>
		  <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
			INLINE HTML
		  </a>

		</p>
		<div class="collapse" id="collapseExample">
		  <div class="card card-body" id="emailBody">
			<!-- ++++++++++++++++++++++ INLINE HTML FOR EMAIL +++++++++++++++++++++++++++++++++++++ -->			  
			<!-- ++++++++++++++++++++++ INLINE HTML FOR EMAIL +++++++++++++++++++++++++++++++++++++ -->	  
		  </div>
		</div> 
		<script>
			$( document ).ready(function() {
				var decodeHTML = function (html) {
					var txt = document.createElement('textarea');
					txt.innerHTML = html;
					return txt.value;
				};
					// Example
			// ++++++++++++++++++++++ INLINE HTML FOR EMAIL +++++++++++++++++++++++++++++++++++++					  
				var decoded = decodeHTML('{{{ isset($inlineHTML) ? str_replace("'", "`", preg_replace('/[\r\n]+/','', html_entity_decode($inlineHTML))) : 'Send an Email to see the email body here.' }}}');				  
    				$("#emailBody").html(decoded);
				  }); //end of doc ready function
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
  