<?php
/**
 * Gestion des utilisateurs (POST, PUT, GET, DELETE)
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 0.1.0
 * @version 1.0.0
 * @copyright 2015-2018
 * @package EO_Framework
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\eoxia\User_Class' ) ) {
	/**
	 * Gestion des utilisateurs (POST, PUT, GET, DELETE)
	 */
	class User_Class extends Object_Class {
		/**
		 * Le nom du modèle
		 *
		 * @var string
		 */
		protected $model_name = '\eoxia\User_Model';

		/**
		 * La clé principale pour post_meta
		 *
		 * @var string
		 */
		protected $meta_key = '_wpeo_user';

		/**
		 * Utiles pour récupérer la clé unique
		 *
		 * @todo Rien à faire ici
		 * @todo Expliquer la documentation
		 *
		 * @var string
		 */
		protected $identifier_helper = 'user';

		/**
		 * User element type
		 *
		 * @var string
		 */
		protected $type = 'user';

		/**
		 * La liste des droits a avoir pour accèder aux différentes méthodes
		 *
		 * @var array
		 */
		protected $capabilities = array(
			'get'    => 'list_users',
			'put'    => 'edit_users',
			'post'   => 'edit_users',
			'delete' => 'delete_users',
		);

		/**
		 * Utiles pour DigiRisk
		 *
		 * @todo Rien à faire ici
		 * @var string
		 */
		public $element_prefix = 'U';

		/**
		 * Fonction de callback après avoir récupérer les données dans la base de donnée en mode GET.
		 *
		 * @var array
		 */
		protected $after_get_function = array( '\eoxia\build_user_initial' );

		/**
		 * Fonction de callback avant d'insérer les données en mode POST.
		 *
		 * @var array
		 */
		protected $before_post_function = array();

		/**
		 * Fonction de callback avant de dispatcher les données en mode POST.
		 *
		 * @var array
		 */
		protected $before_model_post_function = array();

		/**
		 * Fonction de callback après avoir inséré les données en mode POST.
		 *
		 * @var array
		 */
		protected $after_post_function = array();

		/**
		 * Fonction de callback avant de mêttre à jour les données en mode PUT.
		 *
		 * @var array
		 */
		protected $before_put_function = array();

		/**
		 * Fonction de callback avant de dispatcher les données en mode PUT.
		 *
		 * @var array
		 */
		protected $before_model_put_function = array();

		/**
		 * Fonction de callback après avoir mis à jour les données en mode PUT.
		 *
		 * @var array
		 */
		protected $after_put_function = array();

		/**
		 * Slug de base pour la route dans l'api rest
		 *
		 * @var string
		 */
		protected $base = 'user';

		/**
		 * Récupères les données selon le modèle définis.
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @param array   $args Les paramètres de WP_User_Query @see https://codex.wordpress.org/Class_Reference/WP_User_Query.
		 * @param boolean $single Si on veut récupérer un tableau, ou qu'une seule entrée.
		 *
		 * @return Comment_Model
		 */
		public function get( $args = array(), $single = false ) {
			$array_users = array();
			$model_name  = $this->model_name;

			if ( ! empty( $args['id'] ) ) {
				if ( ! isset( $args['include'] ) ) {
					$args['include'] = array();
				}
				$args['include'] = array_merge( (array) $args['id'], $args['include'] );
			}

			if ( isset( $args['schema'] ) ) {
				$list_user[] = array();
			} else {
				$list_user = get_users( $args );
			}

			if ( ! empty( $list_user ) ) {
				foreach ( $list_user as $object ) {
					$object = (array) $object;
					if ( ! empty( $object['ID'] ) ) {
						$object = $this->get_metas( 'get_user_meta', $object['ID'] );

						if ( ! empty( $object['data'] ) ) {
							$object = array_merge( $object, (array) $object['data'] );
							unset( $object['data'] );
						}
						if ( ! empty( $object[ $this->meta_key ] ) ) {
							$object = array_merge( $object, json_decode( $object[ $this->meta_key ], true ) );
							unset( $object[ $this->meta_key ] );
						}
					}
					$data          = new $model_name( $object, 'get' );
					$array_users[] = Model_Util::exec_callback( $this->after_get_function, $data, array( 'model_name' => $model_name ) );
				}
			}

			if ( true === $single && 1 === count( $array_users ) ) {
				$array_users = $array_users[0];
			}

			return $array_users;
		}

		/**
		 * Insère ou met à jour les données dans la base de donnée.
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @param  Array $data Les données a insérer ou à mêttre à jour.
		 * @return Object      L'objet construit grâce au modèle.
		 */
		public function update( $data ) {
			$model_name = $this->model_name;
			$data       = (array) $data;
			$req_method = ( ! empty( $data['id'] ) ) ? 'put' : 'post';
			$before_cb  = 'before_' . $req_method . '_function';
			$after_cb   = 'after_' . $req_method . '_function';
			$args_cb    = array( 'model_name' => $model_name );

			if ( 'post' === $req_method ) {
				while ( username_exists( $data['login'] ) ) {
					$data['login'] .= wp_rand( 1000, 9999 );
				}
			}

			$data = Model_Util::exec_callback( $this->$before_cb, $data, $args_cb );

			if ( ! empty( $data['id'] ) ) {
				$current_data = $this->get( array(
					'id' => $data['id'],
				), true );

				$data = Array_Util::g()->recursive_wp_parse_args( $data, $current_data->data );
			}

			$object = new $model_name( $data, $req_method );

			if ( empty( $object->data['id'] ) ) {
				$inserted_user = wp_insert_user( $object->convert_to_wordpress() );
				if ( is_wp_error( $inserted_user ) ) {
					return $inserted_user;
				}

				$object->data['id'] = $inserted_user;
			} else {

				$updated_user = wp_update_user( $object->convert_to_wordpress() );
				if ( is_wp_error( $updated_user ) ) {
					return $updated_user;
				}

				$object->data['id'] = $updated_user;
			}

			Save_Meta_Class::g()->save_meta_data( $object, 'update_user_meta', $this->meta_key );

			$object = Model_Util::exec_callback( $this->$after_cb, $object, $args_cb );

			return $object;
		}

		/**
		 * Supprimes un utilisateur
		 *
		 * @todo: Utile ?
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @param  integer $id L'ID de l'utilisateur.
		 */
		public function delete( $id ) {
			wp_delete_user( $id );
		}

	}
} // End if().
