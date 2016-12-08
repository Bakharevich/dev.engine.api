<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="http://{{ Request::get('site')->domain }}">{!! Request::get('site')->name  !!}</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <!--
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
                -->
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-map-marker" aria-hidden="true"></i> &nbsp;
                        @foreach (Request::get('cities') as $city)
                            @if ($city->id == Request::get('site')->city_id)
                                {{ $city->name }}
                                @break
                            @endif
                        @endforeach
                    </a>
                    <ul class="dropdown-menu">
                        @foreach (Request::get('cities') as $city)
                            <li><a href="#">{{ $city->name }}</a></li>
                        @endforeach
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        @if (!Auth::check())
                            Login <span class="caret"></span>
                        @else
                            {{ Auth::user()->email }} <span class="caret"></span>
                        @endif
                    </a>
                    <ul class="dropdown-menu">
                        @if (!Auth::check())
                            <li><a href="/login">Login</a></li>
                            <li><a href="/register">Register</a></li>
                            <li><a href="/password/reset">Reset Password</a></li>
                        @else
                            <li><a href="/logout">Logout</a></li>
                        @endif
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>