<?php
/**
 * Actions for wpeo_upload.
 *
 * @author Eoxia
 * @since 0.1.0-alpha
 * @version 1.2.0
 * @copyright 2017
 * @package WPEO-Upload
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\eoxia\WPEO_Upload_Action' ) ) {
	/**
	 * Actions for wpeo_upload.
	 */
	class WPEO_Upload_Action {

		/**
		 * Add actions
		 *
		 * @since 0.1.0-alpha
		 * @version 1.1.0
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_scripts' ) );
			add_action( 'init', array( $this, 'callback_plugins_loaded' ) );

			add_action( 'wp_ajax_eo_upload_associate_file', array( $this, 'callback_associate_file' ) );
			add_action( 'wp_ajax_eo_upload_dissociate_file', array( $this, 'callback_dissociate_file' ) );

			add_action( 'wp_ajax_eo_upload_load_gallery', array( $this, 'callback_load_gallery' ) );
			add_action( 'wp_ajax_eo_upload_set_thumbnail', array( $this, 'callback_set_thumbnail' ) );
		}

		/**
		 * Charges le CSS
		 *
		 * @since 1.1.0
		 * @version 1.1.0
		 */
		public function callback_admin_scripts() {
			wp_enqueue_style( 'wpeo_upload_style', \eoxia\Config_Util::$init['eo-framework']->wpeo_upload->url . '/asset/css/style.css', array() );
		}

		/**
		 * Initialise le fichier MO
		 *
		 * @since 1.1.0
		 * @version 1.1.0
		 */
		public function callback_plugins_loaded() {
			$path = str_replace( str_replace( '\\', '/', WP_PLUGIN_DIR ), '', str_replace( '\\', '/', \eoxia\Config_Util::$init['eo-framework']->wpeo_upload->path ) );
			load_plugin_textdomain( 'wpeo-upload', false, $path . '/asset/language/' );
		}

		/**
		 * Associate a file to an element.
		 *
		 * @since 0.1.0-alpha
		 * @version 1.0.0
		 *
		 * @return void
		 * @todo: nonce
		 */
		public function callback_associate_file() {
			$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
			$file_id = ! empty( $_POST['file_id'] ) ? (int) $_POST['file_id'] : 0;
			$model_name = ! empty( $_POST['model_name'] ) ? stripslashes( sanitize_text_field( $_POST['model_name'] ) ) : '';
			$field_name = ! empty( $_POST['field_name'] ) ? sanitize_text_field( $_POST['field_name'] ) : '';
			$single = ! empty( $_POST['single'] ) ? sanitize_text_field( $_POST['single'] ) : 'false';
			$size = ! empty( $_POST['size'] ) ? sanitize_text_field( $_POST['size'] ) : 'thumbnail';
			$mime_type = ! empty( $_POST['mime_type'] ) ? sanitize_text_field( $_POST['mime_type'] ) : '';
			$display_type = ! empty( $_POST['display_type'] ) ? sanitize_text_field( $_POST['display_type'] ) : '';
			$view = '';

			if ( ! empty( $id ) ) {
				if ( 'true' === $single ) {
					$element = WPEO_Upload_Class::g()->set_thumbnail( $id, $file_id, $model_name );
				} else {
					$element = WPEO_Upload_Class::g()->associate_file( $id, $file_id, $model_name, $field_name );

					if ( empty( $element->thumbnail_id ) ) {
						$element = WPEO_Upload_Class::g()->set_thumbnail( $id, $file_id, $model_name );
					}
				}

				if ( ! empty( $element->id ) ) {
					ob_start();
					do_shortcode( '[wpeo_upload id="' . $element->id . '" model_name="' . str_replace( '\\', '/', $model_name ) . '" field_name="' . $field_name . '" mime_type="' . $mime_type . '" single="' . $single . '" size="' . $size . '" ]' );
					$view = ob_get_clean();
				}
			}

			if ( 'list' === $display_type ) {
				$filelink = get_attached_file( $file_id );
				$filename_only = basename( $filelink );
				$fileurl_only = wp_get_attachment_url( $file_id );
				ob_start();
				require( \eoxia\Config_Util::$init['eo-framework']->wpeo_upload->path . '/view/' . $display_type . '/list-item.view.php' );
				$view = ob_get_clean();
			}

			wp_send_json_success( array(
				'view' => $view,
				'id' => $id,
				'display_type' => $display_type,
				'media' => wp_get_attachment_image( $file_id, $size ),
			) );
		}

		/**
		 * Delete the index founded in the array
		 *
		 * @since 0.1.0-alpha
		 * @version 1.0.0
		 *
		 * @return void
		 * @todo: nonce
		 */
		public function callback_dissociate_file() {
			$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
			$file_id = ! empty( $_POST['file_id'] ) ? (int) $_POST['file_id'] : 0;
			$model_name = ! empty( $_POST['model_name'] ) ? stripslashes( sanitize_text_field( $_POST['model_name'] ) ) : '';
			$field_name = ! empty( $_POST['field_name'] ) ? sanitize_text_field( $_POST['field_name'] ) : '';
			$single = ! empty( $_POST['single'] ) ? sanitize_text_field( $_POST['single'] ) : 'false';
			$size = ! empty( $_POST['size'] ) ? sanitize_text_field( $_POST['size'] ) : 'thumbnail';
			$mime_type = ! empty( $_POST['mime_type'] ) ? sanitize_text_field( $_POST['mime_type'] ) : '';

			$element = WPEO_Upload_Class::g()->dissociate_file( $id, $file_id, $model_name, $field_name );

			ob_start();
			do_shortcode( '[wpeo_upload id="' . $element->id . '" model_name="' . str_replace( '\\', '/', $model_name ) . '" field_name="' . $field_name . '" mime_type="' . $mime_type . '" single="' . $single . '" size="' . $size . '" ]' );
			wp_send_json_success( array(
				'namespace' => '',
				'module' => 'gallery',
				'callback_success' => 'dissociatedFileSuccess',
				'view' => ob_get_clean(),
				'id' => $id,
				'close_popup' => ! empty( $element->$field_name ) ? false : true,
			)	);
		}

		/**
		 * Load all image and return the display gallery view.
		 *
		 * @since 0.1.0-alpha
		 * @version 1.0.0
		 *
		 * @return void
		 * @todo: nonce
		 */
		public function callback_load_gallery() {
			$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
			$file_id = ! empty( $_POST['file_id'] ) ? (int) $_POST['file_id'] : 0;
			$model_name = ! empty( $_POST['model_name'] ) ? stripslashes( sanitize_text_field( $_POST['model_name'] ) ) : '';
			$field_name = ! empty( $_POST['field_name'] ) ? sanitize_text_field( $_POST['field_name'] ) : '';
			$single = ! empty( $_POST['single'] ) ? sanitize_text_field( $_POST['single'] ) : 'false';
			$size = ! empty( $_POST['size'] ) ? sanitize_text_field( $_POST['size'] ) : 'thumbnail';
			$mime_type = ! empty( $_POST['mime_type'] ) ? sanitize_text_field( $_POST['mime_type'] ) : '';
			$custom_class = ! empty( $_POST['custom_class'] ) ? sanitize_text_field( $_POST['custom_class'] ) : '';

			ob_start();
			WPEO_Upload_Class::g()->display_gallery( $id, $model_name, $field_name, $size, $single, $mime_type, $custom_class );
			wp_send_json_success( array(
				'view' => ob_get_clean(),
			) );
		}

		/**
		 * Update the thumbnail of an element.
		 * The thumbnail ID is not used. The thumbnail of an element is the first index of the array $field_name.
		 *
		 * @since 0.1.0-alpha
		 * @version 1.0.0
		 *
		 * @return void
		 * @todo: nonce
		 */
		public function callback_set_thumbnail() {
			$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
			$file_id = ! empty( $_POST['file_id'] ) ? (int) $_POST['file_id'] : 0;
			$model_name = ! empty( $_POST['model_name'] ) ? stripslashes( sanitize_text_field( $_POST['model_name'] ) ) : '';
			$field_name = ! empty( $_POST['field_name'] ) ? sanitize_text_field( $_POST['field_name'] ) : '';
			$single = ! empty( $_POST['single'] ) ? sanitize_text_field( $_POST['single'] ) : 'false';
			$size = ! empty( $_POST['size'] ) ? sanitize_text_field( $_POST['size'] ) : 'thumbnail';
			$mime_type = ! empty( $_POST['mime_type'] ) ? sanitize_text_field( $_POST['mime_type'] ) : '';

			$element = WPEO_Upload_Class::g()->set_thumbnail( $id, $file_id, $model_name );

			ob_start();
			do_shortcode( '[wpeo_upload id="' . $element->id . '" model_name="' . str_replace( '\\', '/', $model_name ) . '" field_name="' . $field_name . '" mime_type="' . $mime_type . '" single="' . $single . '" size="' . $size . '" ]' );
			wp_send_json_success( array(
				'namespace' => '',
				'module' => 'gallery',
				'callback_success' => 'successfulSetThumbnail',
				'view' => ob_get_clean(),
				'id' => $id,
			) );
		}
	}

	new WPEO_Upload_Action();
}
