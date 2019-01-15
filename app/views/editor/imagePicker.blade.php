@extends('editor.modalMaster')
@section('content')
{{-- @include('editor.imagePanel') --}}
{{ Form::hidden('image_select',"http://share.uakron.edu", array('id' => 'image_select')) }}
           @foreach($images as $image)
<div class="col-xs-6">
                        <img class="thumbnail" onclick="$('#image_select').val('{{ URL::to('images/'.preg_replace('/[^\w]+/', '_', $instance->name).'/'.$image->filename) }}');$('.thumbnail' ).css({'border' : '1px solid #ddd','opacity': '1','filter': 'alpha(opacity=100)'}); $( this ).css({'border' : '2px solid red','opacity': '0.5','filter': 'alpha(opacity=50)'});" src="{{ URL::to('images/'.preg_replace('/[^\w]+/', '_', $instance->name).'/'.$image->filename) }}" width="30%" />
                        <div class="caption"><strong>{{ $image->filename }}</strong></div>
                @if($count == 4)
                <?$count = 1;?>
	</div>
            <div class="row">
                @else
                <?$count++;?>
                @endif
</div>
                @endforeach

    <div class="panel-footer" id="imagesPanelFoot">
        @if(count($images) == 0)
            No Images Found
        @endif
               {{ $images->links() }}
    </div>


<script>
function tinyMCEUpd(){
	//var frame = $(e.currentTarget).find("iframe").get(0);
	top.tinymce.activeEditor.windowManager.oninsert();
	}

</script>
@stop