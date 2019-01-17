@extends('editor.master')
@section('content')


<div class="mailAllSuccess alert alert-success alert-dismissible" id="successMsg" style="display:none">
    <button type="button" class="close" onclick="$('#successMsg').hide()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <strong>The Excel file has been downloaded.  Please check your download folder.</strong>
</div>

<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="publicationPanelHead">
        Publication Logs
    </div>

		
    <div class="panel-body" id="livePublicationBody">
		<div class="row publicationEditor" id="publicationEditor">
			<div class="well" style="text-align:center;">
				<div class="row">
					
						{{ Form::open(array('url' => URL::to('show/'.$instanceName.'/publicationLogs/'), 'method' => 'get', 'class' => 'form-inline')) }}
					
						{{ Form::label('publishedonly', 'Published Only:') }}
						{{ Form::checkbox('publishedonly', 'Y', $publishedonly == 'true' ? true : false) }}
						&nbsp;&nbsp;
						{{ Form::label('fromdate', 'From:') }}
						{{ Form::text('fromdate', $fromdate, array('class' => 'form-control', 'id' => 'fromdate')) }}
						&nbsp;&nbsp;
						
						{{ Form::label('todate', 'To:') }}
						{{ Form::text('todate', $todate, array('class' => 'form-control', 'id' => 'todate')) }}
						
						{{ Form::submit('Search', array('class' => 'form-control')) }}
						@if ($downloadit == 'true')							
							<a id="submissions-nav-link" href="{{URL::to('saveExcel/'.$instanceName.'/publicationLogs/saveExcel?'.$getRequestStr)}}" onclick="$('#successMsg').show();">
							<span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download Excel </a><br/>
						@endif

						{{ Form::close() }}						
					</div>
			</div>
			<table class="table well" >
                <thead>
                <tr>
                    <th>Subject</th>
					<th>Instance</th>
                    <th>Submitter</th>
					<th>Audience</th>
                    <th>Published</th>
					<th>URL</th>
					<th>Modified Date</th>
                </tr>
                </thead>
			@foreach($logResults as $logItem)
			

                <tbody>
                <tr>
                    <td>{{ $logItem->Subject }}</td>
					<td>{{ $logItem->type }}</td>
                    <td>{{ $logItem->first }} {{ $logItem->last }}</td>
                    <td>{{ $logItem->audience }}</td>
					 <td>{{ $logItem->Published}}</td>
					<td>{{ $logItem->PublicationURL }}</td>
					<td>{{ date('m-d-Y h:i:s A', strtotime($logItem->updated_at)) }}</td>
                </tr>

                </tbody>
         	
				
			 @endforeach
				
				
</table>
		<div class="row">    
			<div class="col-4 center-block text-center">{{ $logResults->appends(array('fromdate' => $fromdate, 'todate' => $todate, 'publishedonly' => $publishedonly))->links() }}</div>
		</div>

    {{-- <div class="panel-footer" id="publicationPanelFoot">
    </div> --}}
</div>
<script>
$(document).ready(function(){
  $('#fromdate').mask('00/00/0000', {placeholder: "__/__/____"});
  $('#todate').mask('00/00/0000', {placeholder: "__/__/____"});
});
	</script>
@stop
  