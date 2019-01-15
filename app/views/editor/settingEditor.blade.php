@extends('editor.master')
@section('content')
<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="settingPanelHead">
        <ul class="nav nav-tabs">
            <li {{$subAction == 'appearanceTweakables' ? 'class="active"' : '' ;}}><a href="{{ URL::to("/edit/$instanceName/settings/appearanceTweakables") }}">Appearance Settings</a></li>
            <li {{$subAction == 'contentStructureTweakables' ? 'class="active"' : '' ;}}><a href="{{ URL::to("/edit/$instanceName/settings/contentStructureTweakables") }}">Content/Structure Options</a></li>
            <li {{$subAction == 'headerFooterTweakables' ? 'class="active"' : '' ;}}><a href="{{ URL::to("/edit/$instanceName/settings/headerFooterTweakables") }}">Text Areas</a></li>
            <li {{$subAction == 'workflowTweakables' ? 'class="active"' : '' ;}}><a href="{{ URL::to("/edit/$instanceName/settings/workflowTweakables") }}">Workflow</a></li>
            <li {{$subAction == 'profiles' ? 'class="active"' : '' ;}}><a href="{{ URL::to("/edit/$instanceName/settings/profiles") }}">Settings Profiles</a></li>
        </ul>
    </div>
    <div class="panel-body" id="settingPanelBody">
        <div class="col-md-5 col-sm-12" id="settingChooser">
            @if($subAction == 'profiles')
<!--  Add Favorites for email --> 
<!--  /edit/{instanceName}/SaveEmailFavorites/{subAction?} -->  

<ul class="nav nav-pills">
	<li role="presentation" class="active">
		  <a id="publications-nav-link" href="{{URL::to('/show/reportMigration/'.$instance->name)}}">
		  <span class="glyphicon glyphicon-transfer"></span>
		  &nbsp;&nbsp;Report Migration</a> 
	</li>
</ul>
            {{ Form::open(array('url' => URL::to('/save/'.$instance->name.'/SaveEmailFavorites/'.$subAction), 'method' => 'post')) }}
            <h3><strong>Save email favorites for this publication:</strong></h3>
       		 <label for="emailfavorite">Add Favorite Email(s)</label>
        	<input name="emailfavorite" type="text" id="emailfavorite">
            {{ Form::submit('Add',array('class'=>'btn btn-sm btn-success')) }}
            {{ Form::close()}}    


			<button type="button" class="btn btn-success" data-toggle="collapse" data-target="#currentFavEmail" id="efavbtn">
					<span class="glyphicon glyphicon-collapse-down"></span> Current Email Favorites
			</button>

                  @if(count($emailFavoritesData) > 0)
                    <ul class="list-group collapse" id="currentFavEmail">
                    @foreach($emailFavoritesData as $emFavItems)
                        <li class="list-group-item">
                            <strong>{{ $emFavItems->value  }}</strong>
                            <button class="btn btn-danger btn-xs pull-right" onclick="deleteEmailFav('{{ $emFavItems->id  }}');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <span class="glyphicon-class"></span> </button>
                            </li>
                    @endforeach
                    </ul>
                <script>
                    function deleteEmailFav(emailFavID){
                        if(confirm('Are you sure you wish to delete this email favorite?')){
                            window.location = "{{ URL::to('/deleteEmailFav/'.$instance->name) }}" + '/' + emailFavID;
                        }
                    }
                </script>
                @else
                    There are no named profiles currently
                @endif            
<!--  Add Favorites for email --> 
<!--  /edit/{instanceName}/SaveEmailFavorites/{subAction?} -->    
            {{ Form::open(array('url' => URL::to('/saveAudience/'.$instance->name.'/SaveEmailAudience/'.$subAction), 'method' => 'post')) }}
            <h3><strong>Save audience labels linked to this publication:</strong></h3>
       		 <label for="emailaudience">Add Audiences</label>
        	<input name="emailaudience" type="text" id="emailaudience">
            {{ Form::submit('Add',array('class'=>'btn btn-sm btn-success')) }}
            {{ Form::close()}}    

			<button type="button" class="btn btn-success" data-toggle="collapse" data-target="#audienceEdit" id="pubAudbtn">
								<span class="glyphicon glyphicon-collapse-down"></span> Current Linked Audiences
			</button>

                  @if(count($emailAudienceData) > 0)
                    <ul class="list-group collapse" id="audienceEdit">
                    @foreach($emailAudienceData as $emAudItems)
                        <li class="list-group-item">
                            <strong>{{ $emAudItems->value  }}</strong>
                            <button class="btn btn-danger btn-xs pull-right" onclick="deleteEmailAud('{{ $emAudItems->id  }}');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <span class="glyphicon-class"></span> </button>
                            </li>
                    @endforeach
                    </ul>
                <script>
                    function deleteEmailAud(emailAudID){
                        if(confirm('Are you sure you wish to remove this audience?')){
                            window.location = "{{ URL::to('/deleteEmailAud/'.$instance->name) }}" + '/' + emailAudID;
                        }
                    }
                </script>
                @else
                    There are no named profiles currently
                @endif 
              
<script>
	$(document).ready(function(){
		  $("#currentFavEmail").on("hide.bs.collapse", function(){
			$("#efavbtn").html('<span class="glyphicon glyphicon-collapse-down"></span> Current Email Favorites');
		  });
		  $("#currentFavEmail").on("show.bs.collapse", function(){
			$("#efavbtn").html('<span class="glyphicon glyphicon-collapse-up"></span> Current Email Favorites');
		  });
		  $("#audienceEdit").on("hide.bs.collapse", function(){
			$("#pubAudbtn").html('<span class="glyphicon glyphicon-collapse-down"></span> Publication Audiences');
		  });
		  $("#audienceEdit").on("show.bs.collapse", function(){
			$("#pubAudbtn").html('<span class="glyphicon glyphicon-collapse-up"></span> Publication Audiences');
		  });
	});
</script>
              
              
              
              
              
			<p>&nbsp;</p><p>&nbsp;</p>
            {{ Form::open(array('url' => URL::to('/save/'.$instance->name.'/profiles'), 'method' => 'post')) }}
            <h3><strong>Save current settings as a named profile:</strong></h3>
            {{ Form::label('profileName','Profile Name') }}
            {{ Form::text('profileName', null) }}
            {{ Form::submit('Save Profile',array('class'=>'btn btn-sm btn-success')) }}
            {{ Form::close()}}<br/><br/>
            <h3><strong>Load settings from a named profile:</strong></h3>
                @if(count($profiles) > 0)
                    <ul class="list-group">
                    @foreach($profiles as $profileName => $profile)
                        <li class="list-group-item">
                            <strong>{{ $profileName  }}</strong>
                            <button class="btn btn-danger btn-xs pull-right" onclick="deleteProfile('{{ $profileName }}');">
                            
                            Delete Profile</button>
                            <a href="{{ URL::to('/loadProfile/'.$instance->name.'/'.$profileName) }}" class="btn btn-warning btn-xs pull-right">Load Profile</a>
                        </li>
                    @endforeach
                    </ul>
                <script>
                    function deleteProfile(profileName){
                        if(confirm('Are you sure you wish to delete profile ' + profileName + '?')){
                            window.location = "{{ URL::to('/deleteProfile/'.$instance->name) }}" + '/' + profileName;
                        }
                    }
                </script>
                @else
                    There are no named profiles currently
                @endif
            @else
            {{ Form::open(array('url' => URL::to('/save/'.$instance->name.'/settings'), 'method' => 'post')) }}
            @foreach($default_tweakables as $defName => $defVal)
                @if(in_array($defName, $$subAction))
                <!-- {{$defName}} Form Input -->
                <div class="row form-group" style="line-height:250%">
                    <div class="col-xs-5" style="text-align:right;">
                        {{ Form::label($defName,$default_tweakables_names[$defName]) }}
                    </div>


                    {{-- Color Pickers --}}
                    @if( $tweakables_types[$defName] == 'color' )
                    <div class="col-xs-7" style="padding-right:5px!important">
                        <div class="input-group colorPicker">
                            {{ Form::text($defName, isset($tweakables[$defName]) ? $tweakables[$defName] : $defVal , array('class' => 'form-control ','placeholder' => '')) }}
                            <span class="input-group-addon"><i></i></span>
                        </div>
                    </div>


                    {{-- CKEditor Regions --}}
                    {{-- These are self-contained editors --}}
                    @elseif( $tweakables_types[$defName] == 'textarea' )
                    <div class="col-xs-7" >
                        <div class="editableSetting" id="{{ $defName }}" contenteditable="true" style="background-color:white;">
                            {{ isset($tweakables[$defName]) ? $tweakables[$defName] : ( strlen($defVal) > 0 ? $defVal : '&nbsp' ) }}
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" onclick="saveSetting('{{ $defName }}')">Save {{ $default_tweakables_names[$defName] }}</button>

                    </div>
<script>
	$(document).ready(function(){
		tinymce.init({
			  selector: ".editableSetting",
			  body_class: 'my_class',
			  height: 500,
			width: '500px',
			  inline: true,
			  menubar: false,
			  browser_spellcheck: true,
			 image_advtab: true,
			  //file_browser_callback : 'myFileBrowser',
			  plugins: [
				'advlist autolink lists link imagetools image charmap print preview anchor textcolor',
				'searchreplace visualblocks code fullscreen',
				'insertdatetime media table contextmenu paste code help wordcount'
			  ],
			  toolbar1: 'insert | undo redo | code removeformat | formatselect',
			  toolbar2: 'bold italic backcolor | alignleft aligncenter alignright alignjustify',
			  toolbar3: 'bullist numlist | outdent indent | image | help',
			  content_css: [
				'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
				'//www.tinymce.com/css/codepen.min.css'],
				 //image_list: "{{ URL::to('json/'.$instanceName.'/images') }}"
			    file_picker_callback: function(callback, value, meta) {
    				imageFilePicker(callback, value, meta);
  				} 
		});	
		$('.editableSetting').click(function(){
			$(".mce-tinymce").css("width","500");
		});	

	});		
	
	
				function saveSetting(defName){
							var editContent = tinyMCE.get(defName);
							console.log(editContent.getContent());
							
                            data = {};
                            data[defName] =editContent.getContent();
							//console.log(data);
                            $.ajax({
                                url: '{{ URL::to('save/'.$instance->name.'/settings') }}',
                                type: 'post',
                                data: data
                            }).success(function(data){
								//console.log('success');
                                location.reload();
                            }).error(function(data){
								alert('Oops!  There was an error.  Try again');
								//console.log(data);
                                //location.reload();'Oops!  There was an error.  Try again' + 
                            });
                        }
                        /*$(document).ready(function(){
                            $('.editableSetting').ckeditor({
                                "extraPlugins": "imagebrowser,sourcedialog,openlink",
                                "imageBrowser_listUrl": "{{ URL::to('json/'.$instanceName.'/images') }}"
                            });
                        });
                        function saveSetting(defName){
                            data = {};
                            data[defName] = CKEDITOR.instances[defName].getData();
							console.log(data);
                            $.ajax({
                                url: '{{ URL::to('save/'.$instance->name.'/settings') }}',
                                type: 'post',
                                data: data
                            }).success(function(data){
								//console.log('success');
                                location.reload();
                            }).error(function(data){
								alert('Oops!  There was an error.  Try again');
                                //location.reload();
                            });
                        }*/
</script>


                    {{-- Boolean Radio Boxes --}}
                    @elseif( $tweakables_types[$defName] == 'bool' )
                    <div class="col-xs-7">
                        {{-- Non-Default Value --}}
                        @if(isset($tweakables[$defName]))
                            Yes&nbsp{{ Form::radio($defName, 1, $tweakables[$defName] == 1 ? true : false) }}
                            No&nbsp{{ Form::radio($defName, 0, $tweakables[$defName] == 0 ? true : false) }}
                        @else
                            Yes&nbsp{{ Form::radio($defName, 1, $defVal == 1 ? true : false) }}
                            No&nbsp{{ Form::radio($defName, 0, $defVal == 0 ? true : false) }}
                        @endif
                    </div>

                    {{-- Normal Textfields --}}
                    @elseif( $tweakables_types[$defName] == 'font' )
                    <div class="col-xs-7">
                        {{-- Dropdowns for fonts, weee!  --}}
                        <select class="form-control" name="{{ $defName }}">
                            @if(isset($tweakables[$defName]))
                            <option value='{{ $tweakables[$defName] }}' style='{{ $tweakables[$defName] }}' selected="true">{{ $tweakables[$defName] }}</option>
                            @endif
                            <option value='Arial, Helvetica, sans-serif' style='font-family:Arial, Helvetica, sans-serif!important;'>Arial, Helvetica, sans-serif</option>
                            <option value='"Arial Black", Gadget, sans-serif' style='font-family:"Arial Black", Gadget, sans-serif!important;'>"Arial Black", Gadget, sans-serif</option>
                            <option value='"Comic Sans MS", cursive, sans-serif' style='font-family:"Comic Sans MS", cursive, sans-serif!important;'>"Comic Sans MS", cursive, sans-serif</option>
                            <option value='Impact, Charcoal, sans-serif' style='font-family:Impact, Charcoal, sans-serif!important;'>Impact, Charcoal, sans-serif</option>
                            <option value='"Lucida Sans Unicode", "Lucida Grande", sans-serif' style='font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif!important;'>"Lucida Sans Unicode", "Lucida Grande", sans-serif</option>
                            <option value='Tahoma, Geneva, sans-serif' style='font-family:Tahoma, Geneva, sans-serif!important;'>Tahoma, Geneva, sans-serif</option>
                            <option value='"Trebuchet MS", Helvetica, sans-serif' style='font-family:"Trebuchet MS", Helvetica, sans-serif!important;'>"Trebuchet MS", Helvetica, sans-serif</option>
                            <option value='Verdana, Geneva, sans-serif' style='font-family:Verdana, Geneva, sans-serif!important;'>Verdana, Geneva, sans-serif</option>
                            <option value='Georgia, serif' style='font-family:Georgia, serif!important;'>Georgia, serif</option>
                            <option value='"Palatino Linotype", "Book Antiqua", Palatino, serif' style='font-family:"Palatino Linotype", "Book Antiqua", Palatino, serif!important;'>"Palatino Linotype", "Book Antiqua", Palatino, serif</option>
                            <option value='"Times New Roman", Times, serif' style='font-family:"Times New Roman", Times, serif!important;'>"Times New Roman", Times, serif</option>
                            <option value='"Courier New", Courier, monospace' style='font-family:"Courier New", Courier, monospace!important;'>"Courier New", Courier, monospace</option>
                            <option value='"Lucida Console", Monaco, monospace' style='font-family:"Lucida Console", Monaco, monospace!important;'>"Lucida Console", Monaco, monospace</option>
                        </select>
                    </div>
                    @elseif( $tweakables_types[$defName] == 'weight')
                    <div class="col-xs-7">
                        {{-- Dropdowns for weight --}}
                        <select class="form-control" name="{{ $defName }}">
                            @if(isset($tweakables[$defName]))
                            <option value="{{ $tweakables[$defName] }}">{{ $tweakables[$defName] }}</option>
                            @endif
                            <option value="normal">normal</option>
                            <option value="bold">bold</option>
                            <option value="lighter">lighter</option>
                            <option value="bolder">bolder</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                            <option value="300">300</option>
                            <option value="400">400</option>
                            <option value="500">500</option>
                            <option value="600">600</option>
                            <option value="700">700</option>
                            <option value="800">800</option>
                            <option value="900">900</option>
                        </select>
                    </div>
                    @else
                    <div class="col-xs-7">
                        {{ Form::text($defName, isset($tweakables[$defName]) ? $tweakables[$defName] : $defVal , array('class' => 'form-control ','placeholder' => '')) }}
                    </div>
                    @endif
                </div>
                @endif
            @endforeach
            <script>
                $(function(){
                    $('.colorPicker').colorpicker();
                });
            </script>

            @if($subAction != 'headerFooterTweakables')

            {{ Form::submit('Save Settings', array('class'=>'form-control btn btn-primary btn-xl')) }}

            @endif
            @endif
        </div>
        <div class="col-md-6 col-sm-12 " id="settingsPreviewer" >
            @if($publication != '')
                @include('publication.master')
            @else
                <h1>No Publication to Preview!</h1>
            @endif
        </div>

    </div>
    {{-- <div class="panel-footer" id="publicationPanelFoot">
    </div> --}}
</div>
@stop