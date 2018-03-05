<?php
/**
 * Helper globaux sur les terms dans EO_Framework
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

if ( ! function_exists( 'eoxia\after_put_terms' ) ) {
	/**
	 * Execute des actions complémentaire après avoir mis à jour un objet de type "Term"
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param Term_Model $object L'objet qu'il faut "modifier".
	 * @param array      $args   Les paramètres complémentaires permettant de modifier l'objet.
	 *
	 * @return Term_Model L'objet de type Term "modifié" par le helper.
	 */
	function after_put_terms( $object, $args ) {
		// Mise à jour des metas.
		Save_Meta_Class::g()->save_meta_data( $object, 'update_term_meta', $args['meta_key'] );

		return $object;
	}
}

if ( ! function_exists( 'eoxia\after_get_term' ) ) {
	/**
	 * Execute des actions complémentaire après avoir mis à jour un objet de type "Term"
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param Term_Model $object L'objet qu'il faut "modifier".
	 * @param array      $args   Les paramètres complémentaires permettant de modifier l'objet.
	 *
	 * @return Term_Model L'objet de type Term "modifié" par le helper.
	 */
	function after_get_term( $object, $args ) {
		if ( ! empty( $args['args']['post_id'] ) ) {
			$object['post_id'] = $args['args']['post_id'];
		}

		return $object;
	}
}
