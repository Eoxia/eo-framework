<?php
/**
 * Gestion des objets ( posts / terms / comments / users )
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

if ( ! class_exists( '\eoxia\Object_Class' ) ) {

	/**
	 * Gestion des posts (POST, PUT, GET, DELETE)
	 */
	class Object_Class extends Rest_Class {

		/**
		 * Le nom du modèle pour l'objet actuel.
		 *
		 * @var string
		 */
		protected $model_name = '';

		/**
		 * Le type de l'objet actuel.
		 *
		 * @var string
		 */
		protected $type = '';

		/**
		 * Le slug de base de l'objet actuel.
		 *
		 * @var string
		 */
		protected $base = '';

		/**
		 * La clé principale pour la méta de l'objet.
		 *
		 * @var string
		 */
		protected $meta_key = '';

		/**
		 * Utiles pour récupérer la clé unique
		 *
		 * @var string
		 */
		protected $identifier_helper = '';

		/**
		 * Utile uniquement pour DigiRisk.
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @return string L'identifiant des commentaires pour DigiRisk.
		 */
		public function get_identifier_helper() {
			return $this->identifier_helper;
		}

		/**
		 * Permet de récupérer le schéma avec les données du modèle par défault.
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @return Object
		 */
		public function get_schema() {
			$model_name = $this->model_name;
			$model      = new $model_name( array() );
			return $model->get_model();
		}

		/**
		 * Permet de changer le modèle en dur.
		 *
		 * @param string $model_name Le nom du modèle.
		 *
		 * @since 1.0.0
		 * @version 1.3.6.0
		 */
		public function set_model( $model_name ) {
			$this->model_name = $model_name;
		}

		/**
		 * Retourne le post type, mettre get_type de partout et supprimer get_post_type
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @return string Le type de l'objet actuel
		 */
		public function get_type() {
			return $this->type;
		}

		/**
		 * Appelle la méthode update.
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @param Array   $data   Les données.
		 * @param Boolean $context Les données.
		 *
		 * @return Array $data Les données
		 */
		public function create( $data, $context = false ) {
			$object = $this->update( $data, $context );

			if ( is_wp_error( $object ) ) {
				return $object;
			}

			$object = $this->get( array(
				'id'          => $object->data['id'],
				'use_context' => $context,
			), true );

			return $object;
		}

		/**
		 * Factorisation de la fonction de construction des objet après un GET.
		 *
		 * @param  array  $object_list       La liste des objets récupérés.
		 * @param  string $id_field          Le champs identifiant principal du type d'objet en cours de construction.
		 * @param  string $get_meta_function Le nom de la fonction permettant de récupèrer les meta données pour le type d'objet en cours de construction.
		 * @param  string $req_method        La méthode HTTP actuellement utilisée pour la construction de l'objet.
		 *
		 * @return array                   [description]
		 */
		public function build_objects( $object_list, $id_field, $get_meta_function, $req_method ) {
			$model_name = $this->model_name;

			foreach ( $object_list as $key => $object ) {
				$object = (array) $object;

				// Si $object[ $id_field ] existe, on récupère les meta.
				if ( ! empty( $object[ $id_field ] ) ) {
					$list_meta = call_user_func( $get_meta_function, $object[ $id_field ] );
					foreach ( $list_meta as &$meta ) {
						$meta = array_shift( $meta );
						$meta = JSON_Util::g()->decode( $meta );
					}

					$object = array_merge( $object, $list_meta );

					if ( ! empty( $object[ $this->meta_key ] ) ) {
						$data_json = JSON_Util::g()->decode( $object[ $this->meta_key ] );
						if ( is_array( $data_json ) ) {
							$object = array_merge( $object, $data_json );
						} else {
							$object[ $this->meta_key ] = $data_json;
						}
						unset( $object[ $this->meta_key ] );
					}
				}

				// Construction de l'objet selon les données reçues.
				// Soit un objet vide si l'argument schema est défini. Soit l'objet avec ses données.
				$object_list[ $key ] = new $model_name( $object, $req_method );

				// On donne la possibilité de lancer des actions sur l'élément actuel une fois qu'il est complément construit.
				$object_list[ $key ] = Model_Util::exec_callback( $this->after_get_function, $object_list[ $key ], array( 'model_name' => $model_name ) );
			} // End foreach().

			return $object_list;
		}

	}

}
