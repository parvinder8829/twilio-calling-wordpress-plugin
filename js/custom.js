jQuery(document).ready( function($) {


/////////////////////////////////////////////////
var number = $('#verified_number');
   
   number.keypress(function (e) {

    var key = e.keyCode || e.charCode;

     if( key == 8 || key == 46 )

     {

		var val= $('#verified_number').val();

		val = val.slice(0,-1);

		$('#verified_number').val(val);

		return false;

      }

     //if the letter is not digit then display error and don't type anything

     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) 
	 {

        //display error message
           return false;

     }

   });
////////////////////////////////////////////////
  jQuery('#log_tabs_ul li').click(function(){

  if(jQuery(this).hasClass('active')) {

    //alert(jQuery(this).attr('class'));
    jQuery(this).removeClass('active')
    if(jQuery(this).hasClass('error_logs_li'))
    {
      jQuery('#Outgoing_calls_li').hide();
      jQuery('#'+jQuery(this).attr('class')).show('slow');
      jQuery(this).addClass('active');
    }
    if(jQuery(this).hasClass('Outgoing_calls_li'))
    {
      jQuery('#error_logs_li').hide();
      jQuery('#'+jQuery(this).attr('class')).show('slow');
      jQuery(this).addClass('active');
    }
  }
  else
  {

    if(jQuery(this).hasClass('error_logs_li'))
    {
      jQuery('.Outgoing_calls_li').removeClass('active');
      jQuery('#Outgoing_calls_li').hide();
      jQuery('#'+jQuery(this).attr('class')).show('slow');
      jQuery(this).addClass('active');
    }
    if(jQuery(this).hasClass('Outgoing_calls_li'))
    {
      jQuery('.error_logs_li').removeClass('active');
      jQuery('#error_logs_li').hide();
      jQuery('#'+jQuery(this).attr('class')).show('slow');
      jQuery(this).addClass('active');
    }
    

  }
  //alert(jQuery(this).attr('class'));

});
    $("#generate_app").click( function() {


        $('#loading_image').css('display','block');

        var account_sid = $("#Twilio_account_sid").val();
        var auth_token = $("#Twilio_auth_token").val();
            

        var data = {
            action: 'create_twilio_api',
            account_sid: account_sid,
            auth_token:auth_token
        };
        // the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file

        $.ajax({
          url:the_ajax_script.ajaxurl,
          type:"POST",
          data:data,
          dataType:"json",
          success: function(data){
                if(data['status']==1)
                {

                $('#loading_image').css('display','none');
                    alert(data['message']);
                    $('#Twilio_app_sid').attr('value','');
                     
                    $('#Twilio_app_sid').attr('value',data['app_id']);
                   
                }
                if(data['status']==0)
                {
                   $('#loading_image').css('display','none');
                    alert(data['message']);
                   
                }
          },
          error: function(){
              $('#loading_image').css('display','none');
               alert("Some thing going wrong please try again later.");
             
          }
        });
       
        return false;
    });
});