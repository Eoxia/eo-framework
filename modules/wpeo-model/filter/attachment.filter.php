<?php
/**
 * Gestion des filtres pour les attachments.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.0.0
 * @copyright 2015-2018 Eoxia
 * @package EO_Framework
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des filtres pour les attachments.
 */
class Attachment_Filter {

	public function __construct() {
		add_filter( 'eo_model_handle_value', array( $this, 'callback_eo_model_handle_value' ), 10, 4 );
	}

	/**
	 * [callback_eo_model_handle_value description]
	 * @param  [type] $value      [description]
	 * @param  [type] $field_def  [description]
	 * @param  [type] $req_method [description]
	 * @return [type]             [description]
	 */
	public function callback_eo_model_handle_value( $value, $current_object, $field_def, $req_method ) {
		if ( ( isset( $field_def['context'] ) && in_array( $req_method, $field_def['context'], true ) ) && 'post_mime_type' === $field_def['field'] ) {

			if ( isset( $current_object->guid ) ) {
				$value = wp_check_filetype( $current_object->guid );
			}
		}

		return $value;
	}

}

new Attachment_Filter();
