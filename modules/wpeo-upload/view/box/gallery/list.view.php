<?php
/**
 * The list for the gallery
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

<ul class="image-list">
	<li data-id="<?php echo esc_attr( $main_picture_id ); ?>" class="current">
		<?php
		if ( '' === $data['mime_type'] ) :
			echo wp_get_attachment_image( $main_picture_id, 'full' );
		else :
			?>
			<i class="fa fa-paperclip" aria-hidden="true"></i>
			<?php
		endif;
		?>
	</li>

	<?php if ( ! empty( $list_id ) ) : ?>
		<?php foreach ( $list_id as $key => $id ) : ?>
			<?php if ( $main_picture_id !== $id ) : ?>
				<li data-id="<?php echo esc_attr( $id ); ?>" class="hidden">
					<?php
					if ( '' === $data['mime_type'] ) :
						echo wp_get_attachment_image( $id, 'full' );
					else :
						?>
						<i class="fa fa-paperclip" aria-hidden="true"></i>
						<?php
					endif;
					?>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</ul>
