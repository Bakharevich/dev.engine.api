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

/* Menu */
$(function(){
    $(".mainmenu").on('shown.bs.dropdown', function(){
        // get screen width
        var windowWidth = $(window).width();

        // check if it's not mobile
        if (windowWidth <= 767) return false;

        // check if it's not mobile
        var elem = $(this).find('LI.open UL.dropdown-menu');

        // get element width
        var elemWidth = elem.width();
        var elemOffset = elem.offset();

        // sum of all
        var sumAll = elemWidth + elemOffset.left + 35;

        if (sumAll > windowWidth) {
            var leftOffset = windowWidth - sumAll;

            elem.css('left', leftOffset);
        }
    });
});