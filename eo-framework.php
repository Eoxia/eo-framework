<?php
/**
 * Fichier boot du framework
 *
 * @package EO-Framework
 */

namespace eo_framework;

/**
 * Plugin Name: EO Framework
 * Description:
 * Version: 1.0.0
 * Author: Eoxia <dev@eoxia.com>
 * Author URI: http://www.eoxia.com/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: eo-framework
 * Domain Path: /language
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

DEFINE( 'PLUGIN_EO_FRAMEWORK_PATH', realpath( plugin_dir_path( __FILE__ ) ) . '/' );
DEFINE( 'PLUGIN_EO_FRAMEWORK_URL', plugins_url( basename( __DIR__ ) ) . '/' );
DEFINE( 'PLUGIN_EO_FRAMEWORK_DIR', basename( __DIR__ ) );

require_once( 'core/class/singleton.class.php' );
require_once( 'core/class/init.class.php' );

\eoxia\Init_util::g()->exec( PLUGIN_EO_FRAMEWORK_PATH, basename( __FILE__, '.php' ) );
