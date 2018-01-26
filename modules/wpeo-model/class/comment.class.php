<?php
/**
 * Gestion des commentaires (POST, PUT, GET, DELETE)
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 0.1.0
 * @version 1.0.0
 * @copyright 2015-2018
 * @package EO_Framework
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\eoxia\Comment_Class' ) ) {
	/**
	 * Gestion des commentaires (POST, PUT, GET, DELETE)
	 */
	class Comment_Class extends Rest_Class {
		/**
		 * Le nom du modèle à utiliser.
		 *
		 * @var string
		 */
		protected $model_name = '\eoxia\Comment_Model';

		/**
		 * La clé principale pour enregistrer les meta données.
		 *
		 * @var string
		 */
		protected $meta_key = '_comment';

		/**
		 * Le type du commentaire
		 *
		 * @var string
		 */
		protected $comment_type = 'ping';

		/**
		 * Slug de base pour la route dans l'api rest
		 *
		 * @var string
		 */
		protected $base = 'comment';

		/**
		 * Uniquement utile pour DigiRisk...
		 *
		 * @var string
		 */
		protected $identifier_helper = 'comment';

		/**
		 * La liste des droits a avoir pour accèder aux différentes méthodes
		 *
		 * @var array
		 */
		protected $capabilities = array(
			'get'    => 'read',
			'put'    => 'moderate_comments',
			'post'   => 'moderate_comments',
			'delete' => 'moderate_comments',
		);

		/**
		 * Fonction de callback après avoir récupérer le modèle en mode GET.
		 *
		 * @var array
		 */
		protected $after_get_function = array();

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
		 * Permet de récupérer le schéma avec les données du modèle par défault.
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @return Comment_Model
		 */
		public function get_schema() {
			$model_name = $this->model_name;
			$model = new $model_name( array() );
			return $model->get_model();
		}

		/**
		 * Récupères les données selon le modèle définis.
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @param array   $args Les paramètres de get_comments @https://codex.wordpress.org/Function_Reference/get_comments.
		 * @param boolean $single Si on veut récupérer un tableau, ou qu'une seule entrée.
		 *
		 * @return Comment_Model
		 */
		public function get( $args = array(), $single = false ) {
			$array_model   = array();
			$array_comment = array();

			if ( ! empty( $this->comment_type ) ) {
				$args['status'] = '-34070';
				$args['type']   = $this->get_type();
			}

			if ( empty( $args['status'] ) && ! empty( $this->status ) ) {
				$args['status'] = $this->status;
			}

			if ( isset( $args['id'] ) ) {
				$array_comment[] = get_comment( $args['id'], ARRAY_A );
			} elseif ( isset( $args['schema'] ) ) {
				$array_comment[] = array();
			} else {
				$array_comment = get_comments( $args );
			}

			$list_comment = array();

			if ( ! empty( $array_comment ) ) {
				foreach ( $array_comment as $key => $comment ) {
					$comment = (array) $comment;

					if ( ! empty( $comment['comment_ID'] ) ) {
						$list_meta = get_comment_meta( $comment['comment_ID'] );
						foreach ( $list_meta as &$meta ) {
							$meta = array_shift( $meta );
						}

						$comment = array_merge( $comment, $list_meta );


						if ( ! empty( $comment[ $this->meta_key ] ) ) {
							$comment = array_merge( $comment, json_decode( $comment[ $this->meta_key ], true ) );

							unset( $comment[ $this->meta_key ] );
						}
					}

					$model_name = $this->model_name;
					$list_comment[ $key ] = new $model_name( $comment, 'get' );
					$list_comment[ $key ] = Model_Util::exec_callback( $this->after_get_function, $list_comment[ $key ], array( 'model_name' => $model_name ) );
				}
			} else {
				if ( ! empty( $args['schema'] ) ) {
					$model_name = $this->model_name;
					$list_comment[0] = new $model_name( array(), 'get' );
					$list_comment[0] = Model_Util::exec_callback( $this->after_get_function, $list_comment[0], array( 'model_name' => $model_name ) );
				}
			} // End if().

			if ( true === $single && 1 === count( $list_comment ) ) {
				$list_comment = $list_comment[0];
			}

			return $list_comment;
		}

		/**
		 * Appelle la méthode update.
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @param  Array $data Les données.
		 * @return Array $data Les données
		 */
		public function create( $data ) {
			return $this->update( $data );
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

			// Vérifie l'existence du type.
			if ( empty( $data['type'] ) ) {
				$data['type'] = $this->get_type();
			}

			if ( ! isset( $data['status'] ) ) {
				$data['status'] = '-34070';
			}

			if ( empty( $data['id'] ) ) {
				$user = wp_get_current_user();
				if ( $user->exists() ) {
					if ( empty( $data['author_id'] ) ) {
						$data['author_id'] = $user->ID;
					}

					if ( empty( $data['author_nicename'] ) ) {
						$data['author_nicename'] = $user->display_name;
					}

					if ( empty( $data['author_email'] ) ) {
						$data['author_email'] = $user->user_email;
					}

					if ( empty( $data['author_url'] ) ) {
						$data['author_url'] = $user->user_url;
					}
				}
			}

			$data = Model_Util::exec_callback( $this->$before_cb, $data, $args_cb );

			if ( ! empty( $data['id'] ) ) {
				$current_data = $this->get( array(
					'id' => $data['id'],
				), true );

				$data = Array_Util::g()->recursive_wp_parse_args( $data, (array) $current_data );
			}

			$data = new $model_name( $data, $req_method );

			if ( empty( $data->id ) ) {
				add_filter( 'duplicate_comment_id', '__return_false' );
				add_filter( 'pre_comment_approved', function( $approved, $comment_data ) {
					return $comment_data['comment_approved'];
				}, 10, 2 );
				$inserted_comment = wp_new_comment( $data->convert_to_wordpress(), true );
				if ( is_wp_error( $inserted_comment ) ) {
					return $inserted_comment;
				}

				$data->id = $inserted_comment;
			} else {
				wp_update_comment( $data->convert_to_wordpress() );
			}

			Save_Meta_Class::g()->save_meta_data( $data, 'update_comment_meta', $this->meta_key );

			$data = Model_Util::exec_callback( $this->$after_cb, $data, $args_cb );

			return $data;
		}

		/**
		 * Renvoie le type du commentaire
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @return string Le type du commentaire.
		 */
		public function get_type() {
			return $this->comment_type;
		}

		/**
		 * Utile uniquement pour DigiRisk.
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @return string L'identifiant des commentaires pour DigiRisk.
		 */
		public function get_identifier_helper() {
			return $this->identifier_helper;
		}
	}
} // End if().
