<?php
/**
 * The gallery
 *
 * @author Eoxia
 * @since 0.1.0-alpha
 * @version 1.0.0
 * @copyright 2017
 * @package EO-Framework/WPEO-Upload
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-modal modal-active gallery" data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_gallery' ) ); ?>">
	<div class="modal-container">

		<!-- Entête -->
		<div class="modal-header">
			<h2 class="modal-title">
				<?php
				echo wp_kses( $data['title'], array(
					'span' => array(),
					'div' => array(
						'class' => array(),
						'data-id' => array(),
						'data-title' => array(),
						'data-mode' => array(),
						'data-field-name' => array(),
						'data-model-name' => array(),
						'data-custom-class' => array(),
						'data-size' => array(),
						'data-single' => array(),
						'data-mime-type' => array(),
						'data-display-type' => array(),
						'data-nonce' => array(),
					),
				) );
				?>
			</h2>
			<i class="modal-close fa fa-times"></i>
		</div>

		<!-- Corps -->
		<div class="modal-content">
			<?php require( \eoxia\Config_Util::$init['eo-framework']->wpeo_upload->path . '/view/box/gallery/list.view.php' ); ?>
			<?php require( \eoxia\Config_Util::$init['eo-framework']->wpeo_upload->path . '/view/box/gallery/navigation-arrow.view.php' ); ?>
			<?php require( \eoxia\Config_Util::$init['eo-framework']->wpeo_upload->path . '/view/box/gallery/actions.view.php' ); ?>
		</div>

		<!-- Footer -->
		<div class="modal-footer">
			<a class="wpeo-button button-primary button-uppercase"><span><?php esc_html_e( 'Confirm', 'wpeo-upload' ); ?></span></a>
		</div>
	</div>
</div>