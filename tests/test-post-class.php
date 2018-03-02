<?php
/**
 * Class SampleTest
 *
 * @package EO_Framework
 */

/**
 * Sample test case.
 */
class TestPostClass extends WP_UnitTestCase {

	/**
	 * @dataProvider get_data
	 */
	function test_create_post($data, $expected) {
		$post = \eoxia\Post_Class::g()->create( $data, true );

		$this->assertInstanceOf($expected['is_instance'], $post);

		$this->assertEquals($post->data['title'], $expected['data']['title']);
		$this->assertInternalType($expected['schema']['title']['type'], $post->data['title']);

		$this->assertEquals($post->data['author_id'], $expected['data']['author_id']);
		$this->assertInternalType($expected['schema']['author_id']['type'], $post->data['author_id']);

	}

	function get_data() {
		return array(
			array(
				array(
					'title'     => 'Hello tout le monde !',
					'author_id' => 1,
				),
				array(
					'is_instance' => \eoxia\Post_Model::class,
					'data'        => array(
						'title'     => 'Hello tout le monde !',
						'author_id' => 1,
					),
					'schema'      => \eoxia\Post_Class::g()->get_schema(),
				),
			),
		);
	}
}
