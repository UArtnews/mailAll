<div id="publicationFooter">
    @if($isEmail)
        {{ isset($tweakables['publication-email-footer']) ? $tweakables['publication-email-footer'] : $default_tweakables['publication-email-footer'] }}
    @else
        {{ isset($tweakables['publication-footer']) ? $tweakables['publication-footer'] : $default_tweakables['publication-footer'] }}
    @endif
</div>