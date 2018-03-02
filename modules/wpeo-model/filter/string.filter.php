<?php
/**
 * Gestion des filtres globaux concernant les champs de type string dans EO_Framework.
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
 * Gestion des filtres globaux concernant les champs de type string dans EO_Framework.
 */
class String_Filter {

	/**
	 * Initialisation et appel des différents filtres définis dans EO_Framework.
	 */
	public function __construct() {
		add_filter( 'eo_model_handle_value', array( $this, 'callback_eo_model_handle_value' ), 10, 4 );
	}

	/**
	 * Filtre permettant de traiter un champs de type string pour la construction d'un objet.
	 *
	 * @param mixed  $value          La valeur actuelle du champs qu'il faut formater selon le type.
	 * @param mixed  $current_object L'objet actuellement en cours de construction et qu'il faut remplir.
	 * @param array  $field_def      La définition complète du champs. Type / Valeur par défaut / Champs dans la base de données.
	 * @param string $req_method     Méthode HTTP actuellement appelée.
	 *
	 * @return mixed                 La valeur du champs actuellement traité.
	 */
	public function callback_eo_model_handle_value( $value, $current_object, $field_def, $req_method ) {
		// Si la méthode HTTP appelée est la méthode GET alors on enlève les "slash" en trop.
		if ( ( null !== $value ) && ( 'GET' === $req_method ) && ( 'string' === $field_def['type'] ) ) {
			$value = stripslashes( $value );
		}

		return $value;
	}

}

new String_Filter();
