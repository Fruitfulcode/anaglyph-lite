<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header( 'shop' ); ?>

	<section id="shop-page">
		<?php anaglyph_woo_page_title(); ?>
		<?php anaglyph_woo_breadcrumbs(); ?>
		<?php anaglyph_get_woo_sidebar(); ?>
		<?php anaglyph_woo_get_loop_shop_content(); ?>
	</section> <!-- End section shop-page -->
	
<?php get_footer( 'shop' ); ?>