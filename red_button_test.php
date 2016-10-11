<?php
/**
 * @package red_button_test
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

//add JS
 wp_enqueue_script( 'my-ajax-handle', plugin_dir_url( __FILE__ ) . 'ajax.js', array( 'jquery' ) );
 wp_localize_script( 'my-ajax-handle', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
 
 // add response 
 add_action( 'wp_ajax_the_ajax_hook', 'the_action_function' );
 add_action( 'wp_ajax_nopriv_the_ajax_hook', 'the_action_function' ); // need this to serve non logged in users
 



 
 // THE FUNCTION
 function the_action_function(){

 $clientPostID = $_POST['name'];
 $clientIP = $_SERVER['REMOTE_ADDR'];
 $clientRecord = $clientPostID."::".$clientIP;
 
 $serverTime = date('H:i:s');
 $serverIP = $_SERVER['SERVER_ADDR'];
 
 $result = add_post_meta( $clientPostID, 'red_button', $clientRecord );
 
 echo $serverTime." ".$serverIP." ".$result;
 die();
 }

//add button
 function add_red_buton( ) {
    global $post;
	global $hook_flag2;
	$hook_flag2 += 1;
	
	if ($hook_flag2 == 2 && $post->ID){
		 $the_form = '
		 <div class="comments-area">
		 <form id="theForm">
		 <input id="name" name="name" value = "'.$post->ID.'" type="hidden" />
		 <input name="action" type="hidden" value="the_ajax_hook" />&nbsp; <!-- this puts the action the_ajax_hook into the serialized form -->
		 <input id="submit_button" value = "Click This" type="button" onClick="submit_me();" />
		 </form>
		 <div id="response_area">
		 This is where we\'ll get the response
		 </div> </div>';
	echo $the_form;
	}
}
add_action( "pre_get_comments", "add_red_buton" );

?>
