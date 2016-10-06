<div class="container hidden-xs site-header">
    <div class="col-sm-12 col-md-3 text-center-sm site-header-logo">
        <a href="/">
        <img src="{{ Request::get('site')->logo }}" />
        </a>
    </div>
    <div class="col-sm-7 col-md-5 site-header-search">
        <form class="form-horizontal">
            <div class="input-group">
                <input type="text" class="form-control red-shadow" placeholder="Поиск по сайту" />
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
                </span>
            </div>
        </form>
    </div>
    <div class="col-sm-5 col-md-4 text-right site-header-buttons">
        <a href="#" class="btn btn-chatoff">Добавить заведение</a>
        &nbsp;&nbsp;

        <div class="btn-group">
            <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="glyphicon glyphicon-map-marker"></span> Минск
            </a>

            <ul class="dropdown-menu">
                <li><a href="#">Минск</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">Бобруйск</a></li>
                <li><a href="#">Гомель</a></li>
                <li><a href="#">Борисов</a></li>
            </ul>
        </div>

    </div>
</div>