<?php
/**
 * Plugin Name: WP Team Showcase and Slider
 * Plugin URI: http://www.wponlinesupport.com/
 * Text Domain: wp-team-showcase-and-slider
 * Domain Path: /languages/
 * Description: Easy to add and display your employees, team members in Grid view, Slider view and in widget. 
 * Author: WP Online Support
 * Version: 1.0.1
 * Author URI: http://www.wponlinesupport.com/
 *
 * @package WordPress
 * @author WP Online Support
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if( !defined( 'WP_TSAS_VERSION' ) ) {
	define( 'WP_TSAS_VERSION', '1.0.1' ); // Version of plugin
}

add_action('plugins_loaded', 'wp_tsas_load_textdomain');
function wp_tsas_load_textdomain() {
	load_plugin_textdomain( 'wp-team-showcase-and-slider', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}

/**
 * Function to get unique number value
 * 
 * @package WP Team Showcase and Slider
 * @since 1.0.1
 */
function wp_tsas_get_unique() {
  static $unique = 0;
  $unique++;

  return $unique;
}

add_action( 'wp_enqueue_scripts','wp_tsas_style_css' );
	function wp_tsas_style_css() {
		
		wp_register_style( 'wp-tas-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css', array(), WP_TSAS_VERSION );
		wp_enqueue_style( 'wp-tas-font-awesome' );
		
		wp_enqueue_style( 'wp_tsas_teamshowcase_css',  plugin_dir_url( __FILE__ ). 'assets/css/teamshowcase-style.css', array(), WP_TSAS_VERSION );
		wp_enqueue_script( 'wp_tsas_teamshowcase_jquery', plugin_dir_url( __FILE__ ) . 'assets/js/slick.min.js', array( 'jquery' ), WP_TSAS_VERSION );
		wp_enqueue_script( 'wp_tsas_teamshowcase_popup', plugin_dir_url( __FILE__ ) . 'assets/js/team-showcase-popup.js', array( 'jquery' ), WP_TSAS_VERSION );
  		wp_enqueue_style( 'wp_tsas_teamshowcase_style',  plugin_dir_url( __FILE__ ) . 'assets/css/slick.css', array(), WP_TSAS_VERSION);
  	
}

require_once( 'includes/wp_tsas_functions.php' );
require_once( 'templates/wp_tsas-template.php' );
require_once( 'templates/wp_tsas-slider-template.php' );
require_once( 'includes/wp_tsas_menu_function.php' );