$(function(){
    /* Catch submitting quote form */
    $(".submit-quote").submit(function(e){
        var company_id  = $(this).find("INPUT[name='company_id']").val();
        var quote       = $(this).find("TEXTAREA[name='quote']").val();
        var tel         = $(this).find("INPUT[name='tel']").val();
        var email       = $(this).find("INPUT[name='email']").val();

        var params = {
            company_id:     company_id,
            quote:          quote,
            tel:            tel,
            email:          email
        };

        sendQuote(params);
    })
})


/* Functions */
function sendQuote(params) {
    $.ajax({
        url: '/api/companies/quote',
        dataType: 'json',
        data: params,
        type: 'post',
        success: function (result) {
            alert(result);
            console.log(result);
        },
        error: function () {
            alert('error');
        }
    });
}