@extends('editor.master')
@section('content')
<div class="panel panel-default colorPanel">
    <div class="panel-heading" id="articlePanelHead">
        Article List
    </div>
    <div class="panel-body" id="articlePanelBody">
        <div class="col-sm-10 col-sm-offset-1 col-xs-12" id="articleChooser">
            <button id="newArticleButton" class="btn btn-success btn-block" onclick="$('.newArticle').slideToggle();$('#newArticleButton').slideToggle();"><strong>Create New Article</strong></button><br/>
            <div class="newArticle" style="display:none;">
                <div class="contentDiv">
                    <div class="article">
                        <div id="newArticleTitle" class="newEditable well well-sm" ><h4><strong>[Click here to begin editing Title]</strong></h4></div>
                        <div id="newArticleContent" class="newEditable well well-lg"  >[Click here to begin editing Body]</div>
                        <input type="hidden" id="token" value="{{ csrf_token() }}">
                        <hr/>
                    </div>
                    <button class="btn btn-success" onclick="saveNewArticle();">Save</button>
                    <button class="btn btn-warning" onclick="$('.newArticle').slideToggle();$('#newArticleButton').slideToggle();cancelNewArticle();">Cancel</button>
                </div>
                <br/><br/>
            </div>
            <table class="table well">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Date Created</th>
                    <th>Last Updated</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($articles as $article)
                <tr>
                    <td>
                        <a href="{{ URL::to("edit/$instanceName/article/".$article->id) }}">
                            {{stripslashes($article->title)}}
                        </a>
                        @if(isset($cart) && isset($cart[$article->id]))
                            <a href="#" class="addToCartButton{{ $article->id }}" onclick="removeArticleFromCart({{ $article->id }})"><span class="badge pull-right alert-danger">Remove from Cart</span></a>
                        @else
                            <a href="#" class="addToCartButton{{ $article->id }}" onclick="addArticleToCart({{ $article->id }})"><span class="badge pull-right alert-success">Add Article to Cart</span></a>
                        @endif
                    </td>
                    <td>
                        {{date('m/d/Y', strtotime($article->created_at))}}
                    </td>
                    <td>
                        {{date('m/d/Y', strtotime($article->updated_at))}}
                    </td>
                    <td>
                        @if($article->submission == 'Y')
                            <span class="label label-warning">Submission</span>
                        @else
                            <span class="label label-success">In-House</span>
                        @endif
                        @if($article->isPublished())
                            <a a href="{{ URL::to('/edit/'.$instanceName.'/publication/'.$article->originalPublication().'#article'.$article->id) }}" class="label label-danger">Published</a>
                        @else
                            <span class="label label-primary">Unpublished</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="4">
                        {{$articles->links();}}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="panel-footer" id="articlePanelFoot">
    </div>
</div>
@stop