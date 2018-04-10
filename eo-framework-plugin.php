<?php
/**
 * Plugin Name: EO Framework Dev Plugin
 * Description: Quick and easy to use, manage all your tasks and your time with the Task Manager plugin.
 * Version: 0.0.1
 * Author: Eoxia <dev@eoxia.com>
 * Author URI: http://www.eoxia.com/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Eoxia/MU-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// On plugin load change order in order to load WPShop before current plugin.
add_action( 'plugins_loaded', function() {
	$plugins         = get_option( 'active_plugins' );
	$sub_plugin_path = str_replace( str_replace( '/', '\\', WP_PLUGIN_DIR ) . '\\', '', dirname( __FILE__ ) ) . '/' . basename( __FILE__ );

	foreach ( $plugins as $key => $value ) {
		if ( strpos( $value, basename( __FILE__ ) ) ) {
			$sub_plugin_key  = $key;
			$sub_plugin_path = $value;
		}
	}

	if ( 0 !== $sub_plugin_key ) {
		array_splice( $plugins, $sub_plugin_key, 1 );
		$plugins = array_merge( array( $sub_plugin_path ), $plugins );
		update_option( 'active_plugins', $plugins );
	}
} );

DEFINE( 'PLUGIN_EO_FRAMEWORK_PLUGIN_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
DEFINE( 'PLUGIN_EO_FRAMEWORK_PLUGIN_URL', plugins_url( basename( __DIR__ ) ) . '/' );
DEFINE( 'PLUGIN_EO_FRAMEWORK_PLUGIN_DIR', basename( __DIR__ ) );

require_once 'eo-framework/eo-framework.php';

\eoxia\Init_Util::g()->exec( PLUGIN_EO_FRAMEWORK_PLUGIN_PATH, basename( __FILE__, '.php' ) );
