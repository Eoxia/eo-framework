<?php
/**
 * All methods utils for associate, dessociate and anothers things about upload.
 *
 * @author Eoxia
 * @since 0.1.0-alpha
 * @version 1.0.0
 * @copyright 2017
 * @package WPEO-Upload
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\eoxia\WPEO_Upload_Class' ) ) {

	/**
	 * All methods utils for associate, dessociate and anothers things about upload.
	 */
	class WPEO_Upload_Class extends \eoxia\Singleton_Util {

		/**
		 * Need to be declared for Singleton_Util.
		 *
		 * @since 0.1.0-alpha
		 * @version 0.1.0-alpha
		 */
		protected function construct() {}

		/**
		 * Associate the file_id in the Object.
		 *
		 * @param  integer $id        Object ID.
		 * @param  integer $file_id   File ID.
		 * @param  string $model_name The namespace and model name.
		 * @param  string $field_name The field name in the modeL.
		 *
		 * @return void
		 *
		 * @since 0.1.0-alpha
		 * @version 1.0.0
		 */
		public function associate_file( $id, $file_id, $model_name, $field_name ) {
			$element = null;

			if ( ! empty( $id ) ) {
				$element = $model_name::g()->get( array(
					'id' => $id,
				), true );

				$element->associated_document_id[ $field_name ][] = (int) $file_id;
				$model_name::g()->update( $element );
			}

			return $element;
		}

		/**
		 * Dessociate the file_id in the Object.
		 *
		 * @param  integer $id        Object ID.
		 * @param  integer $file_id   File ID.
		 * @param  string $model_name The namespace and model name.
		 * @param  string $field_name The field name in the modeL.
		 *
		 * @return void
		 *
		 * @since 0.1.0-alpha
		 * @version 1.0.0
		 */
		public function dissociate_file( $id, $file_id, $model_name, $field_name ) {
			$element = $model_name::g()->get( array(
				'id' => $id,
			), true );

			// Check if the file is in associated file list.
			if ( isset( $element->associated_document_id ) && isset( $element->associated_document_id[ $field_name ] ) ) {
				$key = array_search( $file_id, $element->associated_document_id[ $field_name ], true );
				if ( false !== $key ) {
					array_splice( $element->associated_document_id[ $field_name ], $key, 1 );
				}
			}

			// Check if the file is set as thumbnail.
			if ( $file_id === $element->thumbnail_id ) {
				$element->thumbnail_id = 0;
			}

			$model_name::g()->update( $element );

			return $element;
		}

		/**
		 * Load and display the gallery.
		 *
		 * @param  integer $id        Object ID.
		 * @param  string $model_name The namespace and model name.
		 * @param  string $field_name The field name in the modeL.
		 * @param  bool $single .
		 *
		 * @return void
		 *
		 * @since 0.1.0-alpha
		 * @version 1.0.0
		 */
		public function display_gallery( $id, $model_name, $field_name, $size = 'thumbnail', $single = false, $mime_type = '', $custom_class = '' ) {
			$element = $model_name::g()->get( array(
				'id' => $id,
			), true );

			$main_picture_id = $element->thumbnail_id;

			$list_id = ! empty( $element->associated_document_id[ $field_name ] ) ? $element->associated_document_id[ $field_name ] : array();

			require( \eoxia\Config_Util::$init['eo-framework']->wpeo_upload->path . '/view/gallery/gallery.view.php' );
		}

		/**
		 * Set the thumbnail.
		 *
		 * @param  integer $id        Object ID.
		 * @param  integer $file_id   File ID.
		 * @param  string $model_name The namespace and model name.
		 *
		 * @return void
		 *
		 * @since 0.1.0-alpha
		 * @version 1.0.0
		 */
		public function set_thumbnail( $id, $file_id, $model_name ) {
			$element = $model_name::g()->update( array(
				'id' => $id,
				'thumbnail_id' => $file_id,
			) );

			return $element;
		}
	}

	WPEO_Upload_Class::g();
}
