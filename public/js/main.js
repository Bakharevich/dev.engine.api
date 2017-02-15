/* Rating */
$(function(){
    $('.rating-star').mouseover(function(e){
        // get rating value
        var rating = $(this).attr('rating');

        // be default uncolour all
        $('.company-rating I').removeClass('rating-star-coloured');

        // update class for all less objects
        for (var i = rating; i > 0; i--) {
            $("I[rating='" + i + "']").addClass('rating-star-coloured');
        }

        // set to hidden field
        $("#company-rating").val(rating);
    });
})