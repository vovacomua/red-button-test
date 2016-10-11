function submit_me(){
var dt = new Date();
var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
jQuery("#theForm").append('<input type="hidden" name="time" value="'+time+'" /> ');

jQuery.post(the_ajax_script.ajaxurl, jQuery("#theForm").serialize()
,
function(response_from_the_action_function){
jQuery("#response_area").html(response_from_the_action_function);
jQuery("#submit_button").removeClass( "redButtonSize redButtonEnabled" );
jQuery("#submit_button").addClass( "redButtonSize redButtonDisabled" );
jQuery("#submit_button").prop('value', 'Thanks'); 
jQuery("#submit_button").prop('disabled', true);
}
)
.fail(function(response) {
	jQuery("#response_area").html('Error: ' + response.responseText);
});
}