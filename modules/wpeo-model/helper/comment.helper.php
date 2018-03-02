<?php
/**
 * Helper globaux sur les comments dans EO_Framework
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

if ( ! function_exists( 'eoxia\after_put_comments' ) ) {
	/**
	 * Execute des actions complémentaire après avoir mis à jour un objet de type "Comment"
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param Comment_Model $object L'objet qu'il faut "modifier".
	 * @param array         $args   Les paramètres complémentaires permettant de modifier l'objet.
	 *
	 * @return Comment_Model L'objet de type Comment "modifié" par le helper.
	 */
	function after_put_comments( $object, $args ) {
		// Mise à jour des metas.
		Save_Meta_Class::g()->save_meta_data( $object, 'update_comment_meta', $args['meta_key'] );

		return $object;
	}
}
