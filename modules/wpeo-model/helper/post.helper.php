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
	 * Execute des actions complémentaire après avoir récupéré un objet de type "Post"
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param Post_Model $object L'objet qu'il faut "modifier".
	 * @param array      $args   Les paramètres complémentaires permettant de modifier l'objet.
	 *
	 * @return Post_Model L'objet de type Post "modifié" par le helper.
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

if ( ! function_exists( 'eoxia\after_put_posts' ) ) {
	/**
	 * Execute des actions complémentaire après avoir mis à jour un objet de type "Post"
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param Post_Model $object L'objet qu'il faut "modifier".
	 * @param array      $args   Les paramètres complémentaires permettant de modifier l'objet.
	 *
	 * @return Post_Model L'objet de type Post "modifié" par le helper.
	 */
	function after_put_posts( $object, $args ) {
		// Enregistrement des taxonomies pour l'objet venant d'être enregistré.
		if ( ! empty( $object->data['taxonomy'] ) ) {
			foreach ( $object->data['taxonomy'] as $taxonomy_name => $taxonomy_data ) {
				if ( ! empty( $taxonomy_name ) ) {
					if ( is_int( $taxonomy_data ) || is_array( $taxonomy_data ) ) {
						wp_set_object_terms( $object->data['id'], $taxonomy_data, $taxonomy_name, $args['append_taxonomies'] );
					}
				}
			}
		}

		// Si on envoi date_modified a notre objet, on modifie en "dur" car bloqué par WordPress de base.
		if ( ! empty( $args['data'] ) && empty( $args['data']['date_modified'] ) && ! empty( $args['data']['date_modified'] ) ) {
			$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, array( 'date_modified' => $args['data']['date_modified'] ) );
		}

		// Mise à jour des metas.
		Save_Meta_Class::g()->save_meta_data( $object, 'update_post_meta', $args['meta_key'] );

		return $object;
	}
}
