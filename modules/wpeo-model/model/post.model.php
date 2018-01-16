<?php
/**
 * Define schema for WP_Post.
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

if ( ! class_exists( '\eoxia\Post_Model' ) ) {

	/**
	 * Define schema for WP_Post.
	 */
	class Post_Model extends Constructor_Data_Class {

		/**
		 * Init schema for WP_Post.
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
		 * @param Post_Model $object The object.
		 */
		public function __construct( Post_Model $object ) {
			$this->schema['id'] = array(
				'type'        => 'integer',
				'field'       => 'ID',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the ID of the wp_post table from WordPress DB and this field',
			);

			$this->schema['parent_id'] = array(
				'type'        => 'integer',
				'field'       => 'post_parent',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the post_parent of the wp_post table from WordPress DB and this field',
			);

			$this->schema['author_id'] = array(
				'type'        => 'integer',
				'field'       => 'post_author',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the post_author of the wp_post table from WordPress DB and this field',
			);

			$this->schema['date'] = array(
				'type'        => 'wpeo_date',
				'field'       => 'post_date',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the post_date of the wp_post table from WordPress DB and this field',
			);

			$this->schema['date_modified'] = array(
				'type'        => 'wpeo_date',
				'field'       => 'post_modified',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the post_modified of the wp_post table from WordPress DB and this field',
			);

			$this->schema['title'] = array(
				'type'        => 'string',
				'field'       => 'post_title',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the post_title of the wp_post table from WordPress DB and this field',
			);

			$this->schema['slug'] = array(
				'type'        => 'string',
				'field'       => 'post_name',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the slug of the wp_post table from WordPress DB and this field',
			);

			$this->schema['content'] = array(
				'type'        => 'string',
				'field'       => 'post_content',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the content of the wp_post table from WordPress DB and this field',
			);

			$this->schema['status'] = array(
				'type'        => 'string',
				'field'       => 'post_status',
				'bydefault'   => 'publish',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the status of the wp_post table from WordPress DB and this field',
			);

			$this->schema['link'] = array(
				'type'        => 'string',
				'field'       => 'guid',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the link of the wp_post table from WordPress DB and this field',
			);

			$this->schema['type'] = array(
				'type'        => 'string',
				'field'       => 'post_type',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the type of the wp_post table from WordPress DB and this field',
			);

			$this->schema['order'] = array(
				'type'        => 'int',
				'field'       => 'menu_order',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the order of the wp_post table from WordPress DB and this field',
			);

			$this->schema['comment_status'] = array(
				'type'        => 'string',
				'field'       => 'comment_status',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the comment_status of the wp_post table from WordPress DB and this field',
			);

			$this->schema['comment_count'] = array(
				'type'        => 'int',
				'field'       => 'comment_count',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the comment_count of the wp_post table from WordPress DB and this field',
			);

			$this->schema['thumbnail_id'] = array(
				'type'        => 'int',
				'meta_type'   => 'single',
				'field'       => '_thumbnail_id',
				'since'       => '0.1.0',
				'version'     => '1.0.0',
				'description' => 'Makes the join between the thumbnail_id of the wp_post table from WordPress DB and this field',
			);

			parent::__construct( $data );
		}
	}
} // End if().
