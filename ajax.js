function submit_me(){
	var dt = new Date();
	var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
	//jQuery("#theForm").append('<input type="hidden" name="time" value="'+time+'" /> ');

	var action  = jQuery('#theForm').find('input[name="action"]').val() ;
	var nonce   = jQuery('#theForm').find('input[name="nonce"]').val() ;
	var postid = jQuery('#theForm').find('input[name="post-id"]').val() ;

	//alert( action );

	var data = {
		'action' : action,
		'nonce'  : nonce,
		'time'   : time
	};

	jQuery.post(the_ajax_script.ajaxurl, data
	,
		function(response){
		console.log(response);
		jQuery( "#response_area" ).html(response.data.time + " "+ response.data.ip );
		jQuery( "#submit_button" ).removeClass( "red-button-size red-button-enabled" );
		jQuery( "#submit_button" ).addClass( "red-button-size red-button-disabled" );
		jQuery( "#submit_button" ).prop( 'value', 'Thanks' ); 
		jQuery( "#submit_button" ).prop( 'disabled', true );
		}
	)
	.fail(function(response) {
		jQuery("#response_area").html( 'Error: ' + response.responseText );
	});
}