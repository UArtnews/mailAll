<div class="contentDiv" id="publication{{ $publication->id }}">
    @if( (isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position']) != 'center')
        @include('publication.headlineSummary')
    @endif
    @if(strlen($publication->banner_image) > 0)
    <img class="publicationBanner img-responsive" src="{{$publication->banner_image}}" {{ $isEmail ? 'align="center"' : '' }}/>
    @endif
    @include('publication.publicationWebHeader')
    @if( (isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position']) == 'center')
        @include('publication.headlineSummary')
    @endif
    @include('publication.publicationHeader')
    @include('publication.articleContainer')
    @if( (isset($tweakables['publication-repeat-separator']) ? $tweakables['publication-repeat-separator'] : $default_tweakables['publication-repeat-separator']) == true)
        @if($publication->hasRepeat())
            <a name="repeat-container-header"></a>
            {{ $tweakables['publication-repeat-separator'] }}
            <p>Click the headlines to read the articles in their original publications</p>
        @endif
    @endif
    @include('publication.repeatContainer')
    @include('publication.publicationFooter')
</div>