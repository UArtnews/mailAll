@extends('editor.master')
@section('content')
<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="publicationPanelHead">
        Publication List
    </div>
    <div class="panel-body" id="publicationPanelBody">
        <div class="col-xs-12" id="publicationChooser">
            <div class="well">
                <h3 class="alert alert-warning" style="text-align:center;">To create a Publication click the <button class="btn btn-small btn-success">+</button> on the date you wish to publish on.</h3>
                {{ $calendar }}
                <script>
                    //Fixes for calendar
                    $(function(){
                        $(".calendarTable th").first().css('text-align','left');
                        $(".calendarTable th").last().css('text-align','right');
                        $(".calendarTable th[colspan='5']").css('text-align','center').css('font-size', '1.5em');
                    });
                </script>
            </div>
            <table class="table well">
                <thead>
                <tr>
                    <th>Publish Date</th>
                    <th>Creation Date</th>
                    <th>Publication Type</th>
                    <th>Live Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($publications as $publication)
                <tr>
                    <td>
                        <a href="{{ URL::to("edit/$instanceName/publication/" . $publication->id) }}">
                            {{ date('m/d/Y', strtotime($publication->publish_date)) }}
                        </a>
                    </td>
                    <td>{{date('m/d/Y', strtotime($publication->created_at))}}</td>
                    <td>{{ucfirst($publication->type)}} Publication</td>
                    <td>
                        @if(isset($currentLivePublication) && $publication->id == $currentLivePublication->id)
                            <a href="{{ URL::to($instance->name) }}"><span class="label" style="background-color:red;">Live</span></a>
                        @elseif($publication->published == 'Y')
                            <span class="label label-success">Published</span>
                        @else
                            <span class="label label-warning">Unpublished</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3">
                        {{$publications->links()}}
                    </th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    {{-- <div class="panel-footer" id="publicationPanelFoot">
    </div> --}}
</div>
@stop