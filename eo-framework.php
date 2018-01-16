<?php
/**
 * Boot file EO Framework
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 0.1.0
 * @version 1.0.0
 * @copyright 2015+
 * @package EO_Framework
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// To avoid conflicts between two plugins using EO Framework.
if ( ! class_exists( '\eoxia\Init_Util' ) ) {
	DEFINE( 'PLUGIN_EO_FRAMEWORK_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
	DEFINE( 'PLUGIN_EO_FRAMEWORK_URL', plugins_url( basename( __DIR__ ) ) . '/' );
	DEFINE( 'PLUGIN_EO_FRAMEWORK_DIR', basename( __DIR__ ) );

	require_once 'core/class/singleton.class.php';
	require_once 'core/class/init.class.php';

	// Autoload, init all modules in EO Framework.
	Init_Util::g()->exec( PLUGIN_EO_FRAMEWORK_PATH, basename( __FILE__, '.php' ) );
}
