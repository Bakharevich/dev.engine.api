$(function(){
    /* Catch submitting quote form */
    $(".submit-quote").submit(function(e){
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

        // create quote with callback function
        createQuote(
            params,
            function(data) {
                // success function if everything is ok
                function success(keys) {
                    console.log(keys.result);


                    // hide quote modal
                    $(".modal-quote").modal('hide');

                    // show default success modal
                    $(".modal-default").find('.btn-send').hide();
                    $(".modal-default").find('.btn-cancel').html('Закрыть');
                    $(".modal-default").find('DIV.modal-body').html('<h2 class="text-center">' + keys.result['company.quote-success-title'] + '</h2><p class="text-center">' + keys.result['company.quote-success-text'] + '</p>');
                    $(".modal-default").modal('show');
                }

                // first need to get translations
                $.ajax({
                    url: '/api/languages/keys',
                    dataType: 'json',
                    data: {
                        lang: 'en',
                        "keys[]": ["company.quote-success-title", "company.quote-success-text"]
                    },
                    success: function(data) {
                        success(data);
                    },
                    error: function() {
                        alert('API LANGUAGES ERROR');
                    }
                });
            }
        );
    });

    $("#showSpinner").click(function(){
        //$("DIV.modal.loading").show();
        $("DIV.overlay").fadeIn(500);
    });
})


/* Functions */
function createQuote(params, callback) {
    $.ajax({
        url: '/api/companies_quotes',
        dataType: 'json',
        data: params,
        type: 'post',
        beforeSend: function() {
            console.log('Before send');
        },
        success: function (result) {
            if (result.status == 1) {
                callback(result);
            }
            else {
                alert('Error during adding new quote');
            }
        },
        complete: function() {
            console.log('Complete');
        },
        error: function () {
            alert('API COMPANIES_QUOTES ERROR: Error during adding new quote');
        }
    });
}

/**
 * Get translation for keys
 *
 * @param lang
 * @param keys
 * @param callback
 */
function trans(lang, keys, callback) {

}

/* Global Ajax preloader */
$(document)
    .ajaxStart(function () {
        $("DIV.overlay").fadeIn(500);
    })
    .ajaxStop(function () {
        $("DIV.overlay").fadeOut(500);
    })
    .ajaxError(function () {
        $("DIV.overlay").fadeOut(500);
    })
;