<?php
/**
 * The gallery
 *
 * @author Eoxia
 * @since 0.1.0-alpha
 * @version 1.1.0
 * @copyright 2017
 * @package WPEO-Upload
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div data-id="<?php echo esc_attr( $element->id ); ?>" class="eo-gallery">

	<a href="#" class="close"><i class="dashicons dashicons-no-alt"></i></a>

	<ul class="image-list">

		<li data-id="<?php echo esc_attr( $main_picture_id ); ?>" class="current"><?php echo wp_get_attachment_image( $main_picture_id, 'full' ); ?></li>
		<?php if ( ! empty( $list_id ) ) : ?>
				<?php foreach ( $list_id as $key => $id ) : ?>
					<?php if ( $main_picture_id !== $id ) : ?>
						<li data-id="<?php echo esc_attr( $id ); ?>" class="hidden"><?php echo wp_get_attachment_image( $id, 'full' ); ?></li>
					<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>

<?php if ( 'false' === $single ) : ?>
	<ul class="navigation">
		<li><a href="#" class="prev"><i class="dashicons dashicons-arrow-left-alt2"></i></a></li>
		<li><a href="#" class="next"><i class="dashicons dashicons-arrow-right-alt2"></i></a></li>
	</ul>
<?php endif; ?>

<ul class="action">
<?php if ( 'false' === $single ) : ?>
		<li>
			<a href="#"
				data-action="eo_upload_set_thumbnail"
				data-id="<?php echo esc_attr( $element->id ); ?>"
				data-model-name="<?php echo esc_attr( $model_name ); ?>"
				data-mime-type="<?php echo esc_attr( $mime_type ); ?>"
				data-size="<?php echo esc_attr( $size ); ?>"
				data-single="<?php echo esc_attr( $single ); ?>"
				data-field-name="<?php echo esc_attr( $field_name ); ?>"
				data-file-id="<?php echo esc_attr( $main_picture_id ); ?>"
			class="edit-thumbnail-id action-attribute"><?php esc_html_e( 'Set as default thumbnail', 'wpeo-upload' ); ?></a></li>
		<li>
			<a 	href="#"
				data-id="<?php echo esc_attr( $element->id ); ?>"
				data-model-name="<?php echo esc_attr( $model_name ); ?>"
				data-mime-type="<?php echo esc_attr( $mime_type ); ?>"
				data-size="<?php echo esc_attr( $size ); ?>"
				data-single="<?php echo esc_attr( $single ); ?>"
				data-field-name="<?php echo esc_attr( $field_name ); ?>"
				data-custom-class="<?php echo esc_attr( $custom_class ); ?>"
			class="media no-file" ><i></i><?php esc_html_e( 'Add a new file', 'wpeo-upload' ); ?></a></li>
<?php else : ?>
		<li>
			<a href="#"
				data-id="<?php echo esc_attr( $element->id ); ?>"
				data-model-name="<?php echo esc_attr( $model_name ); ?>"
				data-mime-type="<?php echo esc_attr( $mime_type ); ?>"
				data-size="<?php echo esc_attr( $size ); ?>"
				data-single="<?php echo esc_attr( $single ); ?>"
				data-field-name="<?php echo esc_attr( $field_name ); ?>"
				data-custom-class="<?php echo esc_attr( $custom_class ); ?>"
			class="media no-file" ><?php esc_html_e( 'Change file', 'wpeo-upload' ); ?></a></li>
<?php endif; ?>

	<li>
		<a href="#"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'eo_upload_dissociate_file' ) ); ?>"
			data-action="eo_upload_dissociate_file"
			data-id="<?php echo esc_attr( $element->id ); ?>"
			data-model-name="<?php echo esc_attr( $model_name ); ?>"
			data-mime-type="<?php echo esc_attr( $mime_type ); ?>"
			data-size="<?php echo esc_attr( $size ); ?>"
			data-single="<?php echo esc_attr( $single ); ?>"
			data-field-name="<?php echo esc_attr( $field_name ); ?>"
			data-file-id="<?php echo esc_attr( $main_picture_id ); ?>"
		class="edit-thumbnail-id action-attribute" ><i></i><?php esc_html_e( 'Dissociate', 'wpeo-upload' ); ?></a></li>
</ul>

</div>
