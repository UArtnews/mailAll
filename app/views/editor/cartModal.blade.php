<div class="modal fade" id="cartModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                    Article Cart <small>&nbsp;&nbsp;Articles ready for inclusion in a publication.</small>
                </h4>
            </div>
            <div class="modal-body">
                @if(isset($cart) && count($cart) > 0)
                <ul class="list-group cartList">
                    @foreach($cart as $article_id => $title)
                    <li class="list-group-item cartItem">
                        <a href="{{ URL::to('edit/'.$instance->name.'/article/'.$article_id) }}">{{ $title }}</a>
                        <button class="btn btn-xs btn-danger pull-right" onclick="removeArticleFromCart({{ $article_id }})">
                            Remove from cart
                        </button>
                    </li>
                    @endforeach
                </ul>
                @else
                <ul class="list-group cartList">
                    <li id="emptyCartItem" class="list-group-item list-group-item-warning">There are no articles in your
                        cart!
                    </li>
                </ul>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" onclick="clearArticleCart()">Clear Cart</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>