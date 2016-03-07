<?php
/**
 * The Sidebar containing the main blog widget area
 *
 * @package WordPress
 * @subpackage Anaglyph_Theme
 * @since Anaglyph Theme 1.0
 */
?>
<div id="secondary">
	<?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
	<div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-blog' ); ?>
	</div><!-- #primary-sidebar -->
	<?php endif; ?>
</div><!-- #secondary -->