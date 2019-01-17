<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
     <meta name="format-detection" content="telephone=no"> 
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
    @if(!$isEmail)
        @include('emailStyle')
    @endif
</head>
<body class="colorPanel">
    <table class="colorPanel" align="center" border="0" cellspacing="0" style="width: 90%; max-width: 490px;" width="90%"><!-- <-UPDATED by RTN -->
        <tr>
            <td>
                <table style="margin:0px auto" align="center">
                    <tr>
                    @if((isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position']) == 'left')
                        @if(isset($tweakables['publication-headline-summary']) ? $tweakables['publication-headline-summary'] : $default_tweakables['publication-headline-summary'] == 1)
                        <td class="headline-summary-table">
                            @include('publication.headlineSummary')
                        </td>
                        @endif
                    @endif
                        <td class="contentDiv" id="publication{{ $publication->id }}" >
                            @if(strlen($publication->banner_image) > 0)
                            <table style="margin:0px auto;text-align:center;" align="center">
                                <tr>
                                    <td>
                                        <img class="publicationBanner img-responsive" src="{{$publication->banner_image}}" align="center" />
                                    </td>
                                </tr>
                            </table>
                            @endif
                            @include('publication.publicationEmailHeader')
                            @include('publication.publicationWebHeader')
                            @if((isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position']) == 'center')
                                @if(isset($tweakables['publication-headline-summary']) ? $tweakables['publication-headline-summary'] : $default_tweakables['publication-headline-summary'] == 1)
                                    <span class="publication-headline-summary">
                                        @include('publication.headlineSummary')
                                    </span>
                                @endif
                            @endif
                            @include('publication.publicationHeader')
                            @include('publication.articleContainer')
                            {{-- Conditional Separator --}}
                            @if((isset($tweakables['publication-repeat-separator-toggle']) && $tweakables['publication-repeat-separator-toggle'] == 1 ) || $default_tweakables['publication-repeat-separator-toggle'] == 1 )
                                @if($publication->hasRepeat())
                                    <a name="repeat-container-header"></a>
                                    {{ $tweakables['publication-repeat-separator'] }}
                                    <p>Click the headlines to read the articles in their original publications</p>
                                @endif
                            @endif
                            @include('publication.repeatContainer')
                            @include('publication.publicationFooter')
                        </td>
                    @if((isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position']) == 'right')
                        @if(isset($tweakables['publication-headline-summary']) ? $tweakables['publication-headline-summary'] : $default_tweakables['publication-headline-summary'] == 1)
                        <td class="headline-summary-table">
                            @include('publication.headlineSummary')
                        </td>
                        @endif
                    @endif
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>