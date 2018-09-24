@include('editor.editorStyle')
<style>
    a {
        text-decoration:none;
    }

    li {
        font-size
    }

    body {
        background-color:{{ $tweakables['publication-background-color'] or $default_tweakables['publication-background-color'] }};
        margin-top: 8px;
        margin-bottom: 7px;
        padding:0px;
        mso-margin-top-alt:0px;
        mso-margin-bottom-alt:0px;
        mso-padding-alt: 0px 0px 0px 0px;
    }

    h1 {
        mso-margin-top-alt:0px;
        mso-margin-bottom-alt:0px;
        mso-padding-alt: 0px 0px 0px 0px;
    }

    .btn {
        margin-bottom:1em;
    }
</style>