@extends('editor.master')
@section('content')
<nav class="navbar navbar-default">
  <div class="container-fluid">
	  <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a id="publications-nav-link" href="{{URL::to('/show/reportMigration/'.$instance->name)}}">
	<span class="glyphicon glyphicon-upload" aria-hidden="true"></span>&nbsp;&nbsp;Upload</a></li>
	@if($view == 'preprocess')
	<li><a id="publications-nav-link" href="{{URL::to('/save/reportMigration/'.$instance->name)}}">
	<span class="glyphicon glyphicon-save" aria-hidden="true"></span>&nbsp;&nbsp;Update DB</a></li>
	@endif			
      </ul>      
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>


@if($view == 'preprocess')
			
<div class="panel panel-default colorPanel">
	<div class="panel-heading" id="publicationPanelHead">
			<h1>Review of Excel Data to be Migrated</h1>
	</div>
	<div class="panel-body" id="livePublicationBody">
			<div class="row publicationEditor" id="publicationEditor">
				<div class="table-responsive  justify-content-center">
					<table class = "table well justify-content-center" >
						@foreach($xldata as $key=>$row)
							@if ($key == 0)					
								<thead>
									@foreach($row as $iKey=>$item)				
											<th >{{ str_limit($iKey, 20); }} </th>											
									@endforeach
								</thead>
							@endif 
								<tr>
									@foreach($row as $iKey=>$item)				
												<td><span data-toggle="tooltip" title="{{ $item }}">{{ str_limit($item, 20); }}</span> </td>
									@endforeach
								</tr>

						@endforeach
					</table>
				</div>
			</div>
	</div>
</div>
@endif

@if($view == 'save')
<div class="panel panel-default colorPanel">
	<div class="panel-heading" id="publicationPanelHead">
			<h1>Publication Table Updates</h1>
	</div>
	<div class="panel-body" id="livePublicationBody">
			<div class="row publicationEditor" id="publicationEditor">
				<div class="table-responsive  justify-content-center">
					<table class = "table well justify-content-center" >
						@foreach($pubData as $key=>$row)
							@if ($key == 0)					
								<thead>
							@endif 
								<tr>
									@foreach($row as $iKey=>$item)				
											@if ($key == 0)
												<th >{{ str_limit($item, 20); }} </th>
											@else
												<td><span data-toggle="tooltip" title="{{ $item }}">{{ str_limit($item, 20); }}</span> </td>
											@endif
									@endforeach
								</tr>
							@if ($key == 0)					
								</thead> 
							@endif 
						@endforeach
					</table>
				</div>
			</div>
	</div>
</div>
<div class="panel panel-default colorPanel">
	<div class="panel-heading" id="publicationPanelHead">
			<h1>Publication Logs Table Updates</h1>
	</div>
	<div class="panel-body" id="livePublicationBody">
			<div class="row publicationEditor" id="publicationEditor">
				<div class="table-responsive  justify-content-center">
					<table class = "table well justify-content-center" >
						@foreach($pubLogData as $plkey=>$row)
							@if ($plkey == 0)					
								<thead>
							@endif 
								<tr>
									@foreach($row as $iplKey=>$item)				
											@if ($plkey == 0)
												<th >{{ str_limit($item, 20); }} </th>
											@else
												<td>
													@if ($iplKey == 'description')<div style="overflow-x: scroll; width: 500px;"><span data-toggle="tooltip"><xmp>{{  $item  }} </xmp></span></div>@else <span data-toggle="tooltip" title="{{ $item }}">{{ str_limit($item, 20); }}</span>  @endif
													
												</td>
											@endif
									@endforeach
								</tr>
							@if ($plkey == 0)					
								</thead> 
							@endif 
						@endforeach
					</table>
				</div>
			</div>
	</div>
</div>
@endif

@if($view == 'viewData')
<div class="panel panel-default colorPanel">
	<div class="panel-heading" id="publicationPanelHead">
			<h1>Upload An Excel File for Migration</h1>
	</div>
	<div class="panel-body" id="livePublicationBody">
			<div class="row publicationEditor center-block" id="publicationEditor">
				<div class="well">
					<form action="{{URL::to('/upload/reportMigration/'.$instance->name)}}" method="POST" enctype="multipart/form-data" >
					
						Upload File:  <input type="file" name="xlsfile" accept=".xlsx, .xls, .csv" />	<br>					
						<input type="submit" value=" Save " />
					</form>
				</div>
			</div>
	</div>
</div>

@endif

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
@stop
  