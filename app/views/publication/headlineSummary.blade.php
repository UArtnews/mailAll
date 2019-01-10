<?
//Move the headline summary for the settings page ONLY
if(isset($action) && $action == 'settings'){
    if( (isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position']) == 'left'){
        $tweakables['publication-headline-summary-position'] = 'right';
    }
}
?>
@if($isEmail)
    {{ isset($tweakables['publication-headline-summary-header']) ? str_replace('**DATE**',date('F j, Y', strtotime($publication->publish_date)), $tweakables['publication-headline-summary-header']) : str_replace('**DATE**',date('F j, Y', strtotime($publication->publish_date)), $default_tweakables['publication-headline-summary-header']) }}
    @foreach($publication->articles as $article)
        @if(!$article->isPublished($publication->id))
            {{ str_replace('**HEADLINE**', '<a href="'.preg_replace('/https/','http', URL::to($instanceName.'/archive/'.$article->originalPublication().'#article'.$article->id), 1).'">'.strip_tags(stripslashes($article->title)).'</a><br/>', isset($tweakables['publication-headline-summary-style']) ? $tweakables['publication-headline-summary-style'] : $default_tweakables['publication-headline-summary-style']) }}
        @elseif( $article->isPublished($publication->id) && $article->likeNew($publication->id) == 'Y' )
            {{ str_replace('**HEADLINE**', '<a href="'.preg_replace('/https/','http', URL::to($instanceName.'/archive/'.$article->originalPublication().'#article'.$article->id), 1).'">'.strip_tags(stripslashes($article->title)).'</a><br/>', isset($tweakables['publication-headline-summary-style']) ? $tweakables['publication-headline-summary-style'] : $default_tweakables['publication-headline-summary-style']) }}
        @endif
    @endforeach
    @foreach($publication->articles as $article)
        @if($article->isPublished($publication->id) && $article->likeNew($publication->id) == 'N' )
            {{ str_replace('**HEADLINE**', '<a href="'.preg_replace('/https/','http', URL::to($instanceName.'/archive/'.$publication->id.'#repeat-container-header'), 1).'">Repeated Items</a><br/>', isset($tweakables['publication-headline-summary-style']) ? $tweakables['publication-headline-summary-style'] : $default_tweakables['publication-headline-summary-style']) }}
            <?break;?>
        @endif
    @endforeach
    {{ isset($tweakables['publication-headline-summary-footer']) ? $tweakables['publication-headline-summary-footer'] : $default_tweakables['publication-headline-summary-footer']}}
@elseif( (isset($tweakables['publication-headline-summary']) ? $tweakables['publication-headline-summary'] : $default_tweakables['publication-headline-summary']) == 1)
    {{-- Center Headline Summary --}}
    <div class="headline-summary">
    @if( (isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position']) == 'center')
        <div class="headline-summary headline-summary-center">
            {{ isset($tweakables['publication-headline-summary-header']) ? str_replace('**DATE**',date('F j, Y', strtotime($publication->publish_date)), $tweakables['publication-headline-summary-header']) : str_replace('**DATE**',date('F j, Y', strtotime($publication->publish_date)), $default_tweakables['publication-headline-summary-header']) }}
            @foreach($publication->articles as $article)
                @if(!$article->isPublished($publication->id))
                    {{ str_replace('**HEADLINE**', '<a href="#articleTitle'.$article->id.'">'.strip_tags(stripslashes($article->title)).'</a><br/>', isset($tweakables['publication-headline-summary-style']) ? $tweakables['publication-headline-summary-style'] : $default_tweakables['publication-headline-summary-style']) }}
                @elseif( $article->isPublished($publication->id) && $article->likeNew($publication->id) == 'Y' )
                    {{ str_replace('**HEADLINE**', '<a href="#articleTitle'.$article->id.'">'.strip_tags(stripslashes($article->title)).'</a><br/>', isset($tweakables['publication-headline-summary-style']) ? $tweakables['publication-headline-summary-style'] : $default_tweakables['publication-headline-summary-style']) }}
                @endif
            @endforeach
            @foreach($publication->articles as $article)
                @if($article->isPublished($publication->id) && $article->likeNew($publication->id) == 'N' )
                    {{ str_replace('**HEADLINE**', '<a href="#repeat-container-header">Repeated Items</a><br/>', isset($tweakables['publication-headline-summary-style']) ? $tweakables['publication-headline-summary-style'] : $default_tweakables['publication-headline-summary-style']) }}
                    <?break;?>
                @endif
            @endforeach
            {{ isset($tweakables['publication-headline-summary-footer']) ? $tweakables['publication-headline-summary-footer'] : $default_tweakables['publication-headline-summary-footer']}}
        </div>
    {{-- Left/Right Hand Headline Summary --}}
    @else
        <div class="headline-summary headline-summary-{{ isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position'] }}">
            {{ isset($tweakables['publication-headline-summary-header']) ? str_replace('**DATE**',date('F j, Y', strtotime($publication->publish_date)), $tweakables['publication-headline-summary-header']) : str_replace('**DATE**',date('F j, Y', strtotime($publication->publish_date)), $default_tweakables['publication-headline-summary-header']) }}
            @foreach($publication->articles as $article)
                {{ str_replace('**HEADLINE**', '<a href="#articleTitle'.$article->id.'">'.strip_tags(stripslashes($article->title)).'</a><br/>', isset($tweakables['publication-headline-summary-style']) ? $tweakables['publication-headline-summary-style'] : $default_tweakables['publication-headline-summary-style']) }}
            @endforeach
            {{ isset($tweakables['publication-headline-summary-footer']) ? $tweakables['publication-headline-summary-footer'] : $default_tweakables['publication-headline-summary-footer']}}
        </div>
    @endif
    </div>
@endif