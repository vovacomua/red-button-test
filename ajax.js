function submit_me(){

	var $form = jQuery('#the-form'),
		dt = new Date(),
		data = {
			'action' : 'the_ajax_hook',
			'nonce'  : $form.find('input[name="nonce"]').val(),
			'time'   : Math.floor(dt.getTime() / 1000)
		};

	//alert(  Math.floor(dt.getTime() / 1000) );

	jQuery.post(the_ajax_script.ajaxurl, data, function(response){
		console.log(response);
		jQuery( "#response-area" ).html(response.data.time + " "+ response.data.ip );
		jQuery( "#submit-button" ).removeClass( "red-button-size red-button-enabled" );
		jQuery( "#submit-button" ).addClass( "red-button-size red-button-disabled" );
		jQuery( "#submit-button" ).prop( 'value', 'Thanks' );
		jQuery( "#submit-button" ).prop( 'disabled', true );
		}
	)
	.fail(function(response) {
		jQuery("#response-area").html( 'Error: ' + response.responseText );
	});
}