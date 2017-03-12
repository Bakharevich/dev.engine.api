/**
 * Send API request to crete companies quote
 *
 * @param params
 * @param callback
 */
function createQuote(params, callbackSuccess, callbackError) {
    $.ajax({
        url: '/api/companies_quotes',
        dataType: 'json',
        data: params,
        type: 'post',
        beforeSend: function() {
            $("DIV.overlay").fadeIn(500);
        },
        success: function (result) {
            if (result.status == 1) {
                callbackSuccess(result);
            }
            else {
                alert('Error during adding new quote');
            }
        },
        complete: function() {
            $("DIV.overlay").fadeOut(500);
        },
        error: function (result) {
            $("DIV.overlay").fadeOut(500);

            // set status
            callbackError(result.responseJSON);
        }
    });
}

function getCategoriesByCategoryGroup(params, callbackSuccess, callbackError, element) {
    $.ajax({
        url: '/api/categories/getByCategoryGroup',
        dataType: 'json',
        data: params,
        type: 'get',
        beforeSend: function() {
            element.find('.status').append('<img src="/img/ajax-loader.gif" />');
        },
        success: function (result) {
            if (result.status == 1) {
                callbackSuccess(result);
            }
            else {
                alert('Error during getting categories by category group');
            }
        },
        complete: function() {
            element.find('.status IMG').remove('IMG');
        },
        error: function (result) {
            element.find('.status IMG').remove('IMG');

            // set status
            callbackError(result.responseJSON);
        }
    });
}

/**
 * Show modal to post quote
 *
 * @param id
 * @param name
 */
function modalQuote(id, name) {
    // set values
    $(".modal-quote").find("INPUT[name='company_id']").val(id);
    $(".modal-quote").find("SPAN.modal-quote-company-name").html(name);

    // show modal
    $(".modal-quote").modal('show');
}

/**
 * Get translation for keys
 *
 * @param lang
 * @param keys
 * @param callback
 */
function trans(lang, keys, callback) {
    $.ajax({
        url: '/api/languages/keys',
        dataType: 'json',
        data: {
            lang: lang,
            "keys[]": keys
        },
        success: function(data) {
            callback(data);
        },
        error: function() {
            alert('API LANGUAGES ERROR');
        }
    });
}