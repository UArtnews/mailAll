<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{URL::to($instanceName)}}">{{ ucfirst($instanceName) }}</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                @if($submission)
                <li ><a href="{{URL::to('presubmit/'.$instanceName)}}"><span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;Submit New Article/Announcement</a></li>
                @endif
            </ul>
            <form id="searchForm" class="navbar-form navbar-right" role="search" action="{{ URL::to("$instanceName/search") }}" method="GET">

                <div class="form-group">
                    <input type="text" name="search" class="form-control" placeholder="Search" size="24">
                </div>
                <button type="submit" class="btn btn-default">GO</button>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ URL::to($instance->name.'/archive/') }}">Archives</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
