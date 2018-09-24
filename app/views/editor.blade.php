<!DOCTYPE html>
<html>
<head>
    <title>The University of Akron Publication Editor</title>

    {{-- TODO: Conditionally load these js/css resources --}}
    <script type="text/javascript">
        document.write("    \<script src='//code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
    </script>
    <script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ URL::to('js/ckeditor/adapters/jquery.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap-colorpicker.js') }}"></script>
    <script src="{{ URL::to('js/moment.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap-datetimepicker.min.js') }}"></script>

    @include('editor.editorJavascript')

    {{-- Pull in CSS --}}
    <link async rel="StyleSheet" href="{{ URL::to('css/bootstrap-colorpicker.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ URL::to('js/bootstrap-datetimepicker.min.css') }}"/>
    @include('editor.editorStyle')

</head>
<body>
@include('editor.editorNav')
@include('editor.cartModal')
<div class="row">
    <div class="col-lg-10 col-lg-offset-1 col-xs-12">

        {{-- Logic to pull in sub-templates --}}
        {{-- Listing of Articles --}}
        @if($action == 'articles')

        @include('editor.articleList')

        {{-- Individual Article Editor --}}
        @elseif($action == 'article')

        @include('editor.articleEditor')

        {{-- Listing of Submissions --}}
        @elseif($action == 'submissions')

        @include('editor.submissionEditor')

        {{-- Listing of Publications --}}
        @elseif($action == 'publications')

        @include('editor.publicationList')

        {{-- Listing of Publications --}}
        @elseif($action == 'publication')

        @include('editor.publicationEditor')

        {{-- New Publication Form --}}
        @elseif($action == 'newPublication')

        @include('editor.newPublicationEditor')

        {{-- Listing of Images --}}
        @elseif($action == 'images')

        @include('editor.imageEditor')

        {{-- Settings Edtior --}}
        @elseif($action == 'settings')

        @include('editor.settingEditor')

        {{-- Search Page/Results --}}
        @elseif($action == 'search')

        @include('editor.searchResults')

        {{-- Help --}}
        @elseif($action == 'help')

        @include('editor.help')

        @else

        {{-- Render currently live publications --}}
        @if(isset($publication))
        <div class="panel panel-default colorPanel">
            <div class="panel-heading" id="articlePanelHead">Current Live Publication
                <span class="pull-right">
                    Published on {{date('m/d/Y',strtotime($publication->publish_date))}}
                    &nbsp;&nbsp;<a href="{{URL::to("/$instanceName/")}}"><span class="pull-right badge" style="background-color:red;">LIVE</span></a>
                </span>
            </div>
            <div class="panel-body" id="livePublicationBody">
                @include('publication.master', array('isEditable' => true, 'isEmail' => false, 'shareIcons' => false))
            </div>
            <div class="panel-footer" id="articlePanelFoot">
            </div>
        </div>
        @else
        <div class="panel panel-default colorPanel">
            <div class="panel-body">
                <div class="well">
                    <h2>No Publication to Display!</h2>
                </div>
            </div>
        </div>
        @endif
        @endif
    </div>
</div>

{{-- TODO:  Make this footer useful and modular --}}
<div class="row" style="text-align:center;">
    <a href="{{URL::to('/')}}  ">Publication List</a>
    &nbsp;|&nbsp;
    <a href="{{ URL::to($instance->name) }}">Live Publication View</a>
</div>

</body>
</html>