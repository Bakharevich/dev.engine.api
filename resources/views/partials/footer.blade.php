<footer class="site-footer">
    <a href="#">{{ trans('common.footer_about') }}</a> |
    <a href="#">{{ trans('common.footer_contact') }}</a> |
    <a href="#">{{ trans('common.footer_add_business') }}</a> |
    <a href="#">{{ trans('common.footer_vacancies') }}</a>
    <br/>
    {!! Request::get('site')->name !!}, {{ date("Y") }}
</footer>