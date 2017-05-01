@extends('layouts.admin')

@section('content')
    <h1>{{ trans('admin.companies') }}</h1>

    <table class="table">
        <thead>
            <tr>
                <th>{{ trans('admin.companies.id') }}</th>
                <th>{{ trans('admin.companies.name') }}</th>
                <th>{{ trans('admin.companies.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
                <tr>
                    <td>{{ $company->id }}</td>
                    <td>{{ $company->name }}</td>
                    <td>
                        <a href="/admin/companies/{{ $company->id }}" class="btn btn-default btn-sm">
                            <span class="glyphicon glyphicon-pencil"></span>
                            {{ trans('admin.global.edit') }}
                        </a>
                        <a href="/admin/companies_photos/{{ $company->id }}" class="btn btn-default btn-sm">
                            <span class="glyphicon glyphicon-picture"></span>
                            {{ trans('admin.global.photos') }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop

@section('scripts')
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
@stop