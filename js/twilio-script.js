jQuery(document).ready(function ($) {
     
 $('.twilio_call_number').intlTelInput({
   responsiveDropdown: true,
    autoFormat: true
 });
    
    //guest button
    $('.twilio_call_button').click(function () {
        var button = $(this);
        var agent = $(this).attr('data-agent');

        if ($.trim($('.twilio_call_number').val()) == '') {
            $('.twilio_call_number').focus();
            return false;
        }
        //get user phone
        var user_phone = $.trim($('.twilio_call_number').val());
        user_phone = user_phone.replace(/\s/g, "").replace(/[-]/g, "");

        //loading
        var old_text = button.text();
        button.text('Requesting ...');
        button.attr('disabled', true);
        //remove error message
        $('.twilio_error').remove();
        var data = {
            'action': 'make_the_call_guest',
            'agent': agent,
            'user': user_phone
        };
        $.ajax({
            url: ajax_url,
            data: data,
            type: 'post',
            
            success: function (msg) {
                if (msg == 'Done') {
                    button.text('Call incomnig!');
                } else {
                    button.after('<span class="twilio_error">process failed</span>');
                    button.text(old_text);
                    button.attr('disabled', false);
                }
            }, complete: function () {
                setTimeout(function () {
                    button.text(old_text);
                    button.attr('disabled', false);
                }, 5000);
            }
        });
    });
});
