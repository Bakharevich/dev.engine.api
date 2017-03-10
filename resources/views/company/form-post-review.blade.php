<div class="jumbotron" style="z-index: 1;padding-top: 30px; padding-bottom: 30px; background: #f5f5f5;">
    <h3>{{ trans('company.company-review-write-review') }}</h3>

    <form action="/reviews" method="post" class="form-post-review validation">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="name">{{ trans('company.company-review-name') }}:</label>
                    <input type="text" class="form-control required" name="name" required />
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <input type="hidden" class="form-control required" name="rating" id="company-rating" required />
                    <label class="control-label" for="rating">{{ trans('company.company-review-rating') }}:</label>
                    <div class="company-rating">
                        <i class="fa fa-star rating-star" aria-hidden="true" rating="1"></i>
                        <i class="fa fa-star rating-star" aria-hidden="true" rating="2"></i>
                        <i class="fa fa-star rating-star" aria-hidden="true" rating="3"></i>
                        <i class="fa fa-star rating-star" aria-hidden="true" rating="4"></i>
                        <i class="fa fa-star rating-star" aria-hidden="true" rating="5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label" for="review">{{ trans('company.company-review-review') }}:</label>
            <textarea class="form-control required" rows="4" name="review" required></textarea>
        </div>
        <!--
        <div class="form-group">
            <label class="control-label" for="rating">{{ trans('company.company-review-photo') }}:</label>
            <input type="file" class="form-control" name="photo" />
        </div>
        -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary">{{ trans('company.company-review-send') }}</button>
        </div>
    </form>
</div>