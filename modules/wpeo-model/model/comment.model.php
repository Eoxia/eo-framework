<?php
/**
 * Define schema for WP_Comment.
 *
 * @see https://eoxia.com/documentation/eoframework/references/schema
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 0.1.0
 * @version 1.0.0
 * @copyright 2015+
 * @package EO_Framework
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\eoxia\Comment_Model' ) ) {

	/**
	 * Define schema for WP_Comment.
	 */
	class Comment_Model extends Constructor_Data_Class {

		/**
		 * Init schema for WP_Comment.
		 *
		 * @var array
		 */
		protected $schema = array();

		/**
		 * Construct define the schema
		 *
		 * @since 0.1.0
		 * @version 1.0.0
		 *
		 * @param Comment_Model $object The object.
		 */
		public function __construct( Comment_Model $object ) {
			$this->schema['id'] = array(
				'type'        => 'integer',
				'field'       => 'comment_ID',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the comment_ID of the wp_comment table from WordPress DB and this field',
			);

			$this->schema['parent_id'] = array(
				'type'        => 'integer',
				'field'       => 'comment_parent',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the comment_parent of the wp_comment table from WordPress DB and this field',
			);

			$this->schema['post_id'] = array(
				'type'        => 'integer',
				'field'       => 'comment_post_ID',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the comment_post_ID of the wp_comment table from WordPress DB and this field',
			);

			$this->schema['date'] = array(
				'type'        => 'wpeo_date',
				'field'       => 'comment_date',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the comment_date of the wp_comment table from WordPress DB and this field',
			);

			$this->schema['author_id'] = array(
				'type'        => 'integer',
				'field'       => 'user_id',
				'bydefault'   => get_current_user_id(),
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the user_id of the wp_comment table from WordPress DB and this field',
			);

			$this->schema['author_nicename'] = array(
				'type'        => 'string',
				'field'       => 'comment_author',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the author_nicename of the wp_comment table from WordPress DB and this field',
			);

			$this->schema['author_email'] = array(
				'type'        => 'string',
				'field'       => 'comment_author_email',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the author_email of the wp_comment table from WordPress DB and this field',
			);

			$this->schema['author_ip'] = array(
				'type'        => 'string',
				'field'       => 'comment_author_IP',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the author_ip of the wp_comment table from WordPress DB and this field',
			);

			$this->schema['content'] = array(
				'type'        => 'string',
				'field'       => 'comment_content',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the content of the wp_comment table from WordPress DB and this field',
			);

			$this->schema['type'] = array(
				'type'        => 'string',
				'field'       => 'comment_type',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the type of the wp_comment table from WordPress DB and this field',
			);

			parent::__construct( $data );
		}
	}
} // End if().
