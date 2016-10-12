<style>
    .category-company-description {
        font-size: 0.85em;
        color: #333;
    }
</style>

@if (count($companies) > 0)

        @foreach ($companies as $company)
            <div class="row" style="margin-bottom: 30px;">
                <div class="col-sm-3">
                    <a href="{{ $company->url }}">
                        <img src="{{ $company->main_photo_url }}" class="img-responsive img-thumbnail" />
                    </a>
                </div>
                <div class="col-sm-9">
                    <div class="col-sm-6" style="margin-bottom: 30px;">
                        <div style="margin-bottom: 5px;">
                            <a href="{{ $company->url }}" class="category-company-name">{{ $company->name }}</a>
                        </div>

                        <div>
                            {!! Helper::companyRating($company->rating) !!}
                            &nbsp;
                            {{ $company->amount_comments }} {{ trans('company.number_of_reviews') }}
                        </div>
                    </div>
                    <div class="col-sm-6">

                        @if ($company->address)
                            <p><p><i class="fa fa-map-marker" aria-hidden="true"></i> {!! $company->address  !!}</p>
                        @endif

                        @if ($company->tel)
                            <p><p><i class="fa fa-phone-square" aria-hidden="true"></i> {{ $company->tel }}</p>
                        @endif
                    </div>
                    @if ($company->description)
                        <div class="col-sm-12 category-company-description" style="margin-bottom: 1em;">{{ $company->description }}</div>
                    @endif
                    @if ($company->last_review)
                        <div class="col-sm-12 category-company-description">

                                <p>{!!  str_limit($company->last_review, 250)  !!}</p>

                        </div>
                    @endif
                </div>
            </div>
            <hr>
        @endforeach


    {{ $companies->appends(['option' => $selectedOptions])->links() }}
@else
    <p style="padding: 0px 0px 40px 0px;">
        {{ trans('category.no-companies') }}
    </p>
@endif