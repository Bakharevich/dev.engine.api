<style>
    .category-company-description {
        font-size: 0.85em;
        color: #333;
    }
    .category-company-description .quotes {
        font-size: 1.3em;
        line-height: 1em;
        font-weight: bold;

    }
    .company-premium {
        background: #fff1c7;
        padding-top: 15px;
        border-radius: 4px;
    }
    .company-premium A {
        color: #cd4500;
    }
</style>

@if (count($companies) > 0)
        @foreach ($companies as $company)
            <div class="row @if(!empty($company->is_premium)) company-premium @endif" style="margin-bottom: 30px;">
                <div class="col-sm-3">
                    <a href="{{ $company->url }}">
                        @if ($company->main_photo_url)
                            <img src="{{ $company->main_photo_url }}" class="img-responsive img-thumbnail" alt="{{ $company->name }}" title="{{ $company->name }}" />
                        @else
                            <div style="background: #dadada; width: 100%; height: 120px; font-size: 3em; color: #adadad; padding-top: 25px; border: 1px solid #c8c8c8;" class="img-rounded text-center hidden-xs">
                                @if ($category->icon)
                                    <i class="{{ $category->icon }}" aria-hidden="true"></i>
                                @else
                                    <i class="fa fa-picture-o" aria-hidden="true"></i>
                                @endif
                            </div>
                        @endif
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
                        <div class="col-sm-12 category-company-description" style="margin-bottom: 1em;">
                            {!! str_limit(strip_tags($company->description), 350) !!}
                        </div>
                    @endif
                    @if ($company->last_review)
                        <div class="col-sm-12 category-company-description" style="color: #555;">
                            <p>
                                <span class="quotes">&ldquo;</span> {!!  str_limit($company->last_review, 250)  !!} <span class="quotes">&rdquo;</span>
                            </p>
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