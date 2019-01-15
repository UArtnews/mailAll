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
			
		<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
		<!-- ++++++++++++++++++++++ FOR TESTING ONLY +++++++++++++++++++++++++++++++++++++ -->
		<p>
		  <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
			EMAIL PREVIEW
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
        <div class="panel-body" id="livePublicationBody">
            @include('publication.master', array('isEditable' => true, 'isEmail' => false, 'shareIcons' => false))
        </div>
        {{-- <div class="panel-footer" id="articlePanelFoot">
        </div> --}}
    </div>
    @include('editor.sendEmailModal')
@include('editor.fromCartModals') 
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