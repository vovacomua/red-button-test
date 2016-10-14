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

//add CSS
wp_enqueue_style( 'red-button-test', plugin_dir_url( __FILE__ ) . 'red-button-test.css',false,'1.1','all' );

//add JS
wp_enqueue_script( 'my-ajax-handle', plugin_dir_url( __FILE__ ) . 'ajax.js', array( 'jquery' ) );
wp_localize_script( 'my-ajax-handle', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
 
// add response 
add_action( 'wp_ajax_the_ajax_hook', 'the_action_function' );
add_action( 'wp_ajax_nopriv_the_ajax_hook', 'the_action_function' ); // need this to serve non logged in users
 
function custom_meta_box_markup2()
{
	global $post;
 
	$post_id = $post->ID;
	
	$stored_clicks = get_post_meta( $post_id, 'red_button2' );
	
	if ($stored_clicks){
		foreach( $stored_clicks as $click ){
			$s[] = $click;
		}
		echo implode(', ', $s);	
	}
}

function add_custom_meta_box2()
{
    add_meta_box( "demo-meta-box2", "Red Button Clicks 2", "custom_meta_box_markup2", "post", "side", "high", null );
}

add_action( "add_meta_boxes", "add_custom_meta_box2" );
 
 // THE FUNCTION
function the_action_function(){
	$client_post_ID = $_POST['post-id'];
	$time = $_POST['time'];
	$client_IP = $_SERVER['REMOTE_ADDR'];
	$client_record = $time."::".$client_IP;

	$server_time = date( 'H:i:s' );
	$server_IP = $_SERVER['SERVER_ADDR'];

	$result = add_post_meta( $client_post_ID, 'red_button2', $client_record );

	echo $server_time." ".$server_IP;
	die();
}

//add button
 function add_red_buton( ) {
    global $post;
	global $hook_flag2;
	$hook_flag2 += 1;
	
	if ( $hook_flag2 == 2 && $post->ID ){
		 $the_form = '
		 <div class="comments-area">
			 <form id="theForm" autocomplete="off">
				 <input id="post-id" name="post-id" value = "'.$post->ID.'" type="hidden" />
				 <input name="action" type="hidden" value="the_ajax_hook" />&nbsp; <!-- this puts the action the_ajax_hook into the serialized form -->
				 <input id="submit_button" class="red-button-size red-button-enabled" value = "Нажать!" type="button" onClick="submit_me();" />
			 </form>
			 <div id="response_area">
			 </div> 
		 </div>';
		echo $the_form;
	}
}
add_action( "pre_get_comments", "add_red_buton" );
