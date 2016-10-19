<?php
/**
 * @package red-button-test
 * @version 0.1
 */
/*
Plugin Name: Red Button Test
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: This is test assignment
Author: Vladimir Revenko
Version: 0.1
Author URI: http://vldmr.xyz
*/
 
// add response 
add_action( 'wp_ajax_the_ajax_hook', 'the_action_function' );
add_action( 'wp_ajax_nopriv_the_ajax_hook', 'the_action_function' ); // need this to serve non logged in users

add_action( 'wp_enqueue_scripts', 'myajax_data', 99 );

function myajax_data() {
	wp_enqueue_style( 'red-button-test', plugin_dir_url( __FILE__ ) . 'red-button-test.css',false,'1.1','all' );
	wp_enqueue_script( 'my-ajax-handle', plugin_dir_url( __FILE__ ) . 'ajax.js', array( 'jquery' ) );
	wp_localize_script( 'my-ajax-handle', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
 
function custom_meta_box_markup2() {
	global $post;
 
	$post_id = $post->ID;
	
	$stored_clicks = get_post_meta( $post_id, 'red_button5' , false);

	foreach ($stored_clicks as $stored_click) {
		$client_time = gmdate( 'H:i:s' , $stored_click["time"] );

		echo '<span> <b>'. esc_attr($client_time) .'</b> :: '. esc_attr($stored_click["client_IP"]) . ' </span>' ;
	}
}

function add_custom_meta_box2() {
    add_meta_box( "demo-meta-box2", "Red Button Clicks 2", "custom_meta_box_markup2", "post", "side", "high", null );
}

add_action( "add_meta_boxes", "add_custom_meta_box2" );
 
 // THE FUNCTION
function the_action_function() {

	if ( empty( $_POST['nonce'] ) || empty( $_POST['time'] ) ) {
		die ( 'Error!');
	}

	$nonce = $_POST['nonce'];

	if ( ! wp_verify_nonce( $nonce, 'myajax-nonce' ) ) {
		die ( 'Error!');
	}

	$time = sanitize_text_field( $_POST['time'] );

	if (! is_timestamp($time)) {
		die ( 'Error!');
	}

	$referer = wp_get_referer();
	$client_post_ID = url_to_postid( $referer );

	$client_IP = $_SERVER['REMOTE_ADDR'];

	$client_info = array(
		'time' => $time,
		'client_IP' => $client_IP,
	);

	$result = add_post_meta( $client_post_ID, 'red_button5', $client_info );

	$server_time = date( 'H:i:s' );
	$server_IP = $_SERVER['SERVER_ADDR'];

	$return = array(
		'time'	=> $server_time,
		'ip'	=> $server_IP,
	);

	wp_send_json_success($return);
	//die();
}

function add_red_button( $content ) {
	global $post;

	if( is_single() && ! empty( $GLOBALS['post'] ) ) {
		if ( $GLOBALS['post']->ID == get_the_ID() ) {
			$the_form = '
				 <div class="wrap">
					 <form id="the-form" autocomplete="off">' .
			             wp_nonce_field( 'myajax-nonce', 'nonce' ) . '
						 <input id="post-id" name="post-id" value = "' . $post->ID . '" type="hidden" />
						 <input id="submit-button" class="red-button-size red-button-enabled" value = "Нажать!" type="button" onClick="submit_me();" />
					 </form>
					 <div id="response-area">
					 </div> 
				 </div>';

			$content .= $the_form;
		}
	}
	return $content;
}

add_filter('the_content', 'add_red_button');

function is_timestamp($timestamp)
{
	return ((string) (int) $timestamp === $timestamp)
	       && ($timestamp <= PHP_INT_MAX)
	       && ($timestamp >= ~PHP_INT_MAX);
}
