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
