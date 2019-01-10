<div class="well" style="text-align:center;">
    <div class="row">
        <div class="col-xs-4">
            @if(count($publication->submissions) > 0)
            <div class="btn-group ">
                <button class="btn btn-success" data-toggle="modal" data-target="#addFromCartModal{{ $publication->id }}">Add From Cart</button>
                <button class="btn btn-warning" data-toggle="modal" data-target="#addPendingSubmissionsModal{{ $publication->id }}">Submitted Articles</button>
<<<<<<< HEAD
                @if($instance->id == 6 || $instance->id == 6)
                <button class="btn btn-primary" onclick="addIssuedArticlesToCart('{{ $publication->id }}')">Add Dated Articles To Cart</button>
                @endif
            </div>
            @else
                <button class="btn btn-success pull-left" data-toggle="modal" data-target="#addFromCartModal{{ $publication->id }}">Add Article From Cart</button>
                @if($instance->id == 6 || $instance->id == 10)
                <button class="btn btn-primary" onclick="addIssuedArticlesToCart('{{ $publication->id }}')">Add Dated Articles To Cart</button>
                @endif
=======
            </div>
            @else
                <button class="btn btn-success pull-left" data-toggle="modal" data-target="#addFromCartModal{{ $publication->id }}">Add Article From Cart</button>
>>>>>>> mailAllProd/master
            @endif

        </div>
        <div class="col-xs-4">
            @if(Auth::user()->isAdmin($instanceId))
            <button class="btn btn-primary" data-toggle="modal" data-target="#sendEmailModal{{ $publication->id }}">
                <span class="glyphicon glyphicon-send"></span>&nbsp;
                Publish Email
            </button>
            <button class="btn btn-danger" onclick="deletePublication('{{ $publication->id }}')">
                <span class="glyphicon glyphicon-trash"></span>&nbsp;
                Delete Publication
            </button>
            @endif
        </div>
        <div class="col-xs-4">
            <div class="btn-group">
                <button id="regularBtn{{ $publication->id }}" class="btn btn-primary" onclick="$('#regularBtnConfirm{{ $publication->id }}').toggle()" @if($publication->type == 'regular')disabled="disabled"@endif>Regular</button>
                <button id="regularBtnConfirm{{ $publication->id }}" class="btn btn-warning" onclick="publicationType('regular',{{ $publication->id }})" style="display:none;">Are you sure?</button>
                <button id="specialBtnConfirm{{ $publication->id }}" class="btn btn-warning" onclick="publicationType('special',{{ $publication->id }})" style="display:none;">Are you sure?</button>
                <button id="specialBtn{{ $publication->id }}" class="btn btn-warning" onclick="$('#specialBtnConfirm{{ $publication->id }}').toggle()" @if($publication->type == 'special') disabled="disabled" @endif>Special</button>
            </div>
            <div class="btn-group pull-right">
                <button id="unpublishBtn{{ $publication->id }}" class="btn btn-danger" onclick="$('#unpublishBtnConfirm{{ $publication->id }}').toggle()" @if($publication->published == 'N')disabled="disabled"@endif>Unpublish</button>
                <button id="unpublishBtnConfirm{{ $publication->id }}" class="btn btn-warning" onclick="unpublishStatus({{ $publication->id }})" style="display:none;">Are you sure?</button>
                <button id="publishBtnConfirm{{ $publication->id }}" class="btn btn-warning" onclick="publishStatus({{ $publication->id }})" style="display:none;">Are you sure?</button>
                <button id="publishBtn{{ $publication->id }}" class="btn btn-success" onclick="$('#publishBtnConfirm{{ $publication->id }}').toggle()" @if($publication->published == 'Y') disabled="disabled" @endif>Publish</button>
            </div>
        </div>
    </div><br/>
    <div class="row">
        @if(isset($currentLivePublication) && $publication->id == $currentLivePublication->id)
        <a href="{{ URL::to($instance->name) }}"><span class="badge" style="background-color:red;">Live</span></a> Publication published on {{ date('m-d-Y',strtotime($publication->publish_date)) }}
        @elseif($publication->published == 'Y')
        <span class="badge alert-success">Published</span> on {{ date('m-d-Y',strtotime($publication->publish_date)) }}
        @else
        <span class="badge alert-warning">Unpublished</span> to be published on {{ date('m-d-Y',strtotime($publication->publish_date)) }}
        @endif
    </div><br/>
    <div class="row">
        {{ Form::open(array('url' => URL::to('/resource/publication/'.$publication->id), 'method' => 'put', 'class' => 'form-inline')) }}
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                    {{ Form::text('publish_date', null, array('class' => 'datePicker form-control', 'placeholder' => 'Change Publish Date')) }}
                </div>
                <div class="input-group">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-picture"></span></div>
                    {{ Form::text('banner_image', null , array(
                        'id' => 'bannerInput' . $publication->id,
                        'class' => 'form-control',
                        'placeholder' => 'Change Banner Image'
                    )) }}
                    <div class="input-group-addon btn btn-warning" onclick="defaultBanner('{{ $publication->id }}')">Use Default</div>
                </div>
                &nbsp;&nbsp;
                {{ Form::submit('Apply Publish Date/Banner Changes', array('class' => 'btn btn-success')) }}
            </div>
        {{ Form::close() }}
    </div>
</div>
<script>
    $(function (){
        $('.datePicker').datetimepicker({
            pickTime: false
        });
        $('.timePicker').datetimepicker({
            pickDate: false
        });

    });

    function defaultBanner(pubId){
        defaultUrl = '{{ isset($tweakables['publication-banner-image']) ? $tweakables['publication-banner-image'] : '' }}';
        $('#bannerInput'+pubId).val(defaultUrl);
    }
</script>