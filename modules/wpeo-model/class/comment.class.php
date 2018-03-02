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
	class Comment_Class extends Object_Class {
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
		protected $type = 'ping';

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
		 * Définition des fonctions de callback.
		 *
		 * @var array
		 */
		protected $built_in_func = array(
			'before_get'     => array(),
			'before_put'     => array(),
			'before_post'    => array(),
			'after_get'      => array(),
			'after_get_meta' => array(),
			'after_put'      => array( 'eoxia\after_put_comments' ),
			'after_post'     => array( 'eoxia\after_put_comments' ),
		);

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
			$array_comments = array();

			if ( ! empty( $this->type ) ) {
				$args['status'] = '-34070';
				$args['type']   = $this->get_type();
			}

			if ( empty( $args['status'] ) && ! empty( $this->status ) ) {
				$args['status'] = $this->status;
			}

			// Si le paramètre "id" est passé on le transforme en "ID" qui est le paramètre attendu par get_comments.
			// Dans un souci d'homogénéité du code, le paramètre "id" remplace "ID".
			if ( isset( $args['id'] ) ) {
				$args['ID'] = $args['id'];
				unset( $args['id'] );
			}

			// Si l'argument "schema" est présent c'est lui qui prend le dessus et ne va pas récupérer d'élément dans la base de données.
			if ( isset( $args['schema'] ) ) {
				$array_comments[] = array();
			} else { // On lance la requête pour récupèrer les "comments" demandés.
				$args = Model_Util::exec_callback( $this->callback_func['before_get'], $args );

				$array_comments = get_comments( $args );
			}

			// Traitement de la liste des résultats pour le retour.
			$array_comments = $this->prepare_items_for_response( $array_comments, 'get_comment_meta', $this->meta_key, 'comment_ID' );

			// Si on a demandé qu'une seule entrée et qu'il n'y a bien qu'une seule entrée correspondant à la demande alors on ne retourne que cette entrée.
			if ( true === $single && 1 === count( $array_comments ) ) {
				$array_comments = $array_comments[0];
			}

			return $array_comments;
		}

		/**
		 * Insère ou met à jour les données dans la base de données.
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @param  Array $data Les données a insérer ou à mettre à jour.
		 */
		public function update( $data ) {
			$model_name = $this->model_name;
			$data       = (array) $data;
			$req_method = ( ! empty( $data['id'] ) ) ? 'put' : 'post';
			$args_cb    = array(
				'model_name' => $model_name,
				'req_method' => $req_method,
				'meta_key'   => $this->meta_key,
			);

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

			$data            = Model_Util::exec_callback( $this->callback_func[ 'before_' . $req_method ], $data, $args_cb );
			$args_cb['data'] = $data;

			$object = new $model_name( $data, $req_method );

			if ( empty( $object->data['id'] ) ) {
				add_filter( 'duplicate_comment_id', '__return_false' );
				add_filter( 'pre_comment_approved', function( $approved, $comment_data ) {
					return $comment_data['comment_approved'];
				}, 10, 2 );
				$inserted_comment = wp_insert_comment( $object->convert_to_wordpress() );
				if ( is_wp_error( $inserted_comment ) ) {
					return $inserted_comment;
				}

				$object->data['id'] = $inserted_comment;
			} else {
				wp_update_comment( $object->convert_to_wordpress() );
			}

			$object = Model_Util::exec_callback( $this->callback_func[ 'after_' . $req_method ], $object, $args_cb );

			return $object;
		}

	}
} // End if().
