<div class="modal fade" id="addFromCartModal{{ $publication->id }}" role="dialog">
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
                    <li id="addCartArticle{{ $article_id }}"class="list-group-item addCartItem">
                        {{ $title }}&nbsp;&nbsp;
                        <button class="btn btn-xs btn-success" onclick="addArticleToExistingPublication({{ $article_id }}, {{ $publication->id }}, true);toggleShowHide('hide','{{ $article_id }}')" id="show_{{ $article_id }}">
                            <strong>+</strong>&nbsp;Add Article to Publication
                        </button>
                        <button class="btn btn-xs btn-danger" onclick="" id="hide_{{ $article_id }}" style="display:none">
                            <strong></strong>&nbsp;Article Added to Publication
                        </button>
                    </li>
                    @endforeach
                </ul>
                <button class="btn btn-success btn-block" onclick="addArticleCartToExistingPublication({{ $publication->id }})">Add All Articles From Cart</button>
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



@if(count($publication->submissions) > 0)
<div class="modal fade" id="addPendingSubmissionsModal{{ $publication->id }}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Pending Submissions<small>&nbsp;&nbsp;Articles accepted from submitters.</small></h4>
            </div>
            <div class="modal-body">
                <ul id="addFromCartList{{ $publication->id }}" class="list-group">
                    @foreach($publication->submissions as $submission)
                    <li id="addPendingSubmission{{ $submission->id }}" class="list-group-item addPendingSubmission">
                        {{ stripslashes($submission->title) }}&nbsp;&nbsp;
                        <button class="btn btn-xs btn-success" onclick="addArticleToExistingPublication({{ $submission->id }}, {{ $publication->id }}, true)">
                            <strong>+</strong>&nbsp;Add Article to Publication
                        </button>
                        @if(preg_match('/'.$submission->id.'/', $publication->article_order))
                            <span class="label label-warning">Included In Publication</span>
                        @endif
                    </li>
                    @endforeach
                </ul>
                <button class="btn btn-success btn-block" onclick="addSubmissionCartToExistingPublication({{ $publication->id }})">Add All Pending Submissions</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endif