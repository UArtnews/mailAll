<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#editor-nav-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a id="navbar-brand-link" class="navbar-brand" href="{{URL::to('admin')}}">Admin</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="editor-nav-collapse">
            <!-- Main Tab Links -->
            <ul class="nav navbar-nav">
                <li  @if($action == 'users')class="active"@endif>
                <a id="articles-nav-link" href="{{ URL::to('admin/users') }}">
                    <span class="glyphicon glyphicon-user"></span>&nbsp&nbspUsers
                </a>
                </li>
                <li @if($action == 'instances')class="active"@endif>
                <a id="articles-nav-link" href="{{ URL::to('admin/instances') }}">
                    <span class="glyphicon glyphicon-th-list"></span>&nbsp&nbspInstances
                </a>
                </li>
                <li @if($action == 'editors')class="active"@endif>
                <a id="articles-nav-link" href="{{ URL::secure('/editors') }}">
                    <span class="glyphicon glyphicon-home"></span>&nbsp&nbspEditor Home
                </a>
                </li>
            </ul><!-- End Main Tab Links -->
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>