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

		/**
		 * Fonction de callback après avoir récupérer le modèle en mode GET.
		 *
		 * @var array
		 */
		protected $after_get_function = array( '\eoxia\after_get_post' );

		/**
		 * Fonction de callback avant d'insérer les données en mode POST.
		 *
		 * @var array
		 */
		protected $before_post_function = array();

		/**
		 * Fonction de callback avant de dispacher les données en mode POST.
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

			// L'argument "include" était utilisé, mais est devenu obsolète. Permet de garder une compatibilité.
			if ( ! empty( $args['include'] ) ) {
				$args['post__in'] = $args['include'];
				if ( ! is_array( $args['post__in'] ) ) {
					$args['post__in'] = (array) $args['post__in'];
				}
				unset( $args['include'] );
			}

			if ( isset( $args['p'] ) ) {
				if ( ! isset( $args['post__in'] ) ) {
					$args['post__in'] = array();
				}

				$args['post__in'] = array_merge( (array) $args['p'], $args['post__in'] );
				unset( $args['p'] );
			}

			// Si l'argument "schema" est présent c'est lui qui prend le dessus et ne va pas récupérer d'élément dans la base de données.
			if ( isset( $args['schema'] ) ) {
				$array_posts[] = array();
			} else { // On lance la requête pour récupèrer les "posts" demandés.
				$query_posts = new \WP_Query( wp_parse_args( $args, $default_args ) );
				$array_posts = $query_posts->posts;
				unset( $query_posts->posts );
			}

			$array_posts = $this->build_objects( $array_posts, 'ID', 'get_post_meta', $req_method );

			// Si on a demandé qu'une seule entrée et qu'il n'y a bien qu'une seule entrée correspondant à la demande alors on ne retourne que cette entrée.
			if ( true === $single && 1 === count( $array_posts ) ) {
				$array_posts = $array_posts[0];
			}

			return $array_posts;
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
			$before_cb  = 'before_' . $req_method . '_function';
			$after_cb   = 'after_' . $req_method . '_function';
			$args_cb    = array( 'model_name' => $model_name );

			if ( empty( $data['type'] ) ) {
				$data['type'] = $this->get_type();
			}

			$data = Model_Util::exec_callback( $this->$before_cb, $data, $args_cb );

			if ( ! empty( $data['id'] ) ) {
				$current_data = $this->get( array(
					'id'          => $data['id'],
					'use_context' => $context,
				), true );

				$data = Array_Util::g()->recursive_wp_parse_args( $data, $current_data->data );
			}

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

			$object = new $model_name( $data, $req_method );

			if ( empty( $object->data['id'] ) ) {
				$inserted_post = wp_insert_post( $object->convert_to_wordpress(), true );
				if ( is_wp_error( $inserted_post ) ) {
					return $inserted_post;
				}

				$object->data['id'] = $inserted_post;
			} else {
				$update_state = wp_update_post( $object->convert_to_wordpress(), true );

				if ( is_wp_error( $update_state ) ) {
					return $update_state;
				}

				// Si on envoi date_modified a notre objet, on modifie en "dur" car bloqué par WordPress de base.
				if ( ! empty( $data ) && empty( $data['date_modified'] ) && ! empty( $data['date_modified'] ) ) {
					$GLOBALS['wpdb']->update( $GLOBALS['wpdb']->posts, array( 'date_modified' => $data['date_modified'] ) );
				}
			}

			Save_Meta_Class::g()->save_meta_data( $object, 'update_post_meta', $this->meta_key );

			// Save taxonomy!
			$this->save_taxonomies( $object, $append );

			$object = Model_Util::exec_callback( $this->$after_cb, $object, $args_cb );

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

		/**
		 * Sauvegardes les taxonomies
		 *
		 * @version 1.0.0
		 * @since 1.0.0
		 *
		 * @param object  $object L'objet avec les taxonomies à sauvegarder.
		 * @param boolean $append La taxonomie doit elle être ajoutée à la liste existante ou remplacer la liste existante.
		 */
		private function save_taxonomies( $object, $append ) {
			if ( ! empty( $object->data['taxonomy'] ) ) {
				foreach ( $object->data['taxonomy'] as $taxonomy_name => $taxonomy_data ) {
					if ( ! empty( $taxonomy_name ) ) {
						if ( is_int( $taxonomy_data ) || is_array( $taxonomy_data ) ) {
							wp_set_object_terms( $object->data['id'], $taxonomy_data, $taxonomy_name, $append );
						}
					}
				}
			}
		}

	}
} // End if().
