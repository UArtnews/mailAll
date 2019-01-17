@extends('editor.master')
@section('content')
<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="publicationPanelHead">
        Publication Editor
    </div>
    <div class="panel-body" id="publicationPanelBody">
        <div class="col-md-10 col-md-offset-1 col-xs-12" id="publicationChooser">
            <div id="newPublication" class="well">
                <h3>Publication Details</h3>
                {{ Form::label('publish_date','Expected Publish Date: ') }}
                {{ Form::text('publish_date', isset($publish_date) ? date('m/d/Y', strtotime($publish_date)) : null, array('id' => 'publish_date', 'class' => 'form-control')) }}
                <br/>
                <script>
                    $(function(){
                        $('#publish_date').datetimepicker({
                            pickTime: false
                        });
                    });
                </script>

                {{ Form::label('banner_image','Header Image Url: ') }}
                {{ Form::text('banner_image', isset($tweakables['publication-banner-image']) ? $tweakables['publication-banner-image'] : $default_tweakables['publication-banner-image'], array('id' => 'banner_image', 'class' => 'form-control')) }}
                <br/>

                {{ Form::label('type','Publication Type: ') }}<br/>
                {{ Form::radio('type', 'regular', true) }}&nbsp;&nbsp;&nbsp;Regular <small>(once published, becomes homepage for publication)</small><br/>
                {{ Form::radio('type', 'special') }}&nbsp;&nbsp;&nbsp;Special <small>(does not become homepage for publication)</small>
                <br/>
                <div class="colorPanel well">
                    <button class="btn btn-success btn-lg" data-toggle="modal" data-target="#addFromCartModal">Add Article from Cart</button>
                    <button class="btn btn-success btn-lg pull-right" onclick="addArticleCartToNewPublication()">Add All Articles From Cart</button>
                    <div class="contentDiv">
                        <img class="publicationBanner  img-responsive" src="{{isset($tweakables['publication-banner-image']) ? $tweakables['publication-banner-image'] : $default_tweakables['publication-banner-image']}}"/>
                        <div class="article-container">
                        </div>
                        <div class="repeat-container">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer" id="publicationPanelFoot">
        <button class="btn btn-success btn-block" onclick="saveNewPublication()">Save Publication</button>
    </div>
</div>
<div class="modal fade" id="addFromCartModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Article Cart<small>&nbsp;&nbsp;Articles ready for inclusion in a publication.</small></h4>
            </div>
            <div class="modal-body">
                @if(isset($cart) && count($cart) > 0)
                <ul id="addFromCartList" class="list-group">
                    @foreach($cart as $article_id => $title)
                    <li id="addCartArticle{{ $article_id }}" class="list-group-item addCartItem">{{ $title }}&nbsp;&nbsp;<button class="btn btn-xs btn-success" onclick="addArticleToNewPublication({{ $article_id }})"><strong>+</strong>&nbsp;Add Article to Publication</button></li>
                    @endforeach
                </ul>
                @else
                <ul id="cartList" class="list-group">
                    <li id="emptyCartItem" class="list-group-item list-group-item-warning">There are no articles in your cart!</li>
                </ul>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop