@if($isEditable)
    <div class="row publicationEditor" id="publicationEditor{{$publication->id}}">
        @include('publication.publicationEditorControls')
        @include('publication.publicationContent')
    </div>
@else
    @include('publication.publicationContent')
@endif
