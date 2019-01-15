@extends('editor.master')
@section('content')
<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="articlePanelHead">
        Submission Editor
        <button id="backToListSubmission" type="button" class="btn btn-primary pull-right btn-xs" onclick="$('.submissionEditor').slideUp();$('#submissionChooser').slideDown();"><span class="glyphicon glyphicon-arrow-up"></span>&nbsp&nbspBack To List</button>
    </div>
    <div class="panel-body" id="articlePanelBody">
        <div class="col-sm-10 col-sm-offset-1 col-xs-12 table-responsive" id="submissionChooser">
            <table class="table well">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Date Created</th>
                    <th>Issue Dates</th>
                    <th>Organization/Department</th>
                    <th>Submitter</th>
                </tr>
                </thead>
                <tbody>
                @foreach($submissions as $submission)
                <tr >
                    <td>
                        <a href="#" onclick="$('#submissionEditor{{$submission->id}}').slideToggle();$('#submissionChooser').slideToggle();">
                            {{ stripslashes($submission->title) }}
                        </a>
                        @if($submission->promoted == 'N')
                            <a href="#" onclick="promoteSubmission({{ $submission->id }})"><span class="badge pull-right alert-success">Promote Submission to Article</span></a>
                        @else
                            <span class="label label-warning pull-right">Promoted</span>
                        @endif
                    </td>
                    <td>
                        {{date('m/d/Y', strtotime($submission->created_at))}}
                    </td>
                    <td>
                        {{ str_replace(',',', ',str_replace(']','',str_replace('[','',str_replace('"','', stripslashes($submission->issue_dates))))) }}
                    </td>
                    <td>
                        {{ $submission->organization }}  {{ $submission->department }}
                    </td>
                    <td>
                        {{ $submission->name }}
                        {{ strlen($submission->name) > 0 && strlen($submission->email) > 0 ? ',&nbsp;' : ''}}{{-- Conditional Comma --}}
                        {{ $submission->email }}
                    </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="4">
                        {{$submissions->links();}}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        @foreach($submissions as $submission)
        <div class="row submissionEditor" id="submissionEditor{{$submission->id}}" style="display:none;">
            <div class="col-sm-10 col-sm-offset-1 col-xs-12 article table-responsive">
                <div class="contentDiv">
                    <div class="submission" id="submission{{ $submission->id }}">
                        <h1 id="submissionTitle{{ $submission->id }}" class="editable">{{ stripslashes($submission->title) }}</h1>
                        <p id="submissionContent{{ $submission->id }}" class="editable">{{ stripslashes($submission->content) }}<p>
                    </div>
                    <table class="table well" >
                        <thead>
                        <tr>
                            <th>Date Created</th>
                            <th colspan="2">Submitter</th>
                            <th>Phone</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{date('m/d/Y', strtotime($submission->created_at))}}</td>
                            <td colspan="2">{{ $submission->name }}<br/>{{ $submission->email }}</td>
                            <td>{{$submission->phone }}</td>
                        </tr>

                        </tbody>
                        <thead>
                        <tr>
                            <th colspan="2">Issue Dates</th>
                            <th>Location</th>
                            <th>Publish Contact Info?</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="2">
                                {{ str_replace(',',', ',str_replace(']','',str_replace('[','',str_replace('"','', stripslashes($submission->issue_dates))))) }}
                            </td>
                            <td>{{ $submission->location }}</td>
                            <td>
                                {{ $submission->publish_contact_info == 'Y' ? 'Yes' : 'No' }}
                            </td>
                        </tr>

                        </tbody>
                        @if($submission->publish_contact_info == 'Y')
                        <thead>
                        <tr>
                        	<th colspan="2">Contact Name</th>
                            <th>Contact Email</th>
                            <th>Contact Phone</th>
                         </tr>
                         </thead>
                         <tbody>
                         	<tr>
                            	<td colspan="2">{{ $submission->contactName }}</td>
                            	<td>{{ $submission->contactEmail }}</td>
                                <td>{{ $submission->contactPhone }}</td>
                            </tr>
                         </tbody>
                         @endif 
                        <thead>
                        <tr>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ date('m/d/Y', strtotime($submission->event_start_date)) }}</td>
                            <td>{{ date('m/d/Y', strtotime($submission->event_end_date)) }}</td>
                            <td>{{ $submission->start_time }}</td>
                            <td>{{ $submission->end_time }}</td>
                        </tr>

                        </tbody>
                    </table>
                    @if($submission->promoted == 'N')
                        <button id="promote{{ $submission->id }}" class="btn btn-block btn-success" onclick="promoteSubmission({{ $submission->id }})">Promote to Article (and edit)</button>
                    @else
                        <button id="promote{{ $submission->id }}" class="btn btn-block btn-warning" onclick="promoteSubmission({{ $submission->id }})">Promote Again (may duplicate article!)</button>
                    @endif
                    <button id="delete{{ $submission->id }}" class="btn btn-block btn-danger" onclick="deleteSubmission({{ $submission->id }})">Delete Submission</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="panel-footer" id="submissionPanelFoot">
    </div>
</div>
@stop