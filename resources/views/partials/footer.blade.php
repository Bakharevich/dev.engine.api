<footer class="site-footer">
    <a href="http://<?= Request::get('site')->domain ?>/page/about">{{ trans('common.footer_about') }}</a> |
    <a href="mailto:ib@gogo.by">{{ trans('common.footer_contact') }}</a> |
    <a href="http://<?= Request::get('site')->domain ?>/companies/create">{{ trans('common.footer_add_business') }}</a> |
    <a href="http://<?= Request::get('site')->domain ?>/page/vacancies">{{ trans('common.footer_vacancies') }}</a>
    <br/>
    {!! Request::get('site')->name !!}, {{ date("Y") }}
</footer>