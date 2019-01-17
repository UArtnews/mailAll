@extends('admin.master')

@section('content')
<div class="panel panel-default colorPanel">
    <div class="panel-heading">
        <h3>Instance Administration</h3>
    </div>
    <div class="panel-body">
        <ul class="list-group">
        @foreach($instances as $instance)
            <li class="list-group-item">
                <strong>{{ $instance->name }}</strong> -
                {{ Publication::where('instance_id', $instance->id)->where('published', 'Y')->count() }} live publications,
                 and {{ Article::where('instance_id', $instance->id)->count() }} articles
                 <a class="btn btn-xs btn-danger pull-right" href="{{ URL::to('admin/delete/instance/'.$instance->id) }}">Delete Instance</a>
             </li>
        @endforeach
        </ul>
        <ul class="list-group">
        @foreach($deleted_instances as $instance)
            <li class="list-group-item list-group-item-danger">
                <strong>{{ $instance->name }}</strong> - Deleted on {{ date('F j, Y h:i a' ,strtotime($instance->deleted_at)) }}
                 <a class="btn btn-xs btn-info pull-right" href="{{ URL::to('admin/restore/instance/'.$instance->id) }}">Restore Instance</a>
            </li>
        @endforeach
        </ul>
        <ul class="list-group">
            <li class="list-group-item list-group-item-success">
                        {{ Form::open(array('url' => URL::to('admin/save/instance/'), 'class' => 'form-inline', 'role' => 'form')) }}
                        <strong>New Instance</strong><br/><br/>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    Name:
                                </div>
                                {{ Form::text('name', null, array('class' => 'form-control')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                {{ Form::submit('Add Instance', array('class' => 'btn btn-sm btn-success')) }}
                            </div>
                        </div>
                        {{ Form::close() }}
            </li>
        </ul>
    </div>
    <div class="panel-footer">
    </div>
</div>
@stop