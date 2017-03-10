$(function(){
    /* Catch submitting quote form */
    $(".submit-quote").submit(function(e){
        e.preventDefault();

        // get all vars
        var company_id  = $(this).find("INPUT[name='company_id']").val();
        var quote       = $(this).find("TEXTAREA[name='quote']").val();
        var tel         = $(this).find("INPUT[name='tel']").val();
        var email       = $(this).find("INPUT[name='email']").val();

        // create single object
        var params = {
            company_id:     company_id,
            quote:          quote,
            tel:            tel,
            email:          email
        };

        // create quote with callback success and errors functions
        createQuote(
            params,
            function (data) {
                // get vars
                var siteLocale = $("HEAD META[name='site_locale']").attr('content');

                // first need to get translations
                trans(
                    siteLocale,
                    ["company.quote-success-title", "company.quote-success-text", "company.quote-btn-close"],
                    success
                );

                // callback success function if everything is ok
                function success(keys) {
                    // hide quote modal
                    $(".modal-quote").modal('hide');

                    // show default success modal
                    $(".modal-default").find('.btn-send').hide();
                    $(".modal-default").find('.btn-cancel').html(keys.result['company.quote-btn-close']);
                    $(".modal-default").find('DIV.modal-body').html('<h2 class="text-center">' + keys.result['company.quote-success-title'] + '</h2><p class="text-center">' + keys.result['company.quote-success-text'] + '</p>');
                    $(".modal-default").modal('show');
                }

                // clear status field if user wants to resend one more quote
                $("FORM.submit-quote").find('DIV.status').removeClass('alert alert-danger');
            },
            function (errors) {
                // get status div
                var status = $("FORM.submit-quote").find('DIV.status');

                // clear it
                status.html('');

                // add class
                status.addClass('alert alert-danger');

                // add errors
                $.each(errors.error, function(key, value) {
                    status.append('<p>' + value + '</p>');
                });
            }
        );
    });
})

/* Global Ajax preloader */
$(document).ajaxStart(function () {
//    $("DIV.overlay").fadeIn(500);
})
.ajaxStop(function () {
//    $("DIV.overlay").fadeOut(500);
})
.ajaxError(function () {
//    $("DIV.overlay").fadeOut(500);
});