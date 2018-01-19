<?php
/**
 * Définition des données des commentaires
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.0.0
 * @copyright 2015-2018
 * @package EO_Framework
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\eoxia\Comment_Model' ) ) {
	/**
	 * Définition des données des commentaires
	 */
	class Comment_Model extends Data_Class {

		/**
		 * Définition du modèle principal des commentaires
		 *
		 * @var array Les champs principaux des commentaires
		 */
		protected $model = array(
			'id' => array(
				'type' => 'integer',
				'field' => 'comment_ID',
			),
			'parent_id' => array(
				'type' => 'integer',
				'field' => 'comment_parent',
			),
			'post_id' => array(
				'type' => 'integer',
				'field' => 'comment_post_ID',
				'required' => true,
			),
			'date' => array(
				'type' => 'wpeo_date',
				'field' => 'comment_date',
			),
			'author_id' => array(
				'type' => 'integer',
				'field' => 'user_ID',
			),
			'author_nicename' => array(
				'type' => 'string',
				'field' => 'comment_author',
			),
			'author_email' => array(
				'type' => 'string',
				'field' => 'comment_author_email',
			),
			'author_url' => array(
				'type' => 'string',
				'field' => 'comment_author_url',
			),
			'author_ip' => array(
				'type' => 'string',
				'field' => 'comment_author_IP',
			),
			'content' => array(
				'type' => 'string',
				'field' => 'comment_content',
				'required' => true,
			),
			'status' => array(
				'type' => 'string',
				'field' => 'comment_approved',
			),
			'type' => array(
				'type' => 'string',
				'field' => 'comment_type',
				'required' => true,
			),
		);

		/**
		 * Le constructeur
		 *
		 * @since 1.0.0
		 * @version 1.5.0
		 *
		 * @param array $data Les données de l'objet.
		 */
		public function __construct( $data ) {
			parent::__construct( $data );
		}
	}
} // End if().
