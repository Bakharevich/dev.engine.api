@if (count($companies) > 0)
    <div class="row">
        @foreach ($companies as $company)
            <div class="col-sm-4">
                <a href="{{ $company->url }}">
                    <img src="{{ $company->main_photo_url }}" class="img-responsive" />
                </a>
            </div>
            <div class="col-sm-8">
                <a href="{{ $company->url }}">{{ $company->name }}</a>

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