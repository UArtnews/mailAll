<div class="row publicationEditor" id="publicationEditor{{$thisPublication->id}}" style="display:{{ $display }};">
    <div class="col-sm-10 col-sm-offset-1 col-xs-12">
        <div class="well" style="text-align:center;">
            <div class="row">
                <div class="col-xs-4">
                    @if(count($thisPublication->submissions) > 0)
                    <div class="btn-group ">
                        <button class="btn btn-success" data-toggle="modal" data-target="#addFromCartModal{{ $thisPublication->id }}">Add From Cart</button>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#addPendingSubmissionsModal{{ $thisPublication->id }}">Submitted Articles</button>
                    </div>
                    @else
                        <button class="btn btn-success pull-left" data-toggle="modal" data-target="#addFromCartModal{{ $thisPublication->id }}">Add Article From Cart</button>
                    @endif
                </div>
                <div class="col-xs-4">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#sendEmailModal{{ $thisPublication->id }}">
                        <span class="glyphicon glyphicon-send"></span>&nbsp;
                        Publish Email
                    </button>
                </div>
                <div class="col-xs-4">
                    <div class="btn-group pull-right">
                        <button id="unpublishBtn{{ $thisPublication->id }}" class="btn btn-danger" onclick="$('#unpublishBtnConfirm{{ $thisPublication->id }}').toggle()" @if($thisPublication->published == 'N')disabled="disabled"@endif>Unpublish</button>
                        <button id="unpublishBtnConfirm{{ $thisPublication->id }}" class="btn btn-warning" onclick="unpublishStatus({{ $thisPublication->id }})" style="display:none;">Are you sure?</button>
                        <button id="publishBtnConfirm{{ $thisPublication->id }}" class="btn btn-warning" onclick="publishStatus({{ $thisPublication->id }})" style="display:none;">Are you sure?</button>
                        <button id="publishBtn{{ $thisPublication->id }}" class="btn btn-success" onclick="$('#publishBtnConfirm{{ $thisPublication->id }}').toggle()" @if($thisPublication->published == 'Y') disabled="disabled" @endif>Publish</button>
                    </div>
                </div>
            </div><br/>
            <div class="row">
                @if(isset($currentLivePublication) && $publication->id == $currentLivePublication->id)
                <a href="{{ URL::to($instance->name) }}"><span class="badge" style="background-color:red;">Live</span></a> Publication published on {{ date('m-d-Y',strtotime($thisPublication->publish_date)) }}
                @elseif($thisPublication->published == 'Y')
                <span class="badge alert-success">Published</span> on {{ date('m-d-Y',strtotime($thisPublication->publish_date)) }}
                @else
                <span class="badge alert-warning">Unpublished</span> to be published on {{ date('m-d-Y',strtotime($thisPublication->publish_date)) }}
                @endif
            </div><br/>
            <div class="row">
                {{ Form::open(array('url' => URL::to('/resource/publication/'.$thisPublication->id), 'method' => 'put', 'class' => 'form-inline')) }}
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                            {{ Form::text('publish_date', null, array('class' => 'datePicker form-control', 'placeholder' => 'Change Publish Date')) }}
                        </div>
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-picture"></span></div>
                            {{ Form::text('banner_image', null , array(
                                'id' => 'bannerInput' . $thisPublication->id,
                                'class' => 'form-control',
                                'placeholder' => 'Change Banner Image'
                            )) }}
                            <div class="input-group-addon btn btn-warning" onclick="defaultBanner('{{ $thisPublication->id }}')">Use Default</div>
                        </div>
                        &nbsp;&nbsp;
                        {{ Form::submit('Apply Publish Date/Banner Changes', array('class' => 'btn btn-success')) }}
                    </div>
                {{ Form::close() }}
            </div>
        </div>
        <!-- Now to iterate through the articles -->
        <div class="contentDiv" id="publication{{ $thisPublication->id }}">
            <img class="publicationBanner  img-responsive" src="{{$thisPublication->banner_image}}"/>
            {{ isset($tweakables['publication-header']) ? $tweakables['publication-header'] : '' }}
            {{-- Insert Article Summary Conditionally --}}
            @if( (isset($tweakables['publication-headline-summary']) ? $tweakables['publication-headline-summary'] : $default_tweakables['publication-headline-summary']) == 1)
                {{-- Center Headline Summary --}}
                <div class="headline-summary">
                @if( (isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position']) == 'center')
                    <div class="headline-summary headline-summary-center">
                        <h3 class="headline-summary-header">Today's News:</h3>
                        @foreach($publication->articles as $article)
                            <a href="#articleTitle{{ $article->id }}">{{ strip_tags($article->title) }}</a><br/>
                        @endforeach
                    </div>
                {{-- Left Hand Headline Summary --}}
                @else
                    <div class="headline-summary headline-summary-{{ isset($tweakables['publication-headline-summary-position']) ? $tweakables['publication-headline-summary-position'] : $default_tweakables['publication-headline-summary-position'] }}">
                        <h3 class="headline-summary-header">Today's News:</h3>
                        @foreach($publication->articles as $article)
                            <a href="#articleTitle{{ $article->id }}">{{ strip_tags($article->title) }}</a><br/>
                        @endforeach
                    </div>
                @endif
                </div>
            @endif
            <div class="article-container">
                @foreach($thisPublication->articles as $article)
                    @if(!$article->isPublished($publication->id))
                        @include('publication.editableWebArticle')
                    @elseif( $article->isPublished($publication->id) && $article->likeNew($publication->id) == 'Y' )
                        @include('publication.editableWebArticle', array('isRepeat' => true, 'hideRepeat' => false))
                    @endif
                @endforeach
            </div>
            <div class="repeat-container">
                @foreach($thisPublication->articles as $article)
                    @if($article->isPublished($publication->id) && $article->likeNew($publication->id) == 'N' )
                        @include('publication.editableWebArticle',array('isRepeat' => true, 'hideRepeat' => true))
                    @endif
                @endforeach
            </div>
            {{ isset($tweakables['publication-footer']) ? $tweakables['publication-footer'] : $default_tweakables['publication-footer'] }}
        </div>
    </div>
</div>
