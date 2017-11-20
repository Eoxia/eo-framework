<?php
/**
 * The actions button for the gallery.
 *
 * @author Eoxia
 * @since 1.0.0
 * @version 1.0.0
 * @copyright 2017
 * @package EO-Framework/WPEO-Upload
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<ul class="action">
	<?php if ( 'false' === $data['single'] ) : ?>
		<li>
			<a 	href="#"
					data-action="eo_upload_set_thumbnail"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'set_thumbnail' ) ); ?>"
					<?php echo WPEO_Upload_Class::g()->out_all_attributes( $data ); // WPCS: XSS is ok. ?>
					data-file-id="<?php echo esc_attr( $main_picture_id ); ?>"
					class="edit-thumbnail-id action-attribute"><?php esc_html_e( 'Set as default thumbnail', 'wpeo-upload' ); ?></a>
		</li>
	<?php endif; ?>
	<li>
		<a 	href="#"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'dissociate_file' ) ); ?>"
				data-action="eo_upload_dissociate_file"
				<?php echo WPEO_Upload_Class::g()->out_all_attributes( $data ); // WPCS: XSS is ok. ?>
				data-file-id="<?php echo esc_attr( $main_picture_id ); ?>"
				class="edit-thumbnail-id action-attribute" ><i></i><?php esc_html_e( 'Dissociate', 'wpeo-upload' ); ?></a>
	</li>
	<li>
		<a class="edit-link" target="_blank" href="<?php echo esc_attr( admin_url( 'upload.php?item=' . $main_picture_id . '&mode=edit' ) ); ?>"><?php esc_html_e( 'Edit', 'wpeo-upload' ); ?></a>
	</li>
</ul>
