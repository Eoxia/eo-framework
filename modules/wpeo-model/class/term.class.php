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
	class Term_Class extends Rest_Class {

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
		protected $taxonomy = 'category';

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

			register_taxonomy( $this->taxonomy, $this->associate_post_types, $args );
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
			$model = new $model_name( array() );
			return $model->get_model();
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
			$list_term = array();
			$array_term = array();

			$model_name = $this->model_name;

			$term_final_args = array_merge( $args, array(
				'hide_empty' => false,
			) );


			if ( ! empty( $args['id'] ) ) {
				$array_term[] = get_term_by( 'id', $args['id'], $this->taxonomy, ARRAY_A );
			} elseif ( ! empty( $args['post_id'] ) ) {
				$array_term = wp_get_post_terms( $args['post_id'], $this->taxonomy, $term_final_args );

				if ( empty( $array_term ) ) {
					$array_term[] = array();
				}
			} elseif ( isset( $args['schema'] ) ) {
				$array_term[] = array();
			} else {
				$array_term = get_terms( $this->taxonomy, $term_final_args );
			}

			if ( ! empty( $array_term ) ) {
				foreach ( $array_term as $key => $term ) {
					$term = (array) $term;

					if ( ! empty( $args['post_id'] ) ) {
						$term['post_id'] = $args['post_id'];
					}

					if ( ! empty( $term['term_id'] ) ) {
						$list_meta = get_term_meta( $term['term_id'] );
						foreach ( $list_meta as &$meta ) {
							$meta = array_shift( $meta );
						}

						$term = array_merge( $term, $list_meta );

						if ( ! empty( $term[ $this->meta_key ] ) ) {
							$term = array_merge( $term, json_decode( $term[ $this->meta_key ], true ) );
							unset( $term[ $this->meta_key ] );
						}
					}

					$list_term[ $key ] = new $model_name( $term, 'get' );

					$list_term[ $key ] = Model_Util::exec_callback( $this->after_get_function, $list_term[ $key ], array( 'model_name' => $model_name )  );
				}
			}

			if ( true === $single && 1 === count( $list_term ) ) {
				$list_term = $list_term[0];
			}

			return $list_term;
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

			$data = Model_Util::exec_callback( $this->$before_cb, $data, $args_cb );

			if ( ! empty( $data['id'] ) ) {
				$current_data = $this->get( array(
					'id' => $data['id'],
				), true );

				$data = array_merge( (array) $current_data, $data );
			}

			$data = new $model_name( $data, $req_method );

			if ( empty( $data->id ) ) {
				$term = wp_insert_term( $data->name, $this->get_type(), $data->convert_to_wordpress() );
			} else {
				$term = wp_update_term( $data->id, $this->get_type(), $data->convert_to_wordpress() );
			}

			if ( is_wp_error( $term ) ) {
				if ( ! empty( $term->error_data['term_exists'] ) && is_int( $term->error_data['term_exists'] ) ) {
					return $this->get( array(
						'id' => $term->error_data['term_exists'],
					), true );
				}

				return $term;
			}

			$data->id               = $term['term_id'];
			$data->term_taxonomy_id = $term['term_taxonomy_id'];

			Save_Meta_Class::g()->save_meta_data( $data, 'update_term_meta', $this->meta_key );

			$data = Model_Util::exec_callback( $this->$after_cb, $data, $args_cb );

			return $data;
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

		/**
		 * Renvoie le type de la taxonomie.
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @return string Le type du commentaire.
		 */
		public function get_type() {
			return $this->taxonomy;
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
