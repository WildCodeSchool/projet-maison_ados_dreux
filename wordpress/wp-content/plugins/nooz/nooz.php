<?php

/*
Plugin Name: Nooz
Plugin URI: http://www.mightydev.com/nooz/
Description: Simplified press release and media coverage management for business websites.
Author: Mighty Digital
Author URI: http://www.mightydev.com
Version: 0.12.1
*/

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use \MightyDev\Registry;
use \MightyDev\Templating\TwigTemplating;
use MightyDev\WordPress\AdminHelper;
use \MightyDev\WordPress\Settings;
use \MightyDev\WordPress\Updater;
use \MightyDev\WordPress\Plugin\NoozContextualHelp;
use \MightyDev\WordPress\Plugin\NoozCore;
use \MightyDev\WordPress\Plugin\NoozLicense;

function mdnooz_core_activation() {
    delete_option( 'mdnooz_flush_rewrite_rules' );
}

function mdnooz_core_load() {
    require_once( dirname( __FILE__ ) . '/inc/autoload.php' );
    $nooz_core = new NoozCore( __FILE__ );
    $nooz_core->title( 'Nooz' );
    // todo: consider using an option for nooz version
    $nooz_core->version( '0.12.1' );
    $nooz_core->set_admin_helper( new AdminHelper() );
    $nooz_core->set_settings( new Settings() );
    $array_loader = new Twig_Loader_Array( array() );
    $file_loader = new Twig_Loader_Filesystem( array( dirname( __FILE__ ) . '/inc/templates' ) );
    $chain_loader = new Twig_Loader_Chain( array( $array_loader, $file_loader ) );
    $twig = new Twig_Environment( $chain_loader, array( 'autoescape' => false ) );
    $nooz_core->set_templating( new TwigTemplating( $twig, $array_loader ) );
    $nooz_core->register();
    $nooz_help = new NoozContextualHelp( __FILE__ );
    $nooz_help->register();
    Registry::set( 'core', $nooz_core );
    Registry::set( 'file_loader', $file_loader );
    do_action( 'nooz_init' );
}

register_activation_hook( __FILE__, 'mdnooz_core_activation' );
//register_deactivation_hook( __FILE__, 'mdnooz_core_deactivation' );

// plugins_loaded is used to ensure that this code has loaded,
// this is useful when timing core and extension code execution
add_action( 'plugins_loaded', 'mdnooz_core_load', 10 );

// tip: a great debug plugin is "Query Monitor"
// tip: a great plugin to do rewrite troublshooting is "Monkeyman Rewrite Analyzer"

// TODO: this will turn into the core API for nooz
// @since v0.8
function mdnooz() {
    // returns core nooz object
    return Registry::get( 'core' );
}
