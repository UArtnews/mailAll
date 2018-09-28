<div class="modal fade" id="sendEmailModal{{ $publication->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
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