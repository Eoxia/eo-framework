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

		protected $built_in_func = array(
			'before_get'  => array(),
			'before_put'  => array(),
			'before_post' => array(),
			'after_get'   => array(),
			'after_put'   => array(),
			'after_post'  => array(),
		);

		protected $callback_func = array(
			'before_get'  => array(),
			'before_put'  => array(),
			'before_post' => array(),
			'after_get'   => array(),
			'after_put'   => array(),
			'after_post'  => array(),
		);

		/**
		 * Appelle l'action "init" de WordPress
		 *
		 * @return void
		 */
		protected function construct() {
			parent::construct();

			$this->callback_func = array_merge_recursive( $this->built_in_func, $this->callback_func );
		}

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
			$object = $this->update( $data, true );

			if ( is_wp_error( $object ) ) {
				return $object;
			}

			$object = $this->get( array(
				'id' => $object->data['id'],
			), true );

			return $object;
		}

		public function prepare_item_meta_for_response( $function, $id, $meta_key ) {
			$list_meta = call_user_func( $function, $id, $meta_key );
			foreach ( $list_meta as &$meta ) {
				$meta = array_shift( $meta );
				$meta = JSON_Util::g()->decode( $meta );
			}

			$object = array_merge( $object, $list_meta );

			if ( ! empty( $object[ $meta_key ] ) ) {
				$data_json = JSON_Util::g()->decode( $object[ $meta_key ] );
				if ( is_array( $data_json ) ) {
					$object = array_merge( $object, $data_json );
				} else {
					$object[ $meta_key ] = $data_json;
				}
				unset( $object[ $meta_key ] );
			}

			return $object;
		}
	}

}
