<?php 
/*Remove WooCommerce elements*/
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_cart_collaterals',		 'woocommerce_cross_sell_display' );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 60 );


/*WooCommerce settings*/ 
if ( ! function_exists( 'anaglyph_before_loop_products' ) ) {
	function anaglyph_before_loop_products() {
		$class = "shop-loop";
		if (is_single()) { $class = "shop-single"; }
	?>
		
		<section id="<?php echo $class; ?>" class="block">
			<div class="container">
				<div class="row">
				<?php 
					if (is_single()) { 
						echo '<div class="col-md-12">';
					}
				?>	
	<?php					
	}
	add_action( 'woocommerce_before_main_content', 'anaglyph_before_loop_products', 5 );
}								


if ( ! function_exists( 'anaglyph_after_loop_products' ) ) {
	function anaglyph_after_loop_products() {
	?>							
				<?php 
					if (is_single()) { 
						echo '</div>';
					}
				?>	
				</div>	
			</div>	
		</section>	
	<?php					
	}
	add_action( 'woocommerce_after_main_content', 'anaglyph_after_loop_products', 99 );
}								

if ( ! function_exists( 'anaglyph_woo_page_title' ) ) {
	function anaglyph_woo_page_title() {
		global $anaglyph_config;
		if ( apply_filters( 'woocommerce_show_page_title', true ) ) { 
	?>
			<!-- Page Title -->
			<section id="page-title">
				<div class="title">
					<?php if (is_single()) { ?>
						<h1 class="reset-margin"><?php the_title(); ?></h1>
					<?php } else { ?>
						<h1 class="reset-margin"><?php woocommerce_page_title(); ?></h1>
					<?php } ?>
				</div>
				<?php 
					if ( !empty($anaglyph_config['shopheader-image'])) { 
						$simg = $anaglyph_config['shopheader-image'];
						echo '<img src="'.esc_url($simg['url']).'" class="parallax-bg" alt="">';
					}	
				?>
	</section>
	<!-- end Page Title -->
	<?php
		}
	}	
}

if ( ! function_exists( 'anaglyph_woo_get_loop_shop_content' ) ) {
	function anaglyph_woo_get_loop_shop_content() {
		global $anaglyph_config, $time_post_delay;
		$layout = 1;
		$class_content = 'col-md-12';
		$layout = esc_attr($anaglyph_config['shop-layout']);
		if ($layout > 1)  {
			$class_content = 'col-md-9';
		}
		
		
	?>
		<?php
			/**
			* woocommerce_before_main_content hook
			*
			* @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			* @hooked woocommerce_breadcrumb - 20
			*/
			do_action( 'woocommerce_before_main_content' );
			do_action( 'woocommerce_archive_description' ); 
		?>
		<div class="<?php echo $class_content; ?>">	
	
		<?php if ( have_posts() ) : ?>
			<?php
			  /**
				* woocommerce_before_shop_loop hook
				*
				* @hooked woocommerce_result_count - 20
				* @hooked woocommerce_catalog_ordering - 30
				*/
				do_action( 'woocommerce_before_shop_loop' );
			?>
			<?php woocommerce_product_loop_start(); ?>
			<?php woocommerce_product_subcategories(); ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php wc_get_template_part( 'content', 'product' ); ?>
					<?php $time_post_delay += 0.2; ?>
				<?php endwhile; // end of the loop. ?>
			<?php woocommerce_product_loop_end(); ?>
			
			<?php
			  /**
				* woocommerce_after_shop_loop hook
				*
				* @hooked woocommerce_pagination - 10
				*/
				do_action( 'woocommerce_after_shop_loop' );
			?>
		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
			<?php wc_get_template( 'loop/no-products-found.php' ); ?>
		<?php endif; ?>
		</div> <!-- end products loop content -->
		
		
		<?php
		  /**
			* woocommerce_after_main_content hook
			*
			* @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			*/
			do_action( 'woocommerce_after_main_content' );
	}
}	

if ( ! function_exists( 'anaglyph_get_woo_sidebar_content' ) ) {
	function anaglyph_get_woo_sidebar_content(){
	?>
		<div class="col-md-3">
			<?php woocommerce_get_sidebar(); ?>	
		</div>	
	<?php 
	}
}

if ( ! function_exists( 'anaglyph_get_woo_sidebar' ) ) {
	function anaglyph_get_woo_sidebar() {
		global $anaglyph_config;
		$layout = 1;
		$layout = esc_attr($anaglyph_config['shop-layout']);
		if ($layout > 1)  {
			if ($layout == 2) {
				add_action ('woocommerce_before_main_content', 'anaglyph_get_woo_sidebar_content', 6);		
			} else {
				add_action ('woocommerce_after_main_content',  'anaglyph_get_woo_sidebar_content', 98);		
			}
		}
	}
}	

add_filter( 'loop_shop_per_page', 'anaglyph_woo_set_product_per_page', 20 );
if ( ! function_exists( 'anaglyph_woo_set_product_per_page' ) ) {
	function anaglyph_woo_set_product_per_page($cols) {
		global $anaglyph_config;	
		if ((int) $anaglyph_config['shop-product-perpage'] > 0) {
			return (int) $anaglyph_config['shop-product-perpage'];
		}
	}	
}	


add_filter('loop_shop_columns', 'anaglyph_woo_loop_columns');
if (!function_exists('anaglyph_woo_loop_columns')) {
	function anaglyph_woo_loop_columns() {
		global $anaglyph_config, $anaglyph_is_redux_active;	
		if ($anaglyph_is_redux_active ) {
			if ((int) $anaglyph_config['shop-product-perrow'] > 0) {
				return (int) $anaglyph_config['shop-product-perrow'];
			}
		} else {
			return 4;
		}
	}
}

if (!function_exists('anaglyph_woo_get_column_class')) {
	function anaglyph_woo_get_column_class() {
		global $anaglyph_config;	
		if ((int) $anaglyph_config['shop-product-perrow'] > 0) {
			return 'woo-column-' . $anaglyph_config['shop-product-perrow'];
		} else {
			return 'woo-column-4';
		}
	}
}


add_action( 'woocommerce_before_shop_loop_item_title', 'anaglyph_woo_set_products_image_size', 10 );
if (!function_exists('anaglyph_woo_set_image_size')) {
	function anaglyph_woo_set_products_image_size() {
		global $anaglyph_config;	
			if ((int) $anaglyph_config['shop-product-perrow'] == 2) {
				echo woocommerce_get_product_thumbnail(array(560, 560));
			} else if ((int) $anaglyph_config['shop-product-perrow'] == 3) {
				echo woocommerce_get_product_thumbnail(array(420, 420));
			} else {
				echo woocommerce_get_product_thumbnail();
			}
	}
}

if ( ! function_exists( 'anaglyph_woo_breadcrumbs' ) ) {
	function anaglyph_woo_breadcrumbs() {
		global $anaglyph_config, $anaglyph_is_redux_active;
		if (!empty($anaglyph_config['pp-breadcrumbs']) || !$anaglyph_is_redux_active) {
			if ($anaglyph_config['pp-breadcrumbs'] || !$anaglyph_is_redux_active) {
				$args = array();
				$args = apply_filters( 'woocommerce_breadcrumb_defaults', array(
					'delimiter'   => '',
					'wrap_before' => '<section id="breadcrumb"' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '><div class="container"><ol class="breadcrumb">',
					'wrap_after'  => '</ol></div></section>',
					'before'      => '<li>',
					'after'       => '</li>',
					'home'        => _x( 'Home', 'breadcrumb', 'anaglyph-lite' ),
				) );
					woocommerce_breadcrumb($args); 
			}
		}
	}
}

if ( ! function_exists( 'anaglyph_woo_product_custom_classes' ) ) {
	function anaglyph_woo_product_custom_classes( $classes ) {
		global 	$post, $woocommerce, $product, $anaglyph_config; 
		$attachment_ids = '';		
		$columns_in = (int) $anaglyph_config['shop-product-perrow'];
		
		$post_type = get_post_type( get_the_ID() );
		if ( $post_type == 'product' ) {
			 $productObj = new WC_Product($product);
			 $attachment_ids = $productObj->get_gallery_attachment_ids();
			if (!empty($attachment_ids)) {
				$classes[] = 'anaglyph-woo-has-gallery';
			}
		}
		return $classes;
	}
}
add_filter( 'post_class', 'anaglyph_woo_product_custom_classes' );

			
if ( ! function_exists( 'anaglyph_woo_template_loop_second_product_thumbnail' ) ) {
	function anaglyph_woo_template_loop_second_product_thumbnail() {
		global  $post, $woocommerce, $product,  $anaglyph_config;
		$columns_in = (int) $anaglyph_config['shop-product-perrow'];
		
		$attachment_ids = '';
		$size 			= 'full';
		$productObj = new WC_Product($product);
		$attachment_ids = $productObj->get_gallery_attachment_ids();

		if ( !empty($attachment_ids) ) {
			$secondary_image_id = $attachment_ids['0'];
			if ($columns_in == 2) {
				$size = array(560, 560);
			} else if ($columns_in == 3) {
				$size = array(420, 420);
			} else if ($columns_in == 1) {
				$size ='full';
			} else {
				$size = array(258, 258);
			}
			
			echo wp_get_attachment_image( $secondary_image_id, $size, '', $attr = array( 'class' => 'anaglyph-second-image attachment-shop-catalog' ) );
		}
	}
}	
add_action( 'woocommerce_before_shop_loop_item_title', 'anaglyph_woo_template_loop_second_product_thumbnail', 11 );