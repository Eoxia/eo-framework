<?php
/**
 * Gestion des termes (POST, PUT, GET, DELETE)
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

if ( ! class_exists( '\eoxia\Term_Class' ) ) {

	/**
	 * Gestion des termes (POST, PUT, GET, DELETE)
	 */
	class Term_Class extends Object_Class {

		/**
		 * Le nom du modèle
		 *
		 * @var string
		 */
		protected $model_name = 'term_model';

		/**
		 * La clé principale pour post_meta
		 *
		 * @var string
		 */
		protected $meta_key = '_wpeo_term';

		/**
		 * Le nom de la taxonomie
		 *
		 * @var string
		 */
		protected $type = 'category';

		/**
		 * Slug de base pour la route dans l'api rest
		 *
		 * @var string
		 */
		protected $base = 'category';

		/**
		 * Pour l'association de la taxonomy
		 *
		 * @var string|array
		 */
		protected $associate_post_types = array();

		/**
		 * Utiles pour récupérer la clé unique
		 *
		 * @todo Rien à faire ici
		 * @var string
		 */
		protected $identifier_helper = 'term';

		/**
		 * La liste des droits a avoir pour accèder aux différentes méthodes
		 *
		 * @var array
		 */
		protected $capabilities = array(
			'get'    => 'read',
			'put'    => 'manage_categories',
			'post'   => 'manage_categories',
			'delete' => 'manage_categories',
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
		 * Fonction de callback après avoir mis à jour les données en mode PUT.
		 *
		 * @var array
		 */
		protected $after_put_function = array();

		/**
		 * Le constructeur
		 *
		 * @return void
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 */
		protected function construct() {
			parent::construct();

			add_action( 'init', array( $this, 'callback_init' ) );
		}

		/**
		 * Initialise la taxonomie
		 *
		 * @since 1.0.0
		 * @version 1.0.0
		 *
		 * @return void
		 */
		public function callback_init() {
			$args = array(
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
			);

			register_taxonomy( $this->get_type(), $this->associate_post_types, $args );
		}

		/**
		 * Récupères les données selon le modèle définis.
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @param array   $args Les paramètres de get_terms @https://codex.wordpress.org/Function_Reference/get_terms.
		 * @param boolean $single Si on veut récupérer un tableau, ou qu'une seule entrée.
		 *
		 * @return Object
		 */
		public function get( $args = array(), $single = false ) {
			$list_term  = array();
			$array_term = array();

			$model_name = $this->model_name;

			$term_final_args = array_merge( $args, array(
				'hide_empty' => false,
			) );

			if ( empty( $term_final_args['taxonomy'] ) ) {
				$term_final_args['taxonomy'] = $this->get_type();
			}

			if ( isset( $args['id'] ) ) {
				$array_term[] = get_term_by( 'id', $args['id'], $this->get_type(), ARRAY_A );
			} elseif ( isset( $args['post_id'] ) ) {
				$array_term = wp_get_post_terms( $args['post_id'], $this->get_type(), $term_final_args );

				if ( empty( $array_term ) ) {
					$array_term[] = array();
				}
			} elseif ( isset( $args['schema'] ) ) {
				$array_term[] = array();
			} else {
				$array_terms = new \WP_Term_Query( $term_final_args );
				$array_term = $array_terms->terms;
				unset( $array_term->terms );
			}

			if ( empty( $array_term ) ) {
				$array_term[] = array();
			}

			if ( ! empty( $array_term ) ) {
				foreach ( $array_term as $key => $object ) {
					$object = (array) $object;

					if ( ! empty( $args['post_id'] ) ) {
						$object['post_id'] = $args['post_id'];
					}

					// Si $object['term_id'] existe, on récupère les meta.
					if ( ! empty( $object['term_id'] ) ) {
						$object = $this->prepare_item_meta_for_response( get_term_meta, $object['term_id'], $this->meta_key );
					}

					$list_term[ $key ] = new $model_name( $object, 'get' );

					$list_term[ $key ] = Model_Util::exec_callback( $this->after_get_function, $list_term[ $key ], array( 'model_name' => $model_name ) );
				}
			}

			if ( true === $single && 1 === count( $list_term ) ) {
				$list_term = $list_term[0];
			}

			return $list_term;
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

			$data = Model_Util::exec_callback( $this->$before_cb, $data, $args_cb );

			if ( ! empty( $data['id'] ) ) {
				$current_data = $this->get( array(
					'id' => $data['id'],
				), true );

				$data = array_merge( $data, (array) $current_data->data );
			}

			$object = new $model_name( $data, $req_method );

			if ( empty( $object->data['id'] ) ) {
				$term = wp_insert_term( $object->data['name'], $this->get_type(), $object->convert_to_wordpress() );
			} else {
				$term = wp_update_term( $object->data['id'], $this->get_type(), $object->convert_to_wordpress() );
			}

			if ( is_wp_error( $term ) ) {
				if ( ! empty( $term->error_data['term_exists'] ) && is_int( $term->error_data['term_exists'] ) ) {
					return $this->get( array(
						'id' => $term->error_data['term_exists'],
					), true );
				}

				return $term;
			}

			$object->data['id']               = $term['term_id'];
			$object->data['term_taxonomy_id'] = $term['term_taxonomy_id'];

			Save_Meta_Class::g()->save_meta_data( $object, 'update_term_meta', $this->meta_key );

			$object = Model_Util::exec_callback( $this->$after_cb, $object, $args_cb );

			return $object;
		}

		/**
		 * Supprime un term
		 *
		 * @todo: Inutile ?
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @param int $id L'ID du term (term_id).
		 */
		public function delete( $id ) {
			wp_delete_term( $id );
		}

	}
} // End if().
