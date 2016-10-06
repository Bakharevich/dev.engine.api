@if (count($companies) > 0)
    <div class="row">
        @foreach ($companies as $company)
            <div class="col-sm-3">
                <a href="{{ $company->url }}">
                    <img src="{{ $company->main_photo_url }}" class="img-responsive img-thumbnail" />
                </a>
            </div>
            <div class="col-sm-9">
                <a href="{{ $company->url }}" class="category-company-name">{{ $company->name }}</a>

                @if ($company->tel)
                <p>{{ $company->tel }}</p>
                @endif

                @if ($company->address)
                <p>{{ $company->address }}</p>
                @endif
            </div>
        @endforeach
    </div>

    {{ $companies->appends(['option' => $selectedOptions])->links() }}
@else
    <p>
        Нет компаний в данной категории.
    </p>
@endif