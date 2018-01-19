<?php
/**
 * Gestion des filtres principaux de eo_model.
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
 * Gestion des filtres principaux de eo_model.
 */
class Data_Filter {

	public function __construct() {
		add_filter( 'eo_model_handle_value', array( $this, 'callback_eo_model_handle_value' ), 10, 3 );
	}

	/**
	 * [callback_eo_model_handle_value description]
	 * @param  [type] $value      [description]
	 * @param  [type] $field_def  [description]
	 * @param  [type] $req_method [description]
	 * @return [type]             [description]
	 */
	public function callback_eo_model_handle_value( $value, $field_def, $req_method ) {
		if ( ( isset( $field_def['context'] ) && in_array( $req_method, $field_def['context'], true ) ) && 'wpeo_date' === $field_def['type'] ) {
			$value = array(
				'raw'      => $value,
				'rendered' => fill_date( $value ),
			);
		}

		return $value;
	}

}

new Data_Filter();
