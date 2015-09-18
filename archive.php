<?php
/**
 * The template for displaying Archive pages
 * @package WordPress
 * @subpackage Anaglyph_Theme
 * @since Anaglyph Theme 1.0
 */

get_header(); 
?>
	<!-- Page Title -->
	<section id="page-title">
		<div class="title">
			<h1 class="reset-margin"><?php
				if ( is_day() ) :
					printf( __( 'Daily Archives: %s', 'anaglyph-lite' ), get_the_date() );
				elseif ( is_month() ) :
					printf( __( 'Monthly Archives: %s', 'anaglyph-lite' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'anaglyph-lite' ) ) );

				elseif ( is_year() ) :
					printf( __( 'Yearly Archives: %s', 'anaglyph-lite' ), get_the_date( _x( 'Y', 'yearly archives date format', 'anaglyph-lite' ) ) );

				else :
					_e( 'Archives', 'anaglyph-lite' );

				endif;
			?></h1>
		</div>
		<?php anaglyph_custom_image('archive-image'); ?>
	</section>
	<!-- end Page Title -->
	
	<?php anaglyph_add_breadcrumbs(); ?>
	<?php anaglyph_default_page_content(); ?>

<?php
	get_footer();
