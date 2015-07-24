<?php
/**
 * The Shop Sidebar containing Widget areas for Shop Page.
 *
 * @package WordPress
 * @subpackage Fruitful theme
 * @since Fruitful theme 1.0
 */
?>
<div id="secondary">		
	<?php if ( is_active_sidebar( 'shop' ) ) : ?>
	<div id="shop-sidebar" class="primary-sidebar widget-area" role="complementary">
		<?php dynamic_sidebar( 'shop' ); ?>
	</div><!-- #primary-sidebar -->
	<?php endif; ?>
</div><!-- #secondary .widget-area -->
