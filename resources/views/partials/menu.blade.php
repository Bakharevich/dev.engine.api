

@if (Request::get('site')->menu_type == 1)
    <div class="">
        <nav class="navbar navbar-chatoff">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand visible-xs site-menu-logo-xs" href="#">
                        <img src="{{ Request::get('site')->logo }}logo-xs.png" srcset="{{ Request::get('site')->logo }}logo-xs-x2.png" width="132" height="31" />
                    </a>

                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav mainmenu">
                        @foreach (Request::get('menu') as $index => $value)
                            <li>
                                <a href="{{ $value->url }}" class="tab" role="button" aria-expanded="false">
                                    <span class="title">{{ $value->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </nav>
    </div>
@elseif (Request::get('site')->menu_type == 2)
    <div class="">
        <nav class="navbar navbar-chatoff">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand visible-xs site-menu-logo-xs" href="#">
                        <img src="{{ Request::get('site')->logo }}logo-xs.png" srcset="{{ Request::get('site')->logo }}logo-xs-x2.png" width="132" height="31" />
                    </a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav mainmenu">
                        @foreach (Request::get('menu') as $index => $value)
                            <li>
                                <a href="" class="tab" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <span class="title">{{ $value->name1 }}</span>
                                    @if ($value->name2)
                                        <br/>
                                        <span class="subtitle">{{ $value->name2 }}</span>
                                    @else
                                        <br/>
                                        <span class="subtitle">&nbsp;</span>
                                    @endif
                                </a>

                                @if ($value->categories)
                                    <ul class="dropdown-menu">
                                    @foreach ($value->categories as $category)
                                        <li><a href="{{ $category->url }}">{{ $category->name }}</a></li>
                                    @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </nav>
    </div>
@endif
