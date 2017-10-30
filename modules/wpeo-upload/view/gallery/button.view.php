<?php
/**
 * The button for upload media.
 *
 * @author Eoxia
 * @since 0.1.0-alpha
 * @version 0.3.0-alpha
 * @copyright 2017
 * @package EO-Upload
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<span data-id="<?php echo esc_attr( $element->id ); ?>"
			data-model-name="<?php echo esc_attr( $atts['model_name'] ); ?>"
			data-field-name="<?php echo esc_attr( $atts['field_name'] ); ?>"
			data-custom-class="<?php echo ! empty( $atts['custom_class'] ) ? esc_attr( $atts['custom_class'] ) : ''; ?>"
			data-single="<?php echo esc_attr( $atts['single'] ); ?>"
			data-mime-type="<?php echo esc_attr( $atts['mime_type'] ); ?>"
			data-size="<?php echo esc_attr( $atts['size'] ); ?>"
			data-display-type="<?php echo esc_attr( $atts['display_type'] ); ?>"
			class="media <?php if ( empty( $main_picture_id ) ) : ?>no-file <?php endif; ?><?php echo ! empty( $atts['custom_class'] ) ? esc_attr( $atts['custom_class'] ) : ''; ?>">
	<i class="add animated fa fa-plus-circle"></i>

	<?php if ( ! empty( $main_picture_id ) ) : ?>
		<?php echo wp_get_attachment_image( $main_picture_id, $atts['size'] ); ?>
	<?php else : ?>
		<i class="default-image fa fa-picture-o"></i>
		<img src="" class="hidden"/>
		<input type="hidden" name="<?php echo esc_attr( $atts['field_name'] ); ?>" />
		&nbsp;
	<?php endif; ?>
</span>
