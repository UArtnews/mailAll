@extends('admin.master')

@section('content')
<div class="panel panel-default colorPanel">
    <div class="panel-heading">
        <h3>User Administration</h3>
    </div>
    <div class="panel-body">
        <ul class="list-group">
        @foreach($users as $user)
            <li class="list-group-item ">
                <button class="btn btn-default btn-sm" onclick="showUser(this, {{ $user->id }});">&gt;</button>&nbsp;&nbsp;
                {{ $user->first }} {{ $user->last }} - {{ $user->uanet }} - {{ $user->email }}&nbsp;&nbsp;
            @if($user->isSuperAdmin())
                <span class="label label-info">Super Admin</span>
            @endif
            @foreach($user->permissions as $perm)
                @if($perm->node == 'edit')
                    <span class="label label-success">{{ getInstanceName($perm->instance_id) }} Editor</span>
                @elseif($perm->node == 'admin')
                    <span class="label label-info">{{ getInstanceName($perm->instance_id) }} Admin</span>
                @endif
            @endforeach
            <a class="btn btn-xs btn-danger pull-right" href="{{ URL::to('admin/delete/user/'.$user->id) }}">Delete User</a>
            </li>
            <li id="user{{ $user->id }}" class="list-group-item list-group-item-info" style="display:none;">
                <ul class="list-group">
                @foreach($user->permissions->sortBy('node')->sortBy('instance_id') as $perm)
                    <li class="list-group-item list-group-item-warning">
                        {{ getInstanceName($perm->instance_id) }}:{{ $perm->node }}&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-xs btn-danger" href="{{ URL::to('admin/delete/permissionNode/'.$perm->id) }}">
                            <span class="glyphicon glyphicon-trash">&nbsp;</span>
                            Delete Permission Node
                        </a>
                    </li>
                @endforeach
                    <li class="list-group-item list-group-item-success">
                        {{ Form::open(array('url' => URL::to('admin/save/permissionNode/'), 'class' => 'form-inline', 'role' => 'form')) }}
                        <strong>New Permission Node</strong><br/><br/>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    Scope:
                                </div>
                                {{ Form::select('instance_id', $instances, null, array('class' => 'form-control')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    Function:
                                </div>
                                {{ Form::select('node', $nodes, null, array('class' => 'form-control')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                {{ Form::submit('Add Node', array('class' => 'btn btn-sm btn-success')) }}
                                {{ Form::hidden('user_id', $user->id) }}
                            </div>
                        </div>
                        {{ Form::close() }}
                    </li>
                </ul>
            </li>
        @endforeach
            <li class="list-group-item list-group-item-success">
                {{ Form::open(array('url' => URL::to('admin/save/user/'), 'class' => 'form-inline', 'role' => 'form')) }}
                <strong>New User</strong><br/><br/>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            UanetID:
                        </div>
                        {{ Form::text('uanet', null, array('class' => 'form-control')) }}
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            Email:
                        </div>
                        {{ Form::text('email', null, array('class' => 'form-control')) }}
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            First:
                        </div>
                        {{ Form::text('first', null, array('class' => 'form-control')) }}
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            Last:
                        </div>
                        {{ Form::text('last', null, array('class' => 'form-control')) }}
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        {{ Form::submit('Add User', array('class' => 'btn btn-sm btn-success')) }}
                    </div>
                </div>
                {{ Form::close() }}
            </li>
        </ul>
        <ul class="list-group">
        @foreach($deleted_users as $user)
            <li class="list-group-item list-group-item-danger">
                <strong>{{ $user->first }} {{ $user->last }}</strong> - Deleted on {{ date('F j, Y h:i a' ,strtotime($user->deleted_at)) }}
                 <a class="btn btn-xs btn-info pull-right" href="{{ URL::to('admin/restore/user/'.$user->id) }}">Restore User</a>
            </li>
        @endforeach
        </ul>

    </div>
    <div class="panel-footer">
        <h3>Permission Node Cheat Sheet</h3>
        Permissions are defined as a node with two parts;
        <ul>
            <li>Scope - defines what publications this node applies to</li>
            <li>Function - defines what function this node is permitting</li>
        </ul>
        <br/>
        The available Scopes are as follows;
        <ul>
            <li>Global - This means the following Function will be available in ALL publications</li>
            <li>&lt;Publication-Name&gt; (e.g. Zipmail) - A publication name indicates the function is available specifically for the named publication</li>
        </ul>
        The available Functions are as follows;
        <ul>
            <li>Super Admin - this only applies if paired with the GLOBAL scope.  This function lets administrators acces this page as well as the Instance administration page</li>
            <li>Admin - This function allows the user to Edit the publication as well as send emails and edit the settings/configuration of the publication</li>
            <li>Edit - This function allows the user to Edit the publication but not send emails (includes article editing, publication editing, image upload and submission promotion.</li>
        </ul>
        <br/>
    </div>
</div>
<script>
    function showUser(el, id){
        $('#user'+id).show();
        $(el).attr('onclick', 'hideUser(this, '+id+')');
        $(el).text('\\\/')

    }
    function hideUser(el, id){
        $('#user'+id).hide();
        $(el).attr('onclick', 'showUser(this, '+id+')');
        $(el).text(">")
    }
</script>
@stop