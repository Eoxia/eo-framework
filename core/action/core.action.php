<?php
/**
 * Inclusions de wpeo_assets
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.0.0
 * @copyright 2015-2017 Eoxia
 * @package EO-Framework
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inclusions de wpeo_assets
 */
class Core_Action {

	/**
	 * Le constructeur ajoutes les actions WordPress suivantes:
	 * admin_enqueue_scripts (Pour appeller les scripts JS et CSS dans l'admin)
	 * wp_enqueue_script (Pour appeller les scripts JS et CSS dans le frontend)
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'callback_mixed_enqueue_scripts' ), 9 );
		add_action( 'wp_enqueue_scripts', array( $this, 'callback_mixed_enqueue_scripts' ), 9 );
		add_action( 'wp_head', array( $this, 'callback_wp_head' ) );
	}

	/**
	 * Initialise les fichiers JS inclus dans WordPress (jQuery, wp.media et thickbox)
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return void
	 */
	public function callback_mixed_enqueue_scripts() {
		wp_register_script( 'wpeo-assets-scripts', Config_Util::$init['eo-framework']->core->url . 'assets/js/dest/wpeo-assets.js', array( 'jquery' ), \eoxia\Config_Util::$init['eo-framework']->version, false );
		wp_register_script( 'wpeo-assets-fontawesome', Config_Util::$init['eo-framework']->core->url . 'assets/js/dest/font/fontawesome-all.min.js', array( 'jquery' ), \eoxia\Config_Util::$init['eo-framework']->version, false );
		wp_enqueue_script( 'wpeo-assets-datepicker-js', Config_Util::$init['eo-framework']->core->url . 'assets/js/dest/jquery.datetimepicker.full.js', array( 'jquery' ), \eoxia\Config_Util::$init['eo-framework']->version, false );

		wp_enqueue_style( 'wpeo-assets-styles', Config_Util::$init['eo-framework']->core->url . 'assets/css/style.min.css', \eoxia\Config_Util::$init['eo-framework']->version );
		wp_enqueue_style( 'wpeo-assets-datepicker', Config_Util::$init['eo-framework']->core->url . 'assets/css/jquery.datetimepicker.css', array(), \eoxia\Config_Util::$init['eo-framework']->version );

		wp_localize_script( 'wpeo-assets-scripts', 'wpeo_framework', Core_Class::g()->get_localize_script_data() );
		wp_enqueue_script( 'wpeo-assets-scripts' );
		wp_enqueue_script( 'wpeo-assets-fontawesome' );
	}

	/**
	 * @todo 01/02/2018 A commenter / modifier en plus propre.
	 *
	 * @return void
	 */
	public function callback_wp_head() {
		?>
		<script>FontAwesomeConfig = { searchPseudoElements: true };</script>
		<?php
	}
}

new Core_Action();
