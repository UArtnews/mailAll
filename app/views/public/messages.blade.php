@if(Session::has('message') && Session::get('message') != '')
<div class="mailAllMessage alert alert-info alert-dismissible" >
    <button type="button" class="close" onclick="$('.mailAllMessage').hide()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <strong>{{ Session::get('message') }}</strong>
</div>
@endif
@if(Session::has('success') && Session::get('success') != '')
<div class="mailAllSuccess alert alert-success alert-dismissible" >
    <button type="button" class="close" onclick="$('.mailAllSuccess').hide()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <strong>{{ Session::get('success') }}</strong>
</div>
@endif
@if(Session::has('error') && Session::get('error') != '')
<div class="mailAllError alert alert-danger alert-dismissible" >
    <button type="button" class="close" onclick="$('.mailAllError').hide()"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <strong>{{ Session::get('error') }}</strong>
</div>
@endif

