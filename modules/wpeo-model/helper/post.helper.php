<?php
/**
 * Helper globaux sur les post dans EO_Framework
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.0.0
 * @copyright 2015-2018
 * @package EO_Framework
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'eoxia\after_get_post' ) ) {
	/**
	 * Remplis les champs de type 'wpeo_date'.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param  string $current_time Le date envoyé par l'objet.
	 * @return
	 */
	function after_get_post( $object, $args ) {
		if ( ! empty( $object->data['id'] ) ) {
			$model = $object->get_model();
			if ( ! empty( $model['taxonomy']['child'] ) ) {
				foreach ( $model['taxonomy']['child'] as $key => $value ) {
					$object->data['taxonomy'][ $key ] = wp_get_object_terms( $object->data['id'], $key, array(
						'fields' => 'ids',
					) );
				}
			}
		}

		return $object;
	}
}
