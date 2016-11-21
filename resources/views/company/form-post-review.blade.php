<div class="jumbotron" style="padding-top: 30px; padding-bottom: 30px;">
    <h3>Отправить отзыв</h3>

    <form action="/reviews/" method="post" class="form-post-review">
        {{ csrf_field() }}

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name">Иия:</label>
                    <input type="text" class="form-control required" name="name" required />
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="rating">Оценка:</label>
                    <input type="text" class="form-control required" name="rating" required />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="rating">Отзыв:</label>
            <textarea class="form-control required" rows="4" name="review" required></textarea>
        </div>
        <div class="form-group">
            <label for="rating">Фото:</label>
            <input type="file" class="form-control" name="rating" />
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Отправить</button>
        </div>
    </form>
</div>

@section('scripts')
    <script>
        $('.form-post-review').submit(function(e){
            e.preventDefault();

            var isSubmit = true;

            $(".form-post-review .required").each(function(index, val){
                var res = $(val).val();

                if (!res) {
                    isSubmit = false;
                }
            });

            if (isSubmit) this.submit();
        });
    </script>
@stop