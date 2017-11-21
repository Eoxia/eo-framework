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

<div class="wpeo-button button-primary upload no-file"
			<?php echo WPEO_Upload_Class::g()->out_all_attributes( $data ); // WPCS: XSS is ok. ?>
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'associate_file' ) ); ?>">
	<span><?php esc_html_e( 'Add new media', 'wpeo-upload' ); ?></span>
</div>