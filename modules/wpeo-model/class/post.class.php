<?php
/**
 * Gestion des posts (POST, PUT, GET, DELETE)
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

if ( ! class_exists( '\eoxia\Post_Class' ) ) {

	/**
	 * Gestion des posts (POST, PUT, GET, DELETE)
	 */
	class Post_Class extends Object_Class {

		/**
		 * Le nom du modèle
		 *
		 * @var string
		 */
		protected $model_name = '\eoxia\Post_Model';

		/**
		 * Le type du post
		 *
		 * @var string
		 */
		protected $type = 'post';

		/**
		 * Le type du post
		 *
		 * @var string
		 */
		protected $base = 'post';

		/**
		 * La clé principale pour post_meta
		 *
		 * @var string
		 */
		protected $meta_key = '_wpeo_post';

		/**
		 * Le nom pour le resgister post type
		 *
		 * @var string
		 */
		protected $post_type_name = 'posts';

		/**
		 * Utiles pour récupérer la clé unique
		 *
		 * @todo Rien à faire ici
		 * @var string
		 */
		protected $identifier_helper = 'post';

		/**
		 * La liste des droits a avoir pour accèder aux différentes méthodes
		 *
		 * @var array
		 */
		protected $capabilities = array(
			'get'    => 'read',
			'put'    => 'edit_posts',
			'post'   => 'edit_posts',
			'delete' => 'delete_posts',
		);

		protected $built_in_func = array(
			'before_get'  => array(),
			'before_put'  => array(),
			'before_post' => array(),
			'after_get'   => array( '\eoxia\after_get_post' ),
			'after_put'   => array( '\eoxia\after_put_posts' ),
			'after_post'  => array( '\eoxia\after_put_posts' ),
		);

		/**
		 * Appelle l'action "init" de WordPress
		 *
		 * @return void
		 */
		protected function construct() {
			parent::construct();

			add_action( 'init', array( $this, 'init_post_type' ) );
		}

		/**
		 * Initialise le post type selon $name et $name_singular.
		 * Initialise la taxonomy si elle existe.
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @see register_post_type
		 * @return boolean
		 */
		public function init_post_type() {
			$args = array(
				'label' => $this->post_type_name,
			);

			$return = register_post_type( $this->get_type(), $args );

			if ( ! empty( $this->attached_taxonomy_type ) ) {
				register_taxonomy( $this->attached_taxonomy_type, $this->get_type() );
			}

			return $return;
		}

		/**
		 * Récupères les données selon le modèle défini.
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @param array   $args   Les paramètres à appliquer pour la récupération @see https://codex.wordpress.org/Function_Reference/WP_Query.
		 * @param boolean $single Si on veut récupérer un tableau, ou qu'une seule entrée.
		 *
		 * @return Object
		 */
		public function get( $args = array(), $single = false ) {
			$array_posts = array();

			// Doit on utiliser le contexte?
			// Dans le cas d'une mise à jour "partielle" (ou on envoi pas toutes les données).
			$use_context = ( ! isset( $args['use_context'] ) || ( ! empty( $args['use_context'] ) && $args['use_context'] ) ) ? true : false;

			// La méthode HTTP de base est le "GET" (on est dans la méthode get).
			// Si use_context est à false on ne va pas utiliser la méthode GET, ce qui permet de ne pas écraser des données à l'enregistrement.
			$req_method = $use_context ? 'get' : null;

			// Définition des arguments par défaut pour la récupération des "posts".
			$default_args = array(
				'post_status'    => 'any',
				'post_type'      => $this->get_type(),
				'posts_per_page' => -1,
			);

			// Si le paramètre "id" est passé on le transforme en "post__in" pour eviter les problèmes de statuts.
			// Dans un soucis d'homogénéité du code, le paramètre "id" remplace le paramètre "p" qui est de base dans WP_Query.
			if ( isset( $args['id'] ) ) {
				if ( ! isset( $args['post__in'] ) ) {
					$args['post__in'] = array();
				}
				$args['post__in'] = array_merge( (array) $args['id'], $args['post__in'] );
				unset( $args['id'] );
			}

			// Si l'argument "schema" est présent c'est lui qui prend le dessus et ne va pas récupérer d'élément dans la base de données.
			if ( isset( $args['schema'] ) ) {
				$array_posts[] = array();
			} else { // On lance la requête pour récupèrer les "posts" demandés.
				$args_cb    = array(
					'args'         => $args,
					'default_args' => $default_args,
				);
				$final_args = Model_Util::exec_callback( $this->callback_func['before_get'], wp_parse_args( $args, $default_args ), $args_cb );

				$query_posts = new \WP_Query( $final_args );
				$array_posts = $query_posts->posts;
				unset( $query_posts->posts );
			}

			$array_posts = $this->prepare_items_for_response( $array_posts, 'ID', 'get_post_meta', $req_method );

			// Si on a demandé qu'une seule entrée et qu'il n'y a bien qu'une seule entrée correspondant à la demande alors on ne retourne que cette entrée.
			if ( true === $single && 1 === count( $array_posts ) ) {
				$array_posts = $array_posts[0];
			}

			return $array_posts;
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
		public function prepare_items_for_response( $object_list, $id_field, $get_meta_function, $req_method ) {
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
				$object_list[ $key ] = Model_Util::exec_callback( $this->callback_func['after_get'], $object_list[ $key ], array( 'model_name' => $model_name ) );
			} // End foreach().

			return $object_list;
		}

		/**
		 * Insère ou met à jour les données dans la base de donnée.
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @param Array   $data    Les données a insérer ou à mêttre à jour.
		 * @param Boolean $context Les données.
		 *
		 * @return Object      L'objet construit grâce au modèle.
		 */
		public function update( $data, $context = false ) {
			$model_name = $this->model_name;
			$data       = (array) $data;
			$req_method = ( ! empty( $data['id'] ) ) ? 'put' : 'post';
			$args_cb    = array(
				'model_name' => $model_name,
				'req_method' => $req_method,
				'meta_key'   => $this->meta_key,
			);

			if ( empty( $data['type'] ) ) {
				$data['type'] = $this->get_type();
			}

			$data = Model_Util::exec_callback( $this->callback_func[ 'before_' . $req_method ], $data, $args_cb );

			// if ( $context && ! empty( $data['id'] ) ) {
			// 	$current_data = $this->get( array(
			// 		'id'          => $data['id'],
			// 		'use_context' => $context,
			// 	), true );
			//
			// 	$data = Array_Util::g()->recursive_wp_parse_args( $data, $current_data->data );
			// }
			$args_cb['data'] = $data;

			$append = false;
			if ( isset( $data['$push'] ) ) {
				if ( ! empty( $data['$push'] ) ) {
					foreach ( $data['$push'] as $field_name => $field_to_push ) {
						if ( ! empty( $field_to_push ) ) {
							foreach ( $field_to_push as $sub_field_name => $value ) {
								if ( ! isset( $data[ $field_name ][ $sub_field_name ] ) ) {
									$data[ $field_name ][ $sub_field_name ] = array();
								}

								$data[ $field_name ][ $sub_field_name ][] = $value;
							}
						}
					}
				}

				$append = true;
				unset( $data['$push'] );
			}
			$args_cb['append_taxonomies'] = $append;

			$object = new $model_name( $data, $req_method );
echo "<pre>"; print_r($object); echo "</pre>";exit;
			if ( empty( $object->data['id'] ) ) {
				$post_save_result = wp_insert_post( $object->convert_to_wordpress(), true );

				$object->data['id'] = $post_save_result;
			} else {
				$post_save_result = wp_update_post( $object->convert_to_wordpress(), true );
			}

			// Une erreur est survenue à la sauvegarden on retourne l'erreur.
			if ( is_wp_error( $post_save_result ) ) {
				return $post_save_result;
			}

			$object = Model_Util::exec_callback( $this->callback_func[ 'after_' . $req_method ], $object, $args_cb );

			return $object;
		}

		/**
		 * Recherche dans les meta value.
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @param string $search Le terme de la recherche.
		 * @param array  $array  La définition de la recherche.
		 *
		 * @return array
		 */
		public function search( $search, $array ) {
			global $wpdb;

			if ( empty( $array ) || ! is_array( $array ) ) {
				return array();
			}

			$where = ' AND ( ';
			if ( ! empty( $array ) ) {
				foreach ( $array as $key => $element ) {
					if ( is_array( $element ) ) {
						foreach ( $element as $sub_element ) {
							$where .= ' AND ( ' === $where  ? '' : ' OR ';
							$where .= ' (PM.meta_key="' . $sub_element . '" AND PM.meta_value LIKE "%' . $search . '%") ';
						}
					} else {
						$where .= ' AND ( ' === $where ? '' : ' OR ';
						$where .= ' P.' . $element . ' LIKE "%' . $search . '%" ';
					}
				}
			}

			$where .= ' ) ';

			$list_group = $wpdb->get_results( "SELECT DISTINCT P.ID FROM {$wpdb->posts} as P JOIN {$wpdb->postmeta} AS PM ON PM.post_id=P.ID WHERE P.post_type='" . $this->get_post_type() . "'" . $where );
			$list_model = array();
			if ( ! empty( $list_group ) ) {
				foreach ( $list_group as $element ) {
					$list_model[] = $this->get( array(
						'id' => $element->ID,
					) );
				}
			}

			return $list_model;
		}

		/**
		 * Retournes le nom de la catégorie attachée au post.
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @return string Le nom de la catégorie.
		 */
		public function get_attached_taxonomy() {
			return $this->attached_taxonomy_type;
		}

	}
} // End if().
