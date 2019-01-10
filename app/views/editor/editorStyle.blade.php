<style>

    @import url("{{ URL::to('css/bootstrap.css') }}");
    {{--@import url("{{ URL::to('css/material.css') }}");--}}
    {{--@import url("{{ URL::to('css/ripples.css') }}");--}}

    @include('tweakableStyle')

    .headline-summary-header{
        margin-bottom:.25em;
    }

    #publicationPanelFoot {
        text-align:center;
    }

    .error {
        color:red;
        font-weight:bold;
    }

    #logo {
        margin-bottom:1.5em;
    }

    .centerMe {
        margin:0em auto;
        text-align:center;
    }

    .logo:hover {
        box-shadow: 0 0 30px rgba(255,255,255,.6);
    }

    .publicationBanner {
        margin-bottom:1em;
    }

    .editorSaveRevert {
        background-color:rgba(255,255,255,0.5);
        padding:5px;
        position:absolute;
    }

    .side-indicator-hitbox {
        height:100%;
        width:200%;
        left:-10px;
        position:absolute;
        z-index:-2;
    }

    .side-indicator {
        width:10px;
        opacity:0;
        position:absolute;
        left:-10px;
        color:white;
        -webkit-border-top-left-radius: 5px;
        -webkit-border-bottom-left-radius: 5px;
        -moz-border-radius-topleft: 5px;
        -moz-border-radius-bottomleft: 5px;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        z-index:-1;
        overflow:hidden;
    }


    @-webkit-keyframes slideout {
        0% {
            width:10px;
            left:-10px;
        }
        100% {
            width:100px;
            left:-100px
        }
    }

    @-webkit-keyframes slidein {
        0% {
            width:100px;
            left:-100px
        }
        100% {
            width:10px;
            left:-10px;
        }
    }

    @-webkit-keyframes fadeout {
        0% {
            opacity:0.5;
        }
        100% {
            opacity:0;
        }
    }
    @-webkit-keyframes fadein {
        0% {
            opacity:0;
        }
        100% {
            opacity:0.5;
        }
    }
</style>
