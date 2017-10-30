<?php
/**
 * Call the main view of the plugin.
 *
 * @author Eoxia
 * @since 0.1.0-alpha
 * @version 1.2.0
 * @copyright 2017
 * @package WordPress-Plugin-Base
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\eoxia\WPEO_Upload_Shortcode' ) ) {

	/**
	 * Call the main view of the plugin.
	 */
	class WPEO_Upload_Shortcode {

		/**
		 * Need to be declared for Singleton_Util.
		 *
		 * @since 0.1.0-alpha
		 * @version 0.2.0-alpha
		 */
		public function __construct() {
			add_shortcode( 'wpeo_upload', array( $this, 'wpeo_upload' ) );
		}

		/**
		 * Call the button view
		 *
		 * @param  array $atts See paramaters in func.
		 *
		 * @return void
		 *
		 * @since 0.1.0-alpha
		 * @version 1.2.0
		 */
		public function wpeo_upload( $atts ) {
			$atts = shortcode_atts( array(
				'id' => 0,
				'mode' => 'edit',
				'field_name' => '',
				'model_name' => '\eoxia\Post_Class',
				'custom_class' => '',
				'size' => 'thumbnail',
				'single' => 'true',
				'mime_type' => '',
				'display_type' => 'gallery',
			), $atts );

			if ( ! empty( $atts['model_name'] ) ) {
				$atts['model_name'] = str_replace( '/', '\\', $atts['model_name'] );
			}

			$element = $atts['model_name']::g()->get( array(
				'id' => $atts['id'],
			), true );

			$main_picture_id = $element->thumbnail_id;

			$field_name = $atts['field_name'];

			require( \eoxia\Config_Util::$init['eo-framework']->wpeo_upload->path . '/view/' . $atts['display_type'] . '/button.view.php' );
		}

	}

	new WPEO_Upload_Shortcode();
}
