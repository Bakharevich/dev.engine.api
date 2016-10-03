@if (count($companies) > 0)
    <div class="row">
        @foreach ($companies as $company)
            <a href="http://{{ $company->domain }}./">
                <div class="col-sm-4">
                    <img src="{{ $company->main_photo_url }}" class="img-responsive" />
                </div>
                <div class="col-sm-8">
                    {{ $company->name }}
                </div>
            </a>
        @endforeach
    </div>

    {{ $companies->appends(['option' => $selectedOptions])->links() }}
@else
    <p>
        Нет компаний в данной категории.
    </p>
@endif