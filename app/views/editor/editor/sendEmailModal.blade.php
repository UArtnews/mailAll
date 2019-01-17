<div class="modal fade" id="sendEmailModal{{ $publication->id }}" role="dialog" >
    <div class="modal-dialog">
        <div class="modal-content"  style="height: 800px; overflow-y: auto;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Send Publication Email
                    <small class="alert-warning">
                        &nbsp;&nbsp;
                        @if($publication->published == 'N')
                        This will publish this publication!
                        @else
                        This publication is already published.
                        @endif
                    </small>
                </h4>
            </div>
            <div class="modal-body ">
                {{-- Do Mail Merge --}}
                @if(isset($tweakables['publication-allow-merge']) ? $tweakables['publication-allow-merge'] : $default_tweakables['publication-allow-merge'] == true)
                    {{ Form::open(array('method' => 'post','files' => true, 'url' => URL::to('mergeEmail/'.$instance->name.'/'.$publication->id))) }}

                    {{ Form::label('mergeFile', 'Address File: ') }}
                    {{ Form::file('mergeFile',null ,array('class' => 'form-control')) }}
                    <br/>

                    {{ Form::label('addressField', 'Address Field Name (eg. uanet_id, email, etc. Lowercase, replace punctuation/space(s) with underscore(s)): ') }}
                    {{ Form::text('addressField',null ,array('class' => 'form-control')) }}
                    <br/>

                    {{ Form::label('addressFrom', 'From Address: ') }}
                    {{ Form::text('addressFrom',null ,array('class' => 'form-control')) }}
                    <br/>

                    {{ Form::label('replyTo', 'Reply-To Address: ') }}
                    {{ Form::text('replyTo',null ,array('class' => 'form-control')) }}
                    <br/>

                    {{ Form::label('nameFrom', 'From/Reply-To Name: ') }}
                    {{ Form::text('nameFrom',null ,array('class' => 'form-control')) }}
                    <br/>

                    {{ Form::label('subject', 'Subject: ') }}
                    {{ Form::text('subject',null ,array('class' => 'form-control')) }}
                    <br/>

                    {{ Form::label('isTest', 'Send Test-Email ONLY (will send only one email to the test address using the first entry in the address list): ') }}
                    {{ Form::checkbox('isTest', 'true', array('class' => 'btn btn-warning')) }}
                    <br/>

                    <div id="mergeTestTo">
                        {{ Form::label('testTo', 'Test-Email To Address: ') }}
                        {{ Form::text('testTo',null ,array('class' => 'form-control')) }}
                        <br/>
                    </div>

                    <div class="btn-group">
                        <span class="btn btn-success" onclick="$('#publishEmailSubmit{{ $publication->id }}').toggle()">Email Publication</span>
                        {{ Form::submit('Are you sure you want to publish? ', array('id' => 'publishEmailSubmit'.$publication->id, 'class' => 'btn btn-warning', 'style' => 'display:none;')) }}
                        <span class="btn btn-default" disabled="disabled"><span class="glyphicon glyphicon-send"></span></span>
                    </div>
                    {{ Form::close() }}
                    <script>
                        $('#isTest').change(function(){
                            if($('#isTest').prop('checked')){
                                $('#mergeTestTo').show();
                            }else{
                                $('#mergeTestTo').hide();
                            }
                        });
                    </script>

                {{-- Do Normal Mail --}}
                @else
                    {{ Form::open(array('method' => 'post','url' => URL::to('sendEmail/'.$instance->name.'/'.$publication->id))) }}

                    {{ Form::label('addressTo', 'To: ') }}
                    {{ Form::text('addressTo',null ,array('class' => 'form-control')) }}
                    <br/>
<!-- EMAIL FAVORITES -->				
{{-- @if (Session::has('retData'))
   <div class="alert alert-info">{{ Session::get('retData') }} </div>
@endif --}}
                    <div class="panel panel-default">
                      <div class="panel-heading"><strong>Selected Emails</strong> </div>
                      <div class="panel-body" id="selectedEmails">
					   
                      </div>
                    </div>		

                <div class="panel panel-default " >
                  <div class="panel-heading">
						<div class="panel-title">    
							<button type="button" class="btn btn-success" data-toggle="collapse" data-target="#currentFavEmail" id="efavbtn">
								<span class="glyphicon glyphicon-collapse-down"></span> Favorite Emails
							</button>
						</div>
                  </div>
                  <div class="panel-body collapse" id="currentFavEmail">
					<ul class="list-group">
				
				 @if(count($emailFavoritesData) > 0)
                    <ul class="list-group">
                    @foreach($emailFavoritesData as $emFavItems)
                        <li class="list-group-item">
                            <input type="checkbox" value="{{ $emFavItems->value  }}" name="myEmails[]"  onclick="processEmailChkBx('');" />
                            {{ $emFavItems->value  }}
                        </li>
                    @endforeach 
                    </ul>
                @else
                    There are no Email Favoites currently for this Publication.
                @endif    
					</ul>
					</div>
<script>
	$(document).ready(function(){
		  $("#currentFavEmail").on("hide.bs.collapse", function(){
			$("#efavbtn").html('<span class="glyphicon glyphicon-collapse-down"></span> Favorite Emails');
		  });
		  $("#currentFavEmail").on("show.bs.collapse", function(){
			$("#efavbtn").html('<span class="glyphicon glyphicon-collapse-up"></span> Favorite Emails');
		  });
	});
</script>
<!-- Publication Audiences -->				
{{-- @if (Session::has('retData'))
   <div class="alert alert-info">{{ Session::get('retData') }} </div>
@endif --}}
                    <div class="panel panel-default">
                      <div class="panel-heading"><strong>Selected Audiences</strong></div>
                      <div class="panel-body" id="selectedAudiences">
					
                      </div>
                    </div>

                <div class="panel panel-default">
                  <div class="panel-heading">
                    	<div class="panel-title">    
							<button type="button" class="btn btn-success" data-toggle="collapse" data-target="#audienceEdit" id="pubAudbtn">
								<span class="glyphicon glyphicon-collapse-down"></span> Publication Audiences
							</button>
						</div>
					  
					  
					  
					  
                  </div>
                  <div class="panel-body collapse" id="audienceEdit">
					<ul class="list-group">
				
				 @if(count($emailAudienceData) > 0)
                    <ul class="list-group">
                    @foreach($emailAudienceData as $emAudItems)
                        <li class="list-group-item">
                            <input type="checkbox" value="{{ $emAudItems->value  }}" name="myAudience[]"  Checked = "checked" onclick="processEmailChkBx('audience');" />
                            {{ $emAudItems->value  }}
                        </li>
                    @endforeach 
                    </ul>
                @else
                    There are no Email Favoites currently for this Publication.
                @endif    
					</ul>
					  
                <script>
                    function processEmailChkBx(target){
                    	var checked = [];
                    	var valueHTML = "";
						var targetItem = "input[name='myEmails[]']:checked";
						var targetSelItem = "#selectedEmails";
						if(target == "audience"){
						   targetItem = "input[name='myAudience[]']:checked";
							targetSelItem = "#selectedAudiences";
						   }
                    	$(targetItem).each(function () {
                    	  checked.push($(this).val());                    	  
                    	});
                    	$(targetSelItem).html("");
						//console.log(checked);
                    	$.each(checked, function( index, value ) {
                    		  //console.log( index + ": " + value );
                    		  valueHTML = '<span class="label label-default">' + value + '</span> &nbsp;  &nbsp;';
                    		  $(targetSelItem).prepend(valueHTML);
                    	});               
                    }
                    function processSessionsEmailChkBx(target){
                        var sessStr = "{{ Session::get('retData') }}";
                        var sessArr = sessStr.split(",");
                       	$.each(sessArr, function(  index, value ) {                    		  
                       		setCheckBoxes(value,target);
                    	});
                    }
                    function setCheckBoxes(currValue,target){
                    	$("input[name='myEmails[]']").each(function () {
                    		//console.log( $(this).val() );
                        	if($(this).val() == currValue){
                        		$(this).attr('checked', true);  }                        	             	  
                    	});
                    	processEmailChkBx(target);
                    }
					{{-- @if (Session::has('retData')) --}}
                        $(document).ready(function() {
                        	processSessionsEmailChkBx('');
							processSessionsEmailChkBx('audience');
							processEmailChkBx('audience');
                      	});
                    {{-- @endif --}}

                </script>			
<script>
	$(document).ready(function(){
		  $("#audienceEdit").on("hide.bs.collapse", function(){
			$("#pubAudbtn").html('<span class="glyphicon glyphicon-collapse-down"></span> Publication Audiences');
		  });
		  $("#audienceEdit").on("show.bs.collapse", function(){
			$("#pubAudbtn").html('<span class="glyphicon glyphicon-collapse-up"></span> Publication Audiences');
		  });
	});
</script>
					</div>
                  </div>
					
                </div>
				
			

                    {{ Form::label('addressFrom', 'From Address: ') }}
                    {{ Form::text('addressFrom',null ,array('class' => 'form-control')) }}
                    <br/>

                    {{ Form::label('replyTo', 'Reply-To Address: ') }}
                    {{ Form::text('replyTo',null ,array('class' => 'form-control')) }}
                    <br/>

                    {{ Form::label('nameFrom', 'From/Reply-To Name: ') }}
                    {{ Form::text('nameFrom',null ,array('class' => 'form-control')) }}
                    <br/>

                    {{ Form::label('subject', 'Subject: ') }}
                    {{ Form::text('subject',null ,array('class' => 'form-control')) }}
                    <br/>

                    {{ Form::label('isTest', 'Send Test Email ONLY: ') }}
                    {{ Form::checkbox('isTest', 'true', array('class' => 'btn btn-warning')) }}
                    <br/>

                    <div class="btn-group">
                        <span class="btn btn-success" onclick="$('#publishEmailSubmit{{ $publication->id }}').toggle()">Email Publication</span>
                        {{ Form::submit('Are you sure you want to publish? ', array('id' => 'publishEmailSubmit'.$publication->id, 'class' => 'btn btn-warning', 'style' => 'display:none;')) }}
                        <span class="btn btn-default" disabled="disabled"><span class="glyphicon glyphicon-send"></span></span>
                    </div>

                    {{ Form::close() }}
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    <script>
        $(document).ready(function() {
          $(window).keydown(function(event){
            if(event.keyCode == 13) {
              event.preventDefault();
              return false;
            }
          });
        });
    </script>
</div><!-- /.modal -->