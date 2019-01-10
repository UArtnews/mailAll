{{--  This Doubles as the Image Search Results  Just pass $isSearch as true to nerf new image stuff  --}}
<div class="panel panel{{ isset($isSearch) && $isSearch ? '-info' : '-default' }} colorPanel">
    <div class="panel-heading" id="imagesHead">
        Images
    </div>
    <div class="panel-body" id="imagesPanelBody">
        <div class="showImageUpload" {{ isset($isSearch) && $isSearch ? 'style="display:none;"' : '' }}>
            <button id="upload-new-image-btn" class="btn btn-primary btn-block" onclick="$('.newImageUpload').slideToggle()">Upload New Image</button><br/>
            <div class="row well newImageUpload" style="display:none;">
                {{ Form::open(array('method' => 'post','url' => URL::to('resource/image'), 'files' => true)) }}
                {{ Form::label('title', 'Title', array('for' =>'title')) }}
                {{ Form::text('title',null, array('class' => 'form-control')) }}<br/>

                {{ Form::file('imageFile', array('class' => 'form-control')) }}<br/>

                {{ Form::hidden('instance_id',$instance->id) }}

                {{ Form::submit('Upload New Image',array('class' => 'btn btn-primary')) }}
                {{ Form::close() }}
            </div>
        </div>
        <div id="imageDetails">
    @foreach($images as $image)
        <div id="image{{ $image->id }}" class="row well imageDetailsRow" style="display:none;">
            <div class="col-xs-4">
                <ul class="list-group imageDetails" id="image{{ $image->id }}">
                    <li class="list-group-item"><button class="btn btn-block btn-default" onclick="$('#image{{ $image->id }}').slideUp();">Close</button></li>
                    <li class="list-group-item form-group"><strong>Title: </strong><input id="title{{ $image->id }}" class="form-control" value="{{ $image->title }}"/></li>
                    <li class="list-group-item form-group"><strong>Filename: </strong><input id="filename{{ $image->id }}" class="form-control" value="{{ $image->filename }}"/></li>
                    <li class="list-group-item"><strong>Uploaded on: </strong>{{ date('m-d-Y',strtotime($image->created_at)) }}</li>
                    <li class="list-group-item"><strong>Modified on: </strong>{{ date('m-d-Y',strtotime($image->created_at)) }}</li>
                    <li class="list-group-item">
                        <div class="btn-group">
                            <button class="btn btn-primary" onclick="saveImage(this)">Save</button>
                            <button class="btn btn-warning" data-toggle="modal" data-target="#uploadModal" onclick="setUpload({{ $image->id }})">Re-Upload</button>
                            <button class="btn btn-danger" onclick="deleteImage(this)">Delete</button>
                        </div>
                        <span class="alert-success" id="msgSuccess{{ $image->id }}" style="padding:5px;display:none;"></span>
                        <span class="alert-danger" id="msgError{{ $image->id }}" style="padding:5px;display:none;"></span>
                    </li>
                    <li class="list-group-item">
                        <input type="text" class="form-control" value="{{ URL::to('images/'.preg_replace('/[^\w]+/', '_', $instance->name).'/'.$image->filename,null,false) }}"/>
                    </li>
                </ul>
            </div>
            <div class="col-xs-8">
                <ul class="list-group">
                     <li class="list-group-item">
                        <a href="{{ URL::to('images/'.preg_replace('/[^\w]+/', '_', $instance->name).'/'.$image->filename) }}">
                            <img class="img-responsive" src="{{ URL::to('images/'.preg_replace('/[^\w]+/', '_', $instance->name).'/'.$image->filename) }}"/>
                        </a>
                     </li>
                </ul>
            </div>
        </div>
    @endforeach
        </div>
        <div id="imageThumbs">
            <div class="row">
            <?$count = 1;?>
            @foreach($images as $image)
                <div class="col-xs-12 col-md-6 col-lg-3">
                    <div  class="thumbnail">
                        <img class="img-responsive" onclick="$('.imageDetailsRow').slideUp();$('#image{{ $image->id }}').slideDown();window.scrollTo(0,0);" src="{{ URL::to('images/'.preg_replace('/[^\w]+/', '_', $instance->name).'/'.$image->filename) }}"/>
                        <div class="caption">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    {{ $image->title }}
                                </li>
                                <li class="list-group-item">
                                    <input type="text" class="form-control" value="{{ URL::to('images/'.preg_replace('/[^\w]+/', '_', $instance->name).'/'.$image->filename,null,false) }}"/>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @if($count == 4)
                <?$count = 1;?>
            </div>
            <div class="row">
                @else
                <?$count++;?>
                @endif

                @endforeach
            </div>
        </div>
    </div>
    <div class="panel-footer" id="imagesPanelFoot">
        @if(count($images) == 0)
            No Images Found
        @endif
               {{ $images->links() }}
    </div>
</div>
<div class="modal fade" id="uploadModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Upload Image</h4>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => '', 'id' => 'modalForm', 'method' => 'PUT', 'files' => true)) }}
                Please choose a file to upload from your computer.
                {{ Form::file('imageFile',array('class' => 'form-control')) }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                {{ Form::submit('Submit Image',array('class' => 'btn btn-primary')) }}
                {{ Form::close() }}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->