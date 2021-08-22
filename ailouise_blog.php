<?php
/**
 * Plugin Name:     aiLouise Blog
 * Plugin URI:      https://ailouise.com
 * Description:     Empower your Blog with Artificial Intelligence
 * Author:          Gabriel Borges
 * Author URI:      www.sample.com
 * Text Domain:     ailouise_blog
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Ailouise_blog
 */

define('AIL_DIR_URI', plugin_dir_path(__FILE__));
define('AIL_DIR_URL', plugin_dir_url(__FILE__));

 require_once plugin_dir_path( __FILE__ ) . '/src/modules/speak/index.php';
 require_once plugin_dir_path( __FILE__ ) . '/src/modules/admin/index.php';
 require_once plugin_dir_path( __FILE__ ) . '/src/shared/infra/dependencies/index.php';

// Include Admin Side Style and Scripts JS
add_action( 'admin_enqueue_scripts', 'ailb_plugin_enqueue_scripts' );
function ailb_plugin_enqueue_scripts() {
	wp_enqueue_script( 'admin_script', plugin_dir_url(__FILE__) . 'src/assets/js/admin-script.js', array('jquery'), '1.0', true );
	global $post;
	wp_localize_script( 'admin_script', 'Ajax', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'post_id' => isset($post->ID) ? $post->ID : null
	));
}

add_action( 'wp_enqueue_scripts', 'ailb_plugin_enqueue_scripts_user_side');
function ailb_plugin_enqueue_scripts_user_side(){
	wp_enqueue_script( 'fas-icons', 'https://kit.fontawesome.com/54401851b8.js', array(), '1.0', true );
	wp_enqueue_script( 'admin_script', plugin_dir_url(__FILE__) . 'src/assets/js/client-script.js', array('jquery'), '1.0', true );
}


add_action('wp', 'testing_functionalities');
function testing_functionalities(){
	// $ailouise_options = get_option( 'ailouise_option_name' );
	// $ibm_api_url = $ailouise_options['ibm_api_url'];

}
