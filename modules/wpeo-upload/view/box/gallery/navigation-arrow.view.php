<?php
/**
 * Arrow for navigate in the gallery.
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

<?php if ( 'false' === $data['single'] ) : ?>
	<ul class="navigation">
		<li><a href="#" class="prev"><i class="dashicons dashicons-arrow-left-alt2"></i></a></li>
		<li><a href="#" class="next"><i class="dashicons dashicons-arrow-right-alt2"></i></a></li>
	</ul>
<?php endif; ?>
